<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Property $property)
    {
        $units = $property->units()->with('activeLease.tenant.user')->paginate(15);
        return view('agent.units.index', compact('property', 'units'));
    }

    public function create(Property $property)
    {
        return view('agent.units.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
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

        return redirect()->route('agent.properties.show', $property)->with('success', 'Unit added.');
    }

    public function show(Property $property, Unit $unit)
    {
        $unit->load(['activeLease.tenant.user', 'maintenanceRequests']);
        return view('agent.units.show', compact('property', 'unit'));
    }

    public function edit(Property $property, Unit $unit)
    {
        return view('agent.units.edit', compact('property', 'unit'));
    }

    public function update(Request $request, Property $property, Unit $unit)
    {
        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'status' => 'required|string',
        ]);

        $unit->update($validated);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Unit updated.');
    }

    public function destroy(Property $property, Unit $unit)
    {
        $unit->delete();
        return redirect()->route('agent.properties.show', $property)->with('success', 'Unit deleted.');
    }
}
