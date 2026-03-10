<?php

namespace App\Http\Middleware;

use App\Enums\OrganizationStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!app()->bound('currentOrganization')) {
            return $next($request);
        }

        $org = app('currentOrganization');

        if ($org->status === OrganizationStatus::SUSPENDED) {
            Auth::logout();
            abort(403, 'This workspace has been suspended. Please contact support.');
        }

        if ($org->status === OrganizationStatus::PENDING) {
            $allowedPaths = ['pending-approval', 'login', 'logout'];
            $currentPath  = ltrim($request->path(), '/');

            foreach ($allowedPaths as $allowed) {
                if ($currentPath === $allowed || str_starts_with($currentPath, $allowed)) {
                    return $next($request);
                }
            }

            return redirect()->route('pending-approval');
        }

        return $next($request);
    }
}
