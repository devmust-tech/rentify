<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip enforcement when Stripe is not configured (dev/test environments)
        if (!config('services.stripe.secret')) {
            return $next($request);
        }

        if (!app()->bound('currentOrganization')) {
            return $next($request);
        }

        $org = app('currentOrganization');

        // Tenants are always allowed through — they shouldn't be blocked for org billing lapses
        if (auth()->check() && auth()->user()->role === UserRole::TENANT) {
            return $next($request);
        }

        // Always allow billing and pending-approval routes
        if ($request->routeIs('*.billing*') || $request->routeIs('pending-approval')) {
            return $next($request);
        }

        // Active subscription or active trial → allow
        if ($org->subscriptionIsActive()) {
            return $next($request);
        }

        // Past-due → allow with grace period warning flash
        if ($org->subscription_status === 'past_due') {
            session()->flash('subscription_warning', 'Your subscription payment is overdue. Please update your payment method to avoid service interruption.');
            return $next($request);
        }

        // Trial expired / subscription canceled / no subscription → redirect to billing
        $billingRoute = auth()->check() && auth()->user()->isAgent() ? 'agent.billing' : 'landlord.billing';
        return redirect()->route($billingRoute)
            ->with('warning', 'Your trial has ended. Subscribe to a plan to continue.');
    }
}
