<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Unit;

class DashboardController extends Controller
{
    public function index()
    {
        $landlord    = auth()->user()->landlord;
        $propertyIds = $landlord->properties()->pluck('id');

        $unitQuery = Unit::whereIn('property_id', $propertyIds);
        $unitIds   = (clone $unitQuery)->pluck('id');

        $totalUnits   = (clone $unitQuery)->count();
        $occupiedUnits = (clone $unitQuery)->where('status', 'occupied')->count();

        $leaseIds   = Lease::whereIn('unit_id', $unitIds)->pluck('id');
        $invoiceIds = Invoice::whereIn('lease_id', $leaseIds)->pluck('id');

        $totalIncome   = Payment::whereIn('invoice_id', $invoiceIds)->where('status', 'completed')->sum('amount');
        $pendingAmount = Invoice::whereIn('lease_id', $leaseIds)->whereIn('status', ['pending', 'overdue', 'partially_paid'])->sum('amount');

        $stats = [
            'total_properties' => $landlord->properties()->count(),
            'occupied_units'   => $occupiedUnits,
            'total_units'      => $totalUnits,
            'total_income'     => $totalIncome,
            'pending_amount'   => $pendingAmount,
        ];

        $recentPayments = Payment::whereIn('invoice_id', $invoiceIds)
            ->where('status', 'completed')
            ->with('invoice.lease.tenant.user')
            ->latest('paid_at')
            ->take(10)
            ->get();

        // Maintenance alerts — open/in-progress requests for this landlord's units
        $openMaintenance = MaintenanceRequest::whereIn('unit_id', $unitIds)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['unit.property', 'tenant.user'])
            ->latest()
            ->take(5)
            ->get();

        // Leases expiring in the next 60 days
        $expiringLeases = Lease::whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now(), now()->addDays(60)])
            ->with(['unit.property', 'tenant.user'])
            ->orderBy('end_date')
            ->take(5)
            ->get();

        return view('landlord.dashboard', compact(
            'stats', 'recentPayments', 'openMaintenance', 'expiringLeases'
        ));
    }
}
