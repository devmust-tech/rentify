<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class TenantInvitationController extends Controller
{
    /**
     * Show the password setup form for an invited tenant.
     */
    public function show(Request $request, string $org, Tenant $tenant)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This invitation link is invalid or has expired.');
        }

        if ($tenant->user->status === UserStatus::ACTIVE) {
            return redirect()->route('login', ['org' => $request->route('org')])
                ->with('status', 'Your account is already active. Please sign in.');
        }

        return view('auth.tenant-invitation', compact('tenant'));
    }

    /**
     * Activate the account with the tenant's chosen password.
     */
    public function accept(Request $request, string $org, Tenant $tenant)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This invitation link is invalid or has expired.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tenant->user->update([
            'password'          => $request->password, // cast handles hashing
            'status'            => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);

        Auth::login($tenant->user);

        return redirect()->route('tenant.dashboard')
            ->with('status', 'Welcome! Your account is now active.');
    }
}
