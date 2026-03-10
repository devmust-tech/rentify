<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ResolveOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $host   = $request->getHost();
        $domain = config('app.domain', 'localhost');

        // Strip the base domain to get the subdomain
        $sub = rtrim(str_replace('.'.$domain, '', $host), '.');

        // Skip admin panel and bare domain (no subdomain or same as base)
        if ($sub === $domain || $sub === 'admin' || $sub === '' || $sub === $host) {
            return $next($request);
        }

        $org = Organization::where('slug', $sub)->first();

        if (!$org) {
            abort(404, 'Organization not found.');
        }

        app()->instance('currentOrganization', $org);
        view()->share('currentOrganization', $org);
        URL::defaults(['org' => $org->slug]);

        return $next($request);
    }
}
