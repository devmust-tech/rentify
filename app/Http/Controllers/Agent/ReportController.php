<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Landlord;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $agentId = $request->user()->id;
        $propertyIds = Property::where('agent_id', $agentId)->pluck('id');
        $totalUnits = Unit::whereIn('property_id', $propertyIds)->count();
        $occupiedUnits = Unit::whereIn('property_id', $propertyIds)->where('status', 'occupied')->count();
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;

        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');
        $totalCollected = Payment::whereIn('invoice_id', Invoice::whereIn('lease_id', $leaseIds)->pluck('id'))
            ->where('status', 'completed')->sum('amount');
        $totalArrears = Invoice::whereIn('lease_id', $leaseIds)
            ->whereIn('status', ['pending', 'overdue', 'partially_paid'])->sum('amount');

        $landlords = Landlord::whereHas('properties', fn($q) => $q->where('agent_id', $agentId))
            ->with('user')->get();

        return view('agent.reports.index', compact(
            'totalUnits', 'occupiedUnits', 'occupancyRate',
            'totalCollected', 'totalArrears', 'landlords'
        ));
    }

    public function rentRoll(Request $request)
    {
        $agentId = $request->user()->id;
        $properties = Property::where('agent_id', $agentId)
            ->with(['units.activeLease.tenant.user'])
            ->get();

        $totalRent = 0;
        $occupiedRent = 0;
        foreach ($properties as $property) {
            foreach ($property->units as $unit) {
                $totalRent += $unit->rent_amount;
                if ($unit->activeLease) {
                    $occupiedRent += $unit->rent_amount;
                }
            }
        }

        return view('agent.reports.rent-roll', compact('properties', 'totalRent', 'occupiedRent'));
    }

    public function arrears(Request $request)
    {
        $agentId = $request->user()->id;
        $propertyIds = Property::where('agent_id', $agentId)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');

        $overdueInvoices = Invoice::whereIn('lease_id', $leaseIds)
            ->whereIn('status', ['pending', 'overdue', 'partially_paid'])
            ->with(['lease.tenant.user', 'lease.unit.property', 'payments'])
            ->orderBy('due_date')
            ->get();

        $totalArrears = $overdueInvoices->sum(fn($inv) => $inv->amount - $inv->payments->where('status.value', 'completed')->sum('amount'));

        return view('agent.reports.arrears', compact('overdueInvoices', 'totalArrears'));
    }

    public function occupancy(Request $request)
    {
        $agentId = $request->user()->id;
        $properties = Property::where('agent_id', $agentId)
            ->with('units')
            ->get();

        $summary = $properties->map(function ($property) {
            $total = $property->units->count();
            $occupied = $property->units->where('status.value', 'occupied')->count();
            $vacant = $total - $occupied;
            $rate = $total > 0 ? round(($occupied / $total) * 100, 1) : 0;
            return (object) [
                'property' => $property,
                'total' => $total,
                'occupied' => $occupied,
                'vacant' => $vacant,
                'rate' => $rate,
            ];
        });

        $overallTotal = $summary->sum('total');
        $overallOccupied = $summary->sum('occupied');
        $overallRate = $overallTotal > 0 ? round(($overallOccupied / $overallTotal) * 100, 1) : 0;

        return view('agent.reports.occupancy', compact('summary', 'overallTotal', 'overallOccupied', 'overallRate'));
    }

    public function landlordStatement(Request $request, Landlord $landlord)
    {
        $landlord->load(['user', 'properties.units.leases.invoices.payments']);

        $totalIncome = 0;
        $totalPending = 0;
        $properties = [];

        foreach ($landlord->properties as $property) {
            $propIncome = 0;
            $propPending = 0;
            foreach ($property->units as $unit) {
                foreach ($unit->leases as $lease) {
                    foreach ($lease->invoices as $invoice) {
                        $paid = $invoice->payments->where('status.value', 'completed')->sum('amount');
                        $propIncome += $paid;
                        if (in_array($invoice->status->value, ['pending', 'overdue', 'partially_paid'])) {
                            $propPending += $invoice->amount - $paid;
                        }
                    }
                }
            }
            $totalIncome += $propIncome;
            $totalPending += $propPending;
            $properties[] = (object) [
                'name' => $property->name,
                'units' => $property->units->count(),
                'occupied' => $property->units->where('status.value', 'occupied')->count(),
                'income' => $propIncome,
                'pending' => $propPending,
            ];
        }

        return view('agent.reports.landlord-statement', compact('landlord', 'properties', 'totalIncome', 'totalPending'));
    }
}
