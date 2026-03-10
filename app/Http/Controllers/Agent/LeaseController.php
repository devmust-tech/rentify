<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\LeaseStatus;
use App\Enums\NegotiationStatus;
use App\Enums\NotificationType;
use App\Enums\UnitStatus;
use App\Mail\LeaseCreated;
use App\Models\Lease;
use App\Models\Property;
use App\Models\RentNegotiation;
use App\Models\Tenant;
use App\Models\Unit;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeaseController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        $leases = Lease::whereIn('unit_id', $unitIds)
            ->when($request->search, fn($q) => $q->whereHas('tenant.user', fn($u) =>
                $u->where('name', 'like', '%' . $request->search . '%')
            ))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with(['tenant.user', 'unit.property'])
            ->orderByDesc('created_at')
            ->paginate(15)->withQueryString();

        return view('agent.leases.index', compact('leases'));
    }

    public function create(Request $request)
    {
        $tenants = Tenant::with('user')->get();
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $units = Unit::whereIn('property_id', $propertyIds)
            ->where('status', UnitStatus::VACANT)
            ->with('property')
            ->get();

        return view('agent.leases.create', compact('tenants', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'terms' => 'nullable|string',
        ]);

        $lease = Lease::create([
            ...$validated,
            'status' => LeaseStatus::PENDING,
        ]);

        $lease->load('tenant.user', 'unit.property');
        Mail::to($lease->tenant->user->email)->queue(new LeaseCreated($lease));

        // In-app notification for tenant (sendEmail=false to avoid double email)
        app(NotificationService::class)->notify(
            user: $lease->tenant->user,
            type: NotificationType::GENERAL,
            subject: 'New Lease Agreement',
            message: 'A new lease agreement for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . ' is awaiting your signature.',
            sendEmail: false,
        );

        return redirect()->route('agent.leases.index')->with('success', 'Lease created. Awaiting tenant signature.');
    }

    public function show(Request $request, string $org, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $lease->load(['tenant.user', 'unit.property', 'invoices.payments', 'negotiations.proposer']);
        return view('agent.leases.show', compact('lease'));
    }

    public function edit(Request $request, string $org, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $tenants = Tenant::with('user')->get();
        $units = Unit::whereIn('property_id', $propertyIds)->with('property')->get();
        return view('agent.leases.edit', compact('lease', 'tenants', 'units'));
    }

    public function update(Request $request, string $org, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'terms' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $oldStatus = $lease->status->value;
        $lease->update($validated);

        if ($oldStatus !== $validated['status']) {
            app(ActivityLogger::class)->log(
                'lease.status_changed',
                "Lease status changed from {$oldStatus} to {$validated['status']} for {$lease->tenant->user->name}",
                $lease,
                ['old_status' => $oldStatus, 'new_status' => $validated['status']],
            );
        }

        return redirect()->route('agent.leases.show', $lease)->with('success', 'Lease updated.');
    }

    public function destroy(Request $request, string $org, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $lease->unit->update(['status' => UnitStatus::VACANT]);
        $lease->delete();
        return redirect()->route('agent.leases.index')->with('success', 'Lease deleted.');
    }

    public function respondToNegotiation(Request $request, string $org, Lease $lease, RentNegotiation $negotiation)
    {
        $this->authorizeAgentLease($request, $lease);

        // Ensure this negotiation belongs to this lease
        if ($negotiation->lease_id !== $lease->id) {
            abort(404);
        }

        // Can only respond to pending negotiations
        if ($negotiation->status !== NegotiationStatus::PENDING) {
            return back()->with('error', 'This negotiation has already been responded to.');
        }

        $request->validate([
            'action' => 'required|in:accept,reject,counter',
            'counter_rent' => 'required_if:action,counter|nullable|numeric|min:0',
            'message' => 'nullable|string|max:1000',
        ]);

        $action = $request->input('action');
        $lease->load('tenant.user', 'unit.property');

        if ($action === 'accept') {
            $negotiation->update([
                'status' => NegotiationStatus::ACCEPTED,
                'responded_at' => now(),
            ]);

            // Update the lease rent amount to the accepted proposal
            $lease->update([
                'rent_amount' => $negotiation->proposed_rent,
            ]);

            app(NotificationService::class)->notify(
                user: $lease->tenant->user,
                type: NotificationType::GENERAL,
                subject: 'Rent Negotiation Accepted',
                message: 'Your proposed rent of KSh ' . number_format($negotiation->proposed_rent, 2) . ' for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . ' has been accepted.',
                sendEmail: true,
            );

            return back()->with('success', 'Negotiation accepted. Lease rent updated to KSh ' . number_format($negotiation->proposed_rent, 2) . '.');
        }

        if ($action === 'reject') {
            $negotiation->update([
                'status' => NegotiationStatus::REJECTED,
                'responded_at' => now(),
            ]);

            app(NotificationService::class)->notify(
                user: $lease->tenant->user,
                type: NotificationType::GENERAL,
                subject: 'Rent Negotiation Rejected',
                message: 'Your rent proposal for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . ' has been rejected.' . ($request->input('message') ? ' Reason: ' . $request->input('message') : ''),
                sendEmail: true,
            );

            return back()->with('success', 'Negotiation rejected.');
        }

        // Counter-offer
        $negotiation->update([
            'status' => NegotiationStatus::COUNTERED,
            'responded_at' => now(),
        ]);

        // Create a new negotiation entry with the agent's counter-offer
        RentNegotiation::create([
            'lease_id' => $lease->id,
            'proposed_by' => $request->user()->id,
            'proposed_rent' => $request->input('counter_rent'),
            'message' => $request->input('message'),
            'status' => NegotiationStatus::PENDING,
        ]);

        app(NotificationService::class)->notify(
            user: $lease->tenant->user,
            type: NotificationType::GENERAL,
            subject: 'Rent Counter-Offer',
            message: 'The agent has counter-offered KSh ' . number_format($request->input('counter_rent'), 2) . ' for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . '.' . ($request->input('message') ? ' Message: ' . $request->input('message') : ''),
            sendEmail: true,
        );

        return back()->with('success', 'Counter-offer of KSh ' . number_format($request->input('counter_rent'), 2) . ' sent.');
    }

    public function bulkDelete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'string']);

        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds     = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        $count = Lease::whereIn('unit_id', $unitIds)->whereIn('id', $request->ids)->count();
        Lease::whereIn('unit_id', $unitIds)->whereIn('id', $request->ids)->delete();

        return redirect()->route('agent.leases.index')
            ->with('success', "{$count} " . \Illuminate\Support\Str::plural('lease', $count) . " deleted.");
    }

    public function markDepositPaid(Request $request, string $org, Lease $lease): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeAgentLease($request, $lease);

        if ($lease->deposit_paid_at) {
            return back()->with('error', 'Deposit is already marked as paid.');
        }

        $lease->load('tenant.user');
        $lease->update(['deposit_paid_at' => now()]);

        app(ActivityLogger::class)->log(
            'deposit.paid',
            "Deposit of KSh " . number_format((float) $lease->deposit, 2) . " marked as paid for {$lease->tenant->user->name}",
            $lease,
        );

        return back()->with('success', 'Deposit marked as paid.');
    }

    public function markDepositRefunded(Request $request, string $org, Lease $lease): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeAgentLease($request, $lease);

        if (!$lease->deposit_paid_at) {
            return back()->with('error', 'Deposit has not been marked as paid yet.');
        }

        if ($lease->deposit_refunded_at) {
            return back()->with('error', 'Deposit is already marked as refunded.');
        }

        $lease->load('tenant.user');
        $lease->update(['deposit_refunded_at' => now()]);

        app(ActivityLogger::class)->log(
            'deposit.refunded',
            "Deposit of KSh " . number_format((float) $lease->deposit, 2) . " refunded to {$lease->tenant->user->name}",
            $lease,
        );

        return back()->with('success', 'Deposit marked as refunded.');
    }

    private function authorizeAgentLease(Request $request, Lease $lease): void
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        if (!$unitIds->contains($lease->unit_id)) {
            abort(403, 'Unauthorized access to this lease.');
        }
    }
}
