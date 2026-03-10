<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            // On the admin subdomain, redirect to admin login instead of 403
            $host = $request->getHost();
            $adminHost = 'admin.' . config('app.domain');
            if ($host === $adminHost) {
                return redirect()->route('admin.login');
            }

            return redirect()->route('login', ['org' => $request->route('org')]);
        }

        $userRole = Auth::user()->role->value;

        if (!in_array($userRole, $roles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
