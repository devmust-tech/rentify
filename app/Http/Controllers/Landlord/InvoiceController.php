<?php

namespace App\Http\Controllers\Landlord;

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
    private function getLandlordLeaseIds(Request $request)
    {
        $propertyIds = Property::where('landlord_id', $request->user()->landlord->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');
        return Lease::whereIn('unit_id', $unitIds)->pluck('id');
    }

    public function index(Request $request)
    {
        $leaseIds = $this->getLandlordLeaseIds($request);

        $invoices = Invoice::whereIn('lease_id', $leaseIds)
            ->with(['lease.tenant.user', 'lease.unit.property', 'payments'])
            ->orderByDesc('due_date')
            ->paginate(15);

        return view('landlord.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $leaseIds = $this->getLandlordLeaseIds($request);
        $leases = Lease::whereIn('id', $leaseIds)
            ->where('status', 'active')
            ->with(['tenant.user', 'unit.property'])
            ->get();

        return view('landlord.invoices.create', compact('leases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Verify lease belongs to landlord's property
        $leaseIds = $this->getLandlordLeaseIds($request);
        if (!$leaseIds->contains($validated['lease_id'])) {
            abort(403, 'Unauthorized access to this lease.');
        }

        $invoice = Invoice::create([
            ...$validated,
            'status' => InvoiceStatus::PENDING,
        ]);

        $invoice->load('lease.tenant.user', 'lease.unit.property');
        Mail::to($invoice->lease->tenant->user->email)->queue(new InvoiceGenerated($invoice));

        app(NotificationService::class)->notify(
            user: $invoice->lease->tenant->user,
            type: NotificationType::GENERAL,
            subject: 'New Invoice',
            message: 'New invoice of KSh ' . number_format($invoice->amount, 2) . ' due on ' . $invoice->due_date->format('d M Y') . '.',
            sendEmail: false,
        );

        return redirect()->route('landlord.invoices.index')->with('success', 'Invoice created.');
    }

    public function show(Request $request, Invoice $invoice)
    {
        $leaseIds = $this->getLandlordLeaseIds($request);
        if (!$leaseIds->contains($invoice->lease_id)) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $invoice->load(['lease.tenant.user', 'lease.unit.property', 'payments']);
        return view('landlord.invoices.show', compact('invoice'));
    }
}
