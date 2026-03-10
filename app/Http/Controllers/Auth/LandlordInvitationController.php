<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class LandlordInvitationController extends Controller
{
    public function show(Request $request, string $org, Landlord $landlord)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This invitation link is invalid or has expired.');
        }

        if ($landlord->user->status === UserStatus::ACTIVE) {
            return redirect()->route('login', ['org' => $request->route('org')])
                ->with('status', 'Your account is already active. Please sign in.');
        }

        return view('auth.landlord-invitation', compact('landlord'));
    }

    public function accept(Request $request, string $org, Landlord $landlord)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This invitation link is invalid or has expired.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $landlord->user->update([
            'password'          => $request->password,
            'status'            => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ]);

        Auth::login($landlord->user);

        return redirect()->route('landlord.dashboard')
            ->with('status', 'Welcome! Your account is now active.');
    }
}
