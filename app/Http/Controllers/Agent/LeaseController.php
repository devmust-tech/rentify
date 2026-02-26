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
            ->with(['tenant.user', 'unit.property'])
            ->orderByDesc('created_at')
            ->paginate(15);

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

    public function show(Request $request, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $lease->load(['tenant.user', 'unit.property', 'invoices.payments', 'negotiations.proposer']);
        return view('agent.leases.show', compact('lease'));
    }

    public function edit(Request $request, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $tenants = Tenant::with('user')->get();
        $units = Unit::whereIn('property_id', $propertyIds)->with('property')->get();
        return view('agent.leases.edit', compact('lease', 'tenants', 'units'));
    }

    public function update(Request $request, Lease $lease)
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

        $lease->update($validated);

        return redirect()->route('agent.leases.show', $lease)->with('success', 'Lease updated.');
    }

    public function destroy(Request $request, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $lease->unit->update(['status' => UnitStatus::VACANT]);
        $lease->delete();
        return redirect()->route('agent.leases.index')->with('success', 'Lease deleted.');
    }

    public function respondToNegotiation(Request $request, Lease $lease, RentNegotiation $negotiation)
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

    private function authorizeAgentLease(Request $request, Lease $lease): void
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        if (!$unitIds->contains($lease->unit_id)) {
            abort(403, 'Unauthorized access to this lease.');
        }
    }
}
