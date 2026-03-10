<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Super admin → redirect to admin panel
        if ($user->role === UserRole::ADMIN) {
            return redirect('http://admin.'.config('app.domain').'/dashboard');
        }

        // Inactive user (org pending approval) → show waiting room
        if ($user->status === UserStatus::INACTIVE) {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['email' => 'Your account is pending workspace approval.']);
        }

        // If already on the user's correct org subdomain, redirect as usual
        if (app()->bound('currentOrganization')) {
            $currentOrg = app('currentOrganization');
            if ($currentOrg->id === $user->organization_id) {
                return redirect()->intended(route('dashboard', ['org' => $currentOrg->slug], absolute: false));
            }
        }

        // Redirect to the user's org subdomain
        if ($user->organization_id) {
            $org = Organization::withoutGlobalScopes()->find($user->organization_id);
            if ($org) {
                return redirect($org->subdomainUrl().'/dashboard');
            }
        }

        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login', ['org' => request()->route('org')]);
    }
}
