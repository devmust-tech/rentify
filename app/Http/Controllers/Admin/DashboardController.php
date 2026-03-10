<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrganizationStatus;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // All queries here run WITHOUT OrganizationScope (admin has no org bound)
        $totalOrgs   = Organization::withoutGlobalScopes()->count();
        $pendingOrgs = Organization::withoutGlobalScopes()->where('status', OrganizationStatus::PENDING)->count();
        $activeOrgs  = Organization::withoutGlobalScopes()->where('status', OrganizationStatus::ACTIVE)->count();
        $totalUsers  = User::withoutGlobalScopes()->whereNotNull('organization_id')->count();

        $monthlyRevenue = Payment::withoutGlobalScopes()
            ->where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $pendingOrgsList = Organization::withoutGlobalScopes()
            ->where('status', OrganizationStatus::PENDING)
            ->with('owner')
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalOrgs', 'pendingOrgs', 'activeOrgs', 'totalUsers',
            'monthlyRevenue', 'pendingOrgsList'
        ));
    }
}
