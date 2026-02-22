<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        // Get all payments for landlord's properties
        $payments = Payment::whereHas('invoice.lease.unit.property', function ($query) use ($request) {
            $query->where('landlord_id', $request->user()->landlord->id);
        })
        ->where('status', 'completed')
        ->with(['invoice.lease.tenant.user', 'invoice.lease.unit.property'])
        ->latest('paid_at')
        ->paginate(15);

        // Calculate total income
        $totalIncome = Payment::whereHas('invoice.lease.unit.property', function ($query) use ($request) {
            $query->where('landlord_id', $request->user()->landlord->id);
        })
        ->where('status', 'completed')
        ->sum('amount');

        return view('landlord.financials.index', compact('payments', 'totalIncome'));
    }

    public function statement(Request $request)
    {
        // Get all properties with units, leases, invoices and payments
        $properties = $request->user()->landlord->properties()
            ->with([
                'units.leases.tenant.user',
                'units.leases.invoices.payments' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->get();

        return view('landlord.financials.statement', compact('properties'));
    }
}
