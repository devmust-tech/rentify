<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\UnitStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $agentId = $request->user()->id;

        // Get properties managed by this agent
        $properties = Property::where('agent_id', $agentId)->get();
        $propertyIds = $properties->pluck('id');

        // Get all units from agent's properties
        $units = Unit::whereIn('property_id', $propertyIds)->get();
        $unitIds = $units->pluck('id');

        // Get all active leases from these units
        $activeLeaseIds = DB::table('leases')
            ->whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->pluck('id');

        // Calculate stats
        $stats = [
            'total_properties' => $properties->count(),
            'total_units' => $units->count(),
            'occupied_units' => $units->where('status', UnitStatus::OCCUPIED)->count(),
            'total_collected' => Payment::whereIn('invoice_id', function ($query) use ($activeLeaseIds) {
                $query->select('id')
                    ->from('invoices')
                    ->whereIn('lease_id', $activeLeaseIds);
            })->where('status', 'completed')->sum('amount'),
            'pending_invoices' => Invoice::whereIn('lease_id', $activeLeaseIds)
                ->where('status', InvoiceStatus::PENDING)
                ->count(),
        ];

        // Recent payments
        $recentPayments = Payment::whereIn('invoice_id', function ($query) use ($activeLeaseIds) {
            $query->select('id')
                ->from('invoices')
                ->whereIn('lease_id', $activeLeaseIds);
        })
            ->with(['invoice.lease.tenant.user'])
            ->where('status', 'completed')
            ->orderBy('paid_at', 'desc')
            ->limit(5)
            ->get();

        // Overdue invoices
        $overdueInvoices = Invoice::whereIn('lease_id', $activeLeaseIds)
            ->where('status', InvoiceStatus::OVERDUE)
            ->with(['lease.tenant.user'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        return view('agent.dashboard', compact('stats', 'recentPayments', 'overdueInvoices'));
    }
}
