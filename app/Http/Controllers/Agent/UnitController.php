<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(string $org, Property $property)
    {
        $units = $property->units()->with('activeLease.tenant.user')->paginate(15);
        return view('agent.units.index', compact('property', 'units'));
    }

    public function create(string $org, Property $property)
    {
        return view('agent.units.create', compact('property'));
    }

    public function store(Request $request, string $org, Property $property)
    {
        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'floor_number' => 'nullable|integer|min:0|max:200',
            'size_sqm' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'balcony' => 'nullable|boolean',
            'furnishing' => 'nullable|string|in:unfurnished,semi_furnished,furnished',
            'service_charge' => 'nullable|numeric|min:0',
            'deposit_months' => 'nullable|integer|min:1|max:12',
            'billing_cycle' => 'nullable|string|in:monthly,quarterly',
            'available_from' => 'nullable|date',
            'min_lease_months' => 'nullable|integer|min:1|max:60',
            'meter_type' => 'nullable|string|in:shared,individual',
            'electricity_meter' => 'nullable|string|max:100',
            'water_meter' => 'nullable|string|max:100',
            'video_tour_url' => 'nullable|url|max:500',
        ]);

        $validated['balcony'] = $request->boolean('balcony');

        $property->units()->create([
            ...$validated,
            'status' => 'vacant',
        ]);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Unit added.');
    }

    public function show(string $org, Property $property, Unit $unit)
    {
        $unit->load(['activeLease.tenant.user', 'maintenanceRequests']);
        return view('agent.units.show', compact('property', 'unit'));
    }

    public function edit(string $org, Property $property, Unit $unit)
    {
        return view('agent.units.edit', compact('property', 'unit'));
    }

    public function update(Request $request, string $org, Property $property, Unit $unit)
    {
        $validated = $request->validate([
            'unit_number' => 'required|string|max:50',
            'rent_amount' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'status' => 'required|string',
            'floor_number' => 'nullable|integer|min:0|max:200',
            'size_sqm' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'balcony' => 'nullable|boolean',
            'furnishing' => 'nullable|string|in:unfurnished,semi_furnished,furnished',
            'service_charge' => 'nullable|numeric|min:0',
            'deposit_months' => 'nullable|integer|min:1|max:12',
            'billing_cycle' => 'nullable|string|in:monthly,quarterly',
            'available_from' => 'nullable|date',
            'min_lease_months' => 'nullable|integer|min:1|max:60',
            'meter_type' => 'nullable|string|in:shared,individual',
            'electricity_meter' => 'nullable|string|max:100',
            'water_meter' => 'nullable|string|max:100',
            'video_tour_url' => 'nullable|url|max:500',
        ]);

        $validated['balcony'] = $request->boolean('balcony');

        $unit->update($validated);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Unit updated.');
    }

    public function destroy(string $org, Property $property, Unit $unit)
    {
        $unit->delete();
        return redirect()->route('agent.properties.show', $property)->with('success', 'Unit deleted.');
    }
}
