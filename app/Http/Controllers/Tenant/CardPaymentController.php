<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\NotificationType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Mail\PaymentReceived;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CardPaymentController extends Controller
{
    /**
     * Create a Stripe Checkout session for a one-time invoice payment and redirect.
     */
    public function checkout(Request $request, string $org, Invoice $invoice)
    {
        $tenant = $request->user()->tenant;

        if ($invoice->lease->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        if ($invoice->balance <= 0) {
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('info', 'This invoice has already been fully paid.');
        }

        $stripeKey = config('services.stripe.key') ? config('services.stripe.secret') : null;

        if (!$stripeKey) {
            return back()->with('error', 'Card payments are not available at this time.');
        }

        $orgModel    = app('currentOrganization');
        $balance     = $invoice->balance;
        $currency    = strtolower($orgModel->settings['currency'] ?? 'kes');
        $description = $invoice->description ?? 'Rent Payment';

        // KES and most African currencies are zero-decimal on Stripe (no multiplication by 100)
        $zeroDecimalCurrencies = ['bif', 'clp', 'gnf', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'kes'];
        $unitAmount = in_array($currency, $zeroDecimalCurrencies) ? (int) $balance : (int) ($balance * 100);

        try {
            \Stripe\Stripe::setApiKey($stripeKey);

            $session = \Stripe\Checkout\Session::create([
                'mode'       => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency'     => $currency,
                        'product_data' => ['name' => 'Invoice #' . $invoice->invoice_number . ' — ' . $description],
                        'unit_amount'  => $unitAmount,
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => route('tenant.invoices.card.return', ['invoice' => $invoice->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('tenant.invoices.show', ['invoice' => $invoice->id]) . '?checkout=cancelled',
                'metadata'    => [
                    'organization_id' => $orgModel->id,
                    'invoice_id'      => $invoice->id,
                    'user_id'         => $request->user()->id,
                    'type'            => 'tenant_payment',
                ],
            ]);

            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe card payment checkout error: ' . $e->getMessage());
            return back()->with('error', 'Payment service error. Please try again.');
        }
    }

    /**
     * Handle return from Stripe Checkout. Records payment if not already recorded by webhook.
     */
    public function return(Request $request, string $org, Invoice $invoice)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('error', 'Payment verification failed. Please contact support.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe session retrieval error: ' . $e->getMessage());
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('error', 'Could not verify payment. Please contact support.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('error', 'Payment was not completed.');
        }

        // Idempotent — webhook may have already recorded the payment
        if (Payment::where('reference', $session->payment_intent)->exists()) {
            return redirect()->route('tenant.invoices.show', $invoice)
                ->with('success', 'Payment successful! Your invoice has been updated.');
        }

        $this->recordPayment($session, $invoice);

        return redirect()->route('tenant.invoices.show', $invoice)
            ->with('success', 'Payment successful! Your invoice has been updated.');
    }

    private function recordPayment(object $session, Invoice $invoice): void
    {
        $payment = Payment::create([
            'organization_id' => $session->metadata->organization_id,
            'invoice_id'      => $invoice->id,
            'amount'          => $session->amount_total, // zero-decimal: already in full units
            'method'          => PaymentMethod::STRIPE,
            'reference'       => $session->payment_intent,
            'status'          => PaymentStatus::COMPLETED,
            'paid_at'         => now(),
        ]);

        $invoice->updateStatus();

        $payment->load('invoice.lease.tenant.user', 'invoice.lease.unit.property');
        Mail::to($payment->invoice->lease->tenant->user->email)->queue(new PaymentReceived($payment));

        app(NotificationService::class)->notify(
            user: $payment->invoice->lease->tenant->user,
            type: NotificationType::PAYMENT_REMINDER,
            subject: 'Payment Received',
            message: 'Card payment of KSh ' . number_format($payment->amount, 2) . ' received for invoice #' . $invoice->invoice_number . '.',
            sendEmail: false,
        );
    }
}
