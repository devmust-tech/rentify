<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Enums\LeaseStatus;
use App\Enums\NotificationType;
use App\Enums\UnitStatus;
use App\Mail\LeaseCreated;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeaseController extends Controller
{
    private function getLandlordId(Request $request): string
    {
        return $request->user()->landlord->id;
    }

    private function getLandlordPropertyIds(Request $request)
    {
        return Property::where('landlord_id', $this->getLandlordId($request))->pluck('id');
    }

    private function getLandlordUnitIds(Request $request)
    {
        return Unit::whereIn('property_id', $this->getLandlordPropertyIds($request))->pluck('id');
    }

    private function authorizeLandlordLease(Request $request, Lease $lease): void
    {
        $unitIds = $this->getLandlordUnitIds($request);
        if (!$unitIds->contains($lease->unit_id)) {
            abort(403, 'Unauthorized access to this lease.');
        }
    }

    public function index(Request $request)
    {
        $unitIds = $this->getLandlordUnitIds($request);

        $leases = Lease::whereIn('unit_id', $unitIds)
            ->with(['tenant.user', 'unit.property'])
            ->latest()
            ->paginate(15);

        return view('landlord.leases.index', compact('leases'));
    }

    public function create(Request $request)
    {
        $tenants = Tenant::with('user')->get();
        $propertyIds = $this->getLandlordPropertyIds($request);
        $units = Unit::whereIn('property_id', $propertyIds)
            ->where('status', UnitStatus::VACANT)
            ->with('property')
            ->get();

        return view('landlord.leases.create', compact('tenants', 'units'));
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

        // Ensure the unit belongs to landlord's property
        $unitIds = $this->getLandlordUnitIds($request);
        if (!$unitIds->contains($validated['unit_id'])) {
            abort(403, 'Unauthorized access to this unit.');
        }

        $lease = Lease::create([
            ...$validated,
            'status' => LeaseStatus::PENDING,
        ]);

        $lease->load('tenant.user', 'unit.property');
        Mail::to($lease->tenant->user->email)->queue(new LeaseCreated($lease));

        app(NotificationService::class)->notify(
            user: $lease->tenant->user,
            type: NotificationType::GENERAL,
            subject: 'New Lease Agreement',
            message: 'A new lease agreement for ' . $lease->unit->property->name . ' - Unit ' . $lease->unit->unit_number . ' is awaiting your signature.',
            sendEmail: false,
        );

        return redirect()->route('landlord.leases.index')->with('success', 'Lease created. Awaiting tenant signature.');
    }

    public function show(Request $request, Lease $lease)
    {
        $this->authorizeLandlordLease($request, $lease);
        $lease->load(['tenant.user', 'unit.property', 'invoices.payments']);
        return view('landlord.leases.show', compact('lease'));
    }

    public function edit(Request $request, Lease $lease)
    {
        $this->authorizeLandlordLease($request, $lease);
        $propertyIds = $this->getLandlordPropertyIds($request);
        $tenants = Tenant::with('user')->get();
        $units = Unit::whereIn('property_id', $propertyIds)->with('property')->get();
        return view('landlord.leases.edit', compact('lease', 'tenants', 'units'));
    }

    public function update(Request $request, Lease $lease)
    {
        $this->authorizeLandlordLease($request, $lease);

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'terms' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $lease->update($validated);

        return redirect()->route('landlord.leases.show', $lease)->with('success', 'Lease updated.');
    }

    public function destroy(Request $request, Lease $lease)
    {
        $this->authorizeLandlordLease($request, $lease);
        $lease->unit->update(['status' => UnitStatus::VACANT]);
        $lease->delete();
        return redirect()->route('landlord.leases.index')->with('success', 'Lease deleted.');
    }

    public function approve(Request $request, Lease $lease)
    {
        // Ensure the lease belongs to a unit in landlord's property
        if ($lease->unit->property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this lease.');
        }

        // Require tenant signature before approval
        if (!$lease->signed_at || !$lease->signature_url) {
            return redirect()->route('landlord.leases.show', $lease)
                ->with('error', 'Cannot approve lease -- tenant has not signed yet.');
        }

        // Update lease status to active and unit to occupied
        $lease->update([
            'status' => 'active',
        ]);

        $lease->unit->update([
            'status' => 'occupied',
        ]);

        return redirect()->route('landlord.leases.show', $lease)
            ->with('success', 'Lease approved successfully.');
    }
}
