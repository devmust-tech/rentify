<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    public function start(Request $request, Organization $organization, User $user)
    {
        // Only admin can impersonate
        if (!$request->user() || $request->user()->role->value !== 'admin') {
            abort(403);
        }

        // Store original admin ID so we can restore later
        Session::put('impersonating_original_admin_id', $request->user()->id);
        Session::put('impersonating_organization_slug', $organization->slug);

        Auth::login($user);

        return redirect('http://' . $organization->slug . '.' . config('app.domain') . '/dashboard');
    }

    public function leave(Request $request)
    {
        $adminId = Session::get('impersonating_original_admin_id');
        $orgSlug = Session::get('impersonating_organization_slug');

        if (!$adminId) {
            abort(403, 'Not currently impersonating.');
        }

        $admin = User::withoutGlobalScopes()->find($adminId);

        if (!$admin) {
            abort(404, 'Admin user not found.');
        }

        Session::forget('impersonating_original_admin_id');
        Session::forget('impersonating_organization_slug');

        Auth::login($admin);

        return redirect('http://admin.' . config('app.domain') . '/organizations/' . $orgSlug);
    }
}
