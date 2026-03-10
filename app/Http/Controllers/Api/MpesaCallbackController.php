<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    public function __construct(private NotificationService $notifications) {}

    public function handleCallback(Request $request)
    {
        $data = $request->input('Body.stkCallback');

        if (!$data) {
            Log::warning('M-Pesa callback: unexpected payload', ['body' => $request->all()]);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $checkoutRequestId = $data['CheckoutRequestID'] ?? null;
        $resultCode        = $data['ResultCode'] ?? null;

        Log::info('M-Pesa callback received', [
            'CheckoutRequestID' => $checkoutRequestId,
            'ResultCode'        => $resultCode,
            'ResultDesc'        => $data['ResultDesc'] ?? '',
        ]);

        $payment = Payment::where('reference', $checkoutRequestId)->first();

        if (!$payment) {
            Log::warning('M-Pesa callback: no payment found', ['CheckoutRequestID' => $checkoutRequestId]);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        if ((int) $resultCode === 0) {
            // Payment successful — extract receipt from CallbackMetadata
            $receipt = null;
            $items   = $data['CallbackMetadata']['Item'] ?? [];
            foreach ($items as $item) {
                if ($item['Name'] === 'MpesaReceiptNumber') {
                    $receipt = $item['Value'];
                    break;
                }
            }

            $payment->update([
                'status'        => PaymentStatus::COMPLETED,
                'paid_at'       => now(),
                'mpesa_receipt' => $receipt,
            ]);

            // Update the invoice status (paid / partially paid)
            $payment->invoice->updateStatus();

            // Notify the tenant
            $tenant = $payment->invoice->lease->tenant->user ?? null;
            if ($tenant) {
                $this->notifications->notify(
                    user:       $tenant,
                    type:       NotificationType::SUCCESS,
                    subject:    'Payment Received',
                    message:    "Your M-Pesa payment of KSh " . number_format($payment->amount, 2)
                                . " has been received. Receipt: {$receipt}.",
                    sendEmail:  true,
                );
            }
        } else {
            // Payment failed or cancelled by user
            $payment->update(['status' => PaymentStatus::FAILED]);

            Log::info('M-Pesa payment failed/cancelled', [
                'payment_id' => $payment->id,
                'ResultDesc' => $data['ResultDesc'] ?? '',
            ]);
        }

        // Always return 200 with ResultCode 0 so Safaricom stops retrying
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function handleTimeout(Request $request)
    {
        $checkoutRequestId = $request->input('Body.stkCallback.CheckoutRequestID');

        if ($checkoutRequestId) {
            Payment::where('reference', $checkoutRequestId)
                ->where('status', PaymentStatus::PENDING)
                ->update(['status' => PaymentStatus::FAILED]);
        }

        Log::info('M-Pesa timeout', ['CheckoutRequestID' => $checkoutRequestId]);

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
