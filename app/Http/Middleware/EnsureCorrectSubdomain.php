<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCorrectSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Admin users don't belong to any org — nothing to check
        if (!$user->organization_id) {
            return $next($request);
        }

        // If no org resolved (not on a subdomain), redirect to the user's org subdomain
        if (!app()->bound('currentOrganization')) {
            $org = \App\Models\Organization::find($user->organization_id);
            if ($org) {
                return redirect($org->subdomainUrl().'/dashboard');
            }
        }

        // If on a wrong subdomain, redirect to the correct org
        $currentOrg = app('currentOrganization');
        if ($currentOrg->id !== $user->organization_id) {
            $userOrg = \App\Models\Organization::find($user->organization_id);
            if ($userOrg) {
                return redirect($userOrg->subdomainUrl().'/dashboard');
            }
        }

        return $next($request);
    }
}
