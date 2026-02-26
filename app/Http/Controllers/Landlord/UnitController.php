<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    private function authorizeLandlordProperty(Request $request, Property $property): void
    {
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }
    }

    public function index(Request $request, Property $property)
    {
        $this->authorizeLandlordProperty($request, $property);
        $units = $property->units()->with('activeLease.tenant.user')->paginate(15);
        return view('landlord.units.index', compact('property', 'units'));
    }

    public function create(Request $request, Property $property)
    {
        $this->authorizeLandlordProperty($request, $property);
        return view('landlord.units.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $this->authorizeLandlordProperty($request, $property);

        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:50',
        ]);

        $property->units()->create([
            ...$validated,
            'status' => 'vacant',
        ]);

        return redirect()->route('landlord.properties.show', $property)->with('success', 'Unit added.');
    }

    public function show(Request $request, Property $property, Unit $unit)
    {
        $this->authorizeLandlordProperty($request, $property);
        $unit->load(['activeLease.tenant.user', 'maintenanceRequests']);
        return view('landlord.units.show', compact('property', 'unit'));
    }

    public function edit(Request $request, Property $property, Unit $unit)
    {
        $this->authorizeLandlordProperty($request, $property);
        return view('landlord.units.edit', compact('property', 'unit'));
    }

    public function update(Request $request, Property $property, Unit $unit)
    {
        $this->authorizeLandlordProperty($request, $property);

        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'status' => 'required|string',
        ]);

        $unit->update($validated);

        return redirect()->route('landlord.properties.show', $property)->with('success', 'Unit updated.');
    }

    public function destroy(Request $request, Property $property, Unit $unit)
    {
        $this->authorizeLandlordProperty($request, $property);
        $unit->delete();
        return redirect()->route('landlord.properties.show', $property)->with('success', 'Unit deleted.');
    }
}
