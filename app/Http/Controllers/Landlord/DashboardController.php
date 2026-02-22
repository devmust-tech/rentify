<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $landlord = auth()->user()->landlord;
        $propertyIds = $landlord->properties()->pluck('id');

        $unitQuery = \App\Models\Unit::whereIn('property_id', $propertyIds);
        $totalUnits = (clone $unitQuery)->count();
        $occupiedUnits = (clone $unitQuery)->where('status', 'occupied')->count();

        // Get lease IDs for this landlord's units
        $unitIds = (clone $unitQuery)->pluck('id');
        $leaseIds = \App\Models\Lease::whereIn('unit_id', $unitIds)->pluck('id');
        $invoiceIds = \App\Models\Invoice::whereIn('lease_id', $leaseIds)->pluck('id');

        $totalIncome = Payment::whereIn('invoice_id', $invoiceIds)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingAmount = \App\Models\Invoice::whereIn('lease_id', $leaseIds)
            ->where('status', 'pending')
            ->sum('amount');

        $stats = [
            'total_properties' => $landlord->properties()->count(),
            'occupied_units' => $occupiedUnits,
            'total_units' => $totalUnits,
            'total_income' => $totalIncome,
            'pending_amount' => $pendingAmount,
        ];

        $recentPayments = Payment::whereIn('invoice_id', $invoiceIds)
            ->where('status', 'completed')
            ->with('invoice.lease.tenant.user')
            ->latest('paid_at')
            ->take(10)
            ->get();

        return view('landlord.dashboard', compact('stats', 'recentPayments'));
    }
}
