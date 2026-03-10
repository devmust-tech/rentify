<?php

namespace App\Http\Controllers\Agent;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Mail\PaymentFailed;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillingController extends Controller
{
    /**
     * Pricing table: plan => Stripe Price ID (set in .env)
     */
    private const PLANS = [
        'basic'      => ['label' => 'Basic',      'price' => 'KSh 2,500/mo',  'stripe_price_id' => 'STRIPE_PRICE_BASIC'],
        'pro'        => ['label' => 'Pro',         'price' => 'KSh 6,500/mo',  'stripe_price_id' => 'STRIPE_PRICE_PRO'],
        'enterprise' => ['label' => 'Enterprise',  'price' => 'KSh 15,000/mo', 'stripe_price_id' => 'STRIPE_PRICE_ENTERPRISE'],
    ];

    public function index(Request $request)
    {
        $org = app('currentOrganization');

        // On return from Stripe Checkout, retrieve session and update plan immediately
        // (webhooks can't reach localhost in dev, so this is the primary update path)
        if ($request->query('checkout') === 'success' && $request->query('session_id')) {
            $this->fulfillCheckoutSession($request->query('session_id'), $org);
            $org->refresh();
        }

        $plan = $org->plan ?? 'pro';

        return view('agent.billing.index', [
            'org'         => $org,
            'plan'        => $plan,
            'plans'       => self::PLANS,
            'trialEndsAt' => $org->trial_ends_at,
            'subStatus'   => $org->subscription_status,
        ]);
    }

    /**
     * Create a Stripe Checkout session and redirect to it.
     * Requires: STRIPE_SECRET, STRIPE_PRICE_BASIC/PRO/ENTERPRISE in .env
     */
    public function checkout(Request $request)
    {
        $request->validate(['plan' => 'required|in:basic,pro,enterprise']);

        $stripeKey = config('services.stripe.secret');

        if (!$stripeKey) {
            return back()->with('error', 'Stripe is not configured. Please contact support.');
        }

        $org  = app('currentOrganization');
        $plan = self::PLANS[$request->plan];
        $priceId = config("services.stripe.prices.{$request->plan}");

        if (!$priceId) {
            return back()->with('error', 'Stripe price not configured for this plan.');
        }

        try {
            \Stripe\Stripe::setApiKey($stripeKey);

            // Create or retrieve Stripe customer
            if (!$org->stripe_customer_id) {
                $customer = \Stripe\Customer::create([
                    'name'  => $org->name,
                    'email' => $org->owner?->email,
                    'metadata' => ['organization_id' => $org->id],
                ]);
                $org->update(['stripe_customer_id' => $customer->id]);
            }

            $session = \Stripe\Checkout\Session::create([
                'customer' => $org->stripe_customer_id,
                'mode'     => 'subscription',
                'line_items' => [['price' => $priceId, 'quantity' => 1]],
                'success_url' => route('agent.billing', ['org' => $org->slug]) . '?checkout=success&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('agent.billing', ['org' => $org->slug]) . '?checkout=cancelled',
                'metadata'    => ['organization_id' => $org->id, 'plan' => $request->plan],
            ]);

            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return back()->with('error', 'Payment service error. Please try again.');
        }
    }

    /**
     * Redirect to Stripe Customer Portal for self-service subscription management.
     */
    public function portal()
    {
        $org = app('currentOrganization');

        if (!$org->stripe_customer_id) {
            return back()->with('error', 'No billing account found. Please subscribe to a plan first.');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\BillingPortal\Session::create([
            'customer'   => $org->stripe_customer_id,
            'return_url' => route('agent.billing'),
        ]);

        return redirect($session->url);
    }

    /**
     * Handle Stripe webhook events.
     * Route must be excluded from CSRF verification.
     */
    public function webhook(Request $request)
    {
        $stripeKey    = config('services.stripe.secret');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!$stripeKey) {
            return response('Stripe not configured', 500);
        }

        \Stripe\Stripe::setApiKey($stripeKey);

        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $webhookSecret
                ? \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret)
                : \Stripe\Event::constructFrom(json_decode($payload, true));
        } catch (\Exception $e) {
            Log::warning('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Find org by metadata.organization_id (checkout sessions) or by stripe_customer_id (invoice events)
        $orgId      = $event->data->object->metadata->organization_id ?? null;
        $customerId = $event->data->object->customer ?? null;
        $org = $orgId
            ? Organization::withoutGlobalScopes()->find($orgId)
            : ($customerId ? Organization::withoutGlobalScopes()->where('stripe_customer_id', $customerId)->first() : null);

        match ($event->type) {
            'checkout.session.completed'    => $this->handleCheckoutSessionCompleted($event->data->object, $org),
            'customer.subscription.updated',
            'customer.subscription.deleted' => $org ? $this->handleSubscriptionUpdate($event->data->object, $org) : null,
            'invoice.payment_failed'        => $org ? $this->handlePaymentFailed($event->data->object, $org) : null,
            'invoice.payment_succeeded'     => $org ? $this->handlePaymentSucceeded($event->data->object, $org) : null,
            default                         => null,
        };

        return response('OK');
    }

    /**
     * Retrieve a completed Checkout Session from Stripe and apply the plan change.
     * Called on the success return URL so local dev works without webhooks.
     */
    private function fulfillCheckoutSession(string $sessionId, Organization $org): void
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid' && $session->status !== 'complete') {
                return;
            }

            // Only process subscription checkout sessions for this org
            if ($session->mode === 'subscription' && ($session->metadata->organization_id ?? '') === $org->id) {
                $this->handleCheckoutComplete($session, $org);
            }
        } catch (\Exception $e) {
            Log::warning('Stripe session retrieval failed: ' . $e->getMessage());
        }
    }

    private function handleCheckoutSessionCompleted(object $session, ?Organization $org): void
    {
        if ($session->mode === 'subscription' && $org) {
            $this->handleCheckoutComplete($session, $org);
        } elseif ($session->mode === 'payment' && ($session->metadata->type ?? '') === 'tenant_payment') {
            $this->handleTenantCardPayment($session);
        }
    }

    private function handleCheckoutComplete(object $session, Organization $org): void
    {
        $plan = $session->metadata->plan ?? $org->plan ?? 'pro';

        $org->update([
            'plan'                    => $plan,
            'features'                => Organization::planFeatures($plan),
            'stripe_subscription_id'  => $session->subscription,
            'subscription_status'     => 'active',
            'trial_ends_at'           => null,
        ]);
    }

    private function handleSubscriptionUpdate(object $subscription, Organization $org): void
    {
        $org->update([
            'stripe_subscription_id' => $subscription->id,
            'subscription_status'    => $subscription->status,
        ]);
    }

    private function handlePaymentFailed(object $invoice, Organization $org): void
    {
        $org->update(['subscription_status' => 'past_due']);

        if ($org->owner) {
            Mail::to($org->owner->email)->queue(new PaymentFailed($org));
        }
    }

    private function handlePaymentSucceeded(object $invoice, Organization $org): void
    {
        if ($org->subscription_status === 'past_due') {
            $org->update(['subscription_status' => 'active']);
        }
    }

    private function handleTenantCardPayment(object $session): void
    {
        // Idempotent — return URL handler may have already recorded the payment
        if (Payment::withoutGlobalScopes()->where('reference', $session->payment_intent)->exists()) {
            return;
        }

        $invoiceId = $session->metadata->invoice_id ?? null;
        $invoice   = $invoiceId ? Invoice::withoutGlobalScopes()->find($invoiceId) : null;

        if (!$invoice) {
            Log::warning('Stripe tenant payment webhook: invoice not found', ['metadata' => (array) $session->metadata]);
            return;
        }

        Payment::create([
            'organization_id' => $session->metadata->organization_id,
            'invoice_id'      => $invoice->id,
            'amount'          => $session->amount_total, // KES is zero-decimal on Stripe
            'method'          => PaymentMethod::STRIPE,
            'reference'       => $session->payment_intent,
            'status'          => PaymentStatus::COMPLETED,
            'paid_at'         => now(),
        ]);

        $invoice->updateStatus();
    }
}
