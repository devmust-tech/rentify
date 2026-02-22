<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Enums\LeaseStatus;
use App\Enums\UnitStatus;
use App\Mail\LeaseCreated;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
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
            'end_date' => 'required|date|after:start_date',
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

        return redirect()->route('agent.leases.index')->with('success', 'Lease created. Awaiting tenant signature.');
    }

    public function show(Request $request, Lease $lease)
    {
        $this->authorizeAgentLease($request, $lease);
        $lease->load(['tenant.user', 'unit.property', 'invoices.payments']);
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
            'end_date' => 'required|date|after:start_date',
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

    private function authorizeAgentLease(Request $request, Lease $lease): void
    {
        $propertyIds = Property::where('agent_id', $request->user()->id)->pluck('id');
        $unitIds = Unit::whereIn('property_id', $propertyIds)->pluck('id');

        if (!$unitIds->contains($lease->unit_id)) {
            abort(403, 'Unauthorized access to this lease.');
        }
    }
}
