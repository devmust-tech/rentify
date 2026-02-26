<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\InvoiceStatus;
use App\Enums\NotificationType;
use App\Mail\InvoiceGenerated;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leaseIds = Lease::whereIn('unit_id', $unitIds)->pluck('id');

        $invoices = Invoice::whereIn('lease_id', $leaseIds)
            ->with(['lease.tenant.user', 'lease.unit.property'])
            ->orderByDesc('due_date')
            ->paginate(15);

        return view('agent.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        $leases = Lease::whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->with(['tenant.user', 'unit.property'])
            ->get();

        return view('agent.invoices.create', compact('leases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            ...$validated,
            'status' => InvoiceStatus::PENDING,
        ]);

        $invoice->load('lease.tenant.user', 'lease.unit.property');
        Mail::to($invoice->lease->tenant->user->email)->queue(new InvoiceGenerated($invoice));

        // In-app notification for tenant (sendEmail=false to avoid double email)
        app(NotificationService::class)->notify(
            user: $invoice->lease->tenant->user,
            type: NotificationType::GENERAL,
            subject: 'New Invoice',
            message: 'New invoice of KSh ' . number_format($invoice->amount, 2) . ' due on ' . $invoice->due_date->format('d M Y') . '.',
            sendEmail: false,
        );

        return redirect()->route('agent.invoices.index')->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['lease.tenant.user', 'lease.unit.property', 'payments']);
        return view('agent.invoices.show', compact('invoice'));
    }
}
