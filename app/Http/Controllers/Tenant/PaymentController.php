<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payments) {}

    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $payments = Payment::whereHas('invoice.lease', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id);
        })
        ->with('invoice')
        ->orderBy('paid_at', 'desc')
        ->paginate(20);

        return view('tenant.payments.index', compact('payments'));
    }

    public function initiate(Request $request, string $org, Invoice $invoice)
    {
        // Ensure the invoice belongs to this tenant
        $tenant = $request->user()->tenant;
        if ($invoice->lease->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        if ($invoice->balance <= 0) {
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('info', 'This invoice has already been fully paid.');
        }

        $request->validate([
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $invoice->balance],
        ]);

        $result = $this->payments->initiateStk(
            invoice: $invoice,
            phone:   $request->input('phone'),
            amount:  (float) $request->input('amount'),
        );

        if ($result['success']) {
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('success', $result['message'])
                ->with('pending_payment_id', $result['payment']->id);
        }

        return redirect()->route('tenant.invoices.show', $invoice)
            ->with('error', $result['message']);
    }

    /**
     * JSON endpoint polled by Alpine.js after an STK push to check payment status.
     */
    public function statusCheck(Request $request, string $org, Payment $payment)
    {
        // Ensure this payment belongs to this tenant
        $tenant = $request->user()->tenant;
        if ($payment->invoice->lease->tenant_id !== $tenant->id) {
            abort(403);
        }

        $payment->refresh();
        if ($payment->status === PaymentStatus::PENDING) {
            $payment = $this->payments->reconcilePendingPayment($payment);
        }

        return response()->json([
            'status'  => $payment->status->value,
            'receipt' => $payment->mpesa_receipt,
            'paid_at' => $payment->paid_at?->format('d/m/Y H:i'),
        ]);
    }
}
