<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $org = app('currentOrganization');

        if (!$org || !$org->hasFeature($feature)) {
            abort(403, 'This feature is not available on your current plan.');
        }

        return $next($request);
    }
}
