<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        return match ($request->user()->role) {
            UserRole::AGENT => redirect()->route('agent.dashboard'),
            UserRole::LANDLORD => redirect()->route('landlord.dashboard'),
            UserRole::TENANT => redirect()->route('tenant.dashboard'),
            default => redirect()->route('agent.dashboard'),
        };
    }
}
