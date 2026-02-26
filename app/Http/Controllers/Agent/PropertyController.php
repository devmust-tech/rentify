<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
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
        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('agent.properties.create', compact('landlords', 'amenities'));
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
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'image|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'amenity_data' => 'nullable|array',
            'amenity_data.*.included_in_rent' => 'nullable|boolean',
            'amenity_data.*.provider' => 'nullable|string|max:255',
            'amenity_data.*.monthly_cost' => 'nullable|numeric|min:0',
            'amenity_data.*.notes' => 'nullable|string|max:1000',
        ]);

        $photos = [];
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('properties', 'public');
        }

        $property = Property::create([
            ...$validated,
            'agent_id' => $request->user()->id,
            'photos' => $photos,
        ]);

        // Sync amenities with pivot data
        if ($request->has('amenities')) {
            $amenityData = [];
            foreach ($request->input('amenities', []) as $amenityId) {
                $data = $request->input("amenity_data.{$amenityId}", []);
                $amenityData[$amenityId] = [
                    'included_in_rent' => $data['included_in_rent'] ?? true,
                    'provider' => $data['provider'] ?? null,
                    'monthly_cost' => $data['monthly_cost'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ];
            }
            $property->amenities()->sync($amenityData);
        }

        return redirect()->route('agent.properties.index')->with('success', 'Property created.');
    }

    public function show(Property $property)
    {
        $property->load(['landlord.user', 'units.activeLease.tenant.user', 'amenities']);
        return view('agent.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $landlords = Landlord::with('user')->get();
        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $property->load('amenities');
        return view('agent.properties.edit', compact('property', 'landlords', 'amenities'));
    }

    public function removePhoto(Request $request, Property $property)
    {
        $request->validate(['photo_index' => 'required|integer|min:0']);
        $photos = $property->photos ?? [];
        $index = $request->input('photo_index');

        if (isset($photos[$index])) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photos[$index]);
            array_splice($photos, $index, 1);
            $property->update(['photos' => $photos]);
        }

        return back()->with('success', 'Photo removed.');
    }

    public function update(Request $request, Property $property)
    {
        $existingPhotos = $property->photos ?? [];
        $newPhotoCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
        $totalPhotos = count($existingPhotos) + $newPhotoCount;

        $validated = $request->validate([
            'landlord_id' => 'required|exists:landlords,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'county' => 'required|string',
            'property_type' => 'required|string',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'amenity_data' => 'nullable|array',
            'amenity_data.*.included_in_rent' => 'nullable|boolean',
            'amenity_data.*.provider' => 'nullable|string|max:255',
            'amenity_data.*.monthly_cost' => 'nullable|numeric|min:0',
            'amenity_data.*.notes' => 'nullable|string|max:1000',
        ]);

        if ($totalPhotos > 10) {
            return back()->withErrors(['photos' => 'Maximum 10 photos allowed. You have ' . count($existingPhotos) . ' existing.'])->withInput();
        }

        if ($request->hasFile('photos')) {
            $photos = $existingPhotos;
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('properties', 'public');
            }
            $validated['photos'] = $photos;
        }

        $property->update($validated);

        // Sync amenities with pivot data
        $amenityData = [];
        foreach ($request->input('amenities', []) as $amenityId) {
            $data = $request->input("amenity_data.{$amenityId}", []);
            $amenityData[$amenityId] = [
                'included_in_rent' => $data['included_in_rent'] ?? true,
                'provider' => $data['provider'] ?? null,
                'monthly_cost' => $data['monthly_cost'] ?? null,
                'notes' => $data['notes'] ?? null,
            ];
        }
        $property->amenities()->sync($amenityData);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Property updated.');
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return redirect()->route('agent.properties.index')->with('success', 'Property deleted.');
    }
}
