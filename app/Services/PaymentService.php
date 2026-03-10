<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentService
{
    public function __construct(private MpesaService $mpesa) {}

    /**
     * Create a pending Payment record and fire an STK push.
     *
     * @return array ['success' => bool, 'message' => string, 'payment' => Payment|null]
     */
    public function initiateStk(Invoice $invoice, string $phone, float $amount): array
    {
        $formattedPhone = $this->mpesa->formatPhone($phone);

        return DB::transaction(function () use ($invoice, $formattedPhone, $amount) {
            // Create a PENDING payment record first
            $payment = Payment::create([
                'organization_id' => $invoice->organization_id,
                'invoice_id'      => $invoice->id,
                'amount'          => $amount,
                'method'          => PaymentMethod::MPESA,
                'status'          => PaymentStatus::PENDING,
            ]);

            try {
                $response = $this->mpesa->stkPush(
                    phone:       $formattedPhone,
                    amount:      (int) ceil($amount),
                    accountRef:  'Rentify-' . substr($invoice->id, -6),
                    description: 'Rent payment'
                );

                // Store CheckoutRequestID so the callback can find this payment
                $payment->update(['reference' => $response['CheckoutRequestID']]);

                return [
                    'success' => true,
                    'message' => 'STK push sent. Check your phone and enter your M-Pesa PIN.',
                    'payment' => $payment,
                ];
            } catch (\RuntimeException $e) {
                // Delete the pending record if STK push failed to initiate
                $payment->delete();
                Log::error('STK push failed', ['invoice' => $invoice->id, 'error' => $e->getMessage()]);

                return [
                    'success' => false,
                    'message' => 'Could not initiate M-Pesa payment. Please try again.',
                    'payment' => null,
                ];
            }
        });
    }

    /**
     * Reconcile a pending M-Pesa payment by querying Daraja directly.
     * Useful when callback delivery is delayed or unavailable.
     */
    public function reconcilePendingPayment(Payment $payment): Payment
    {
        if ($payment->status !== PaymentStatus::PENDING || empty($payment->reference)) {
            return $payment;
        }

        try {
            $result = $this->mpesa->stkQuery($payment->reference);
        } catch (Throwable $e) {
            Log::warning('STK query reconciliation failed', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'error' => $e->getMessage(),
            ]);
            return $payment;
        }

        $resultCode = (int) ($result['ResultCode'] ?? -1);

        if ($resultCode === 0) {
            // Success: store receipt when available and mark paid.
            $receipt = $result['MpesaReceiptNumber'] ?? $payment->mpesa_receipt;

            $payment->update([
                'status' => PaymentStatus::COMPLETED,
                'paid_at' => $payment->paid_at ?? now(),
                'mpesa_receipt' => $receipt,
            ]);

            $payment->invoice->updateStatus();
        } elseif (in_array($resultCode, [1, 1032, 1037, 2001], true)) {
            // Known non-success terminal outcomes (insufficient/cancelled/timeout/invalid details).
            $payment->update(['status' => PaymentStatus::FAILED]);
        }

        return $payment->fresh();
    }
}
