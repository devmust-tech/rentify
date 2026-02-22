<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\MaintenanceRequest;
use App\Enums\LeaseStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        // Get active lease
        $activeLease = $tenant ? Lease::where('tenant_id', $tenant->id)
            ->where('status', LeaseStatus::ACTIVE)
            ->with(['unit.property'])
            ->first() : null;

        // Get stats if there's an active lease
        $stats = [];
        $recentPayments = collect();

        if ($activeLease) {
            $stats = [
                'rent_amount' => $activeLease->rent_amount,
                'next_invoice' => Invoice::where('lease_id', $activeLease->id)
                    ->whereIn('status', ['pending', 'overdue'])
                    ->orderBy('due_date')
                    ->first(),
                'total_paid' => Payment::whereHas('invoice', function($q) use ($activeLease) {
                    $q->where('lease_id', $activeLease->id);
                })->where('status', 'completed')->sum('amount'),
                'pending_maintenance' => MaintenanceRequest::where('unit_id', $activeLease->unit_id)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->count(),
            ];

            $recentPayments = Payment::whereHas('invoice', function($q) use ($activeLease) {
                $q->where('lease_id', $activeLease->id);
            })
            ->with('invoice')
            ->where('status', 'completed')
            ->orderBy('paid_at', 'desc')
            ->limit(5)
            ->get();
        }

        return view('tenant.dashboard', compact('activeLease', 'stats', 'recentPayments'));
    }
}
