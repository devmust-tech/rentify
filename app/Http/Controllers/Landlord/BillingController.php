<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    private const PLANS = [
        'basic'      => ['label' => 'Basic',      'price' => 'KSh 2,500/mo',  'stripe_price_id' => 'STRIPE_PRICE_BASIC'],
        'pro'        => ['label' => 'Pro',         'price' => 'KSh 6,500/mo',  'stripe_price_id' => 'STRIPE_PRICE_PRO'],
        'enterprise' => ['label' => 'Enterprise',  'price' => 'KSh 15,000/mo', 'stripe_price_id' => 'STRIPE_PRICE_ENTERPRISE'],
    ];

    public function index(Request $request)
    {
        $org = app('currentOrganization');

        if ($request->query('checkout') === 'success' && $request->query('session_id')) {
            $this->fulfillCheckoutSession($request->query('session_id'), $org);
            $org->refresh();
        }

        $plan = $org->plan ?? 'pro';

        return view('landlord.billing.index', [
            'org'         => $org,
            'plan'        => $plan,
            'plans'       => self::PLANS,
            'trialEndsAt' => $org->trial_ends_at,
            'subStatus'   => $org->subscription_status,
            'portalRoute' => 'landlord.billing.portal',
            'checkoutRoute' => 'landlord.billing.checkout',
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate(['plan' => 'required|in:basic,pro,enterprise']);

        $stripeKey = config('services.stripe.secret');

        if (!$stripeKey) {
            return back()->with('error', 'Stripe is not configured. Please contact support.');
        }

        $org     = app('currentOrganization');
        $plan    = self::PLANS[$request->plan];
        $priceId = config("services.stripe.prices.{$request->plan}");

        if (!$priceId) {
            return back()->with('error', 'Stripe price not configured for this plan.');
        }

        try {
            \Stripe\Stripe::setApiKey($stripeKey);

            if (!$org->stripe_customer_id) {
                $customer = \Stripe\Customer::create([
                    'name'     => $org->name,
                    'email'    => $org->owner?->email,
                    'metadata' => ['organization_id' => $org->id],
                ]);
                $org->update(['stripe_customer_id' => $customer->id]);
            }

            $session = \Stripe\Checkout\Session::create([
                'customer'   => $org->stripe_customer_id,
                'mode'       => 'subscription',
                'line_items' => [['price' => $priceId, 'quantity' => 1]],
                'success_url' => route('landlord.billing', ['org' => $org->slug]) . '?checkout=success&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('landlord.billing', ['org' => $org->slug]) . '?checkout=cancelled',
                'metadata'    => ['organization_id' => $org->id, 'plan' => $request->plan],
            ]);

            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return back()->with('error', 'Payment service error. Please try again.');
        }
    }

    private function fulfillCheckoutSession(string $sessionId, Organization $org): void
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid' && $session->status !== 'complete') {
                return;
            }

            if ($session->mode === 'subscription' && ($session->metadata->organization_id ?? '') === $org->id) {
                $plan = $session->metadata->plan ?? $org->plan ?? 'pro';
                $org->update([
                    'plan'                   => $plan,
                    'features'               => Organization::planFeatures($plan),
                    'stripe_subscription_id' => $session->subscription,
                    'subscription_status'    => 'active',
                    'trial_ends_at'          => null,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Stripe session retrieval failed: ' . $e->getMessage());
        }
    }

    public function portal()
    {
        $org = app('currentOrganization');

        if (!$org->stripe_customer_id) {
            return back()->with('error', 'No billing account found. Please subscribe to a plan first.');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\BillingPortal\Session::create([
            'customer'   => $org->stripe_customer_id,
            'return_url' => route('landlord.billing'),
        ]);

        return redirect($session->url);
    }
}
