<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::where('agent_id', $request->user()->id)
            ->with(['landlord.user', 'units'])
            ->paginate(15);

        return view('agent.properties.index', compact('properties'));
    }

    public function create()
    {
        $landlords = Landlord::with('user')->get();
        return view('agent.properties.create', compact('landlords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'landlord_id' => 'required|exists:landlords,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'county' => 'required|string',
            'property_type' => 'required|string',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('properties', 'public');
            }
        }

        Property::create([
            ...$validated,
            'agent_id' => $request->user()->id,
            'photos' => $photos,
        ]);

        return redirect()->route('agent.properties.index')->with('success', 'Property created.');
    }

    public function show(Property $property)
    {
        $property->load(['landlord.user', 'units.activeLease.tenant.user']);
        return view('agent.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $landlords = Landlord::with('user')->get();
        return view('agent.properties.edit', compact('property', 'landlords'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'landlord_id' => 'required|exists:landlords,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'county' => 'required|string',
            'property_type' => 'required|string',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('properties', 'public');
            }
            $validated['photos'] = $photos;
        }

        $property->update($validated);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Property updated.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('agent.properties.index')->with('success', 'Property deleted.');
    }
}
