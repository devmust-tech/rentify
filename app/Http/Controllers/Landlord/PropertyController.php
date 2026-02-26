<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = $request->user()->landlord->properties()
            ->with(['units'])
            ->paginate(12);

        return view('landlord.properties.index', compact('properties'));
    }

    public function create()
    {
        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('landlord.properties.create', compact('amenities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'landlord_id' => $request->user()->landlord->id,
            // agent_id is null for self-managed properties
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

        return redirect()->route('landlord.properties.index')->with('success', 'Property created successfully.');
    }

    public function show(Request $request, Property $property)
    {
        // Ensure the property belongs to this landlord
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $property->load(['units.activeLease.tenant.user', 'amenities']);

        return view('landlord.properties.show', compact('property'));
    }

    public function edit(Request $request, Property $property)
    {
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $property->load('amenities');
        return view('landlord.properties.edit', compact('property', 'amenities'));
    }

    public function removePhoto(Request $request, Property $property)
    {
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }

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
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $existingPhotos = $property->photos ?? [];
        $newPhotoCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
        $totalPhotos = count($existingPhotos) + $newPhotoCount;

        $validated = $request->validate([
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

        return redirect()->route('landlord.properties.show', $property)->with('success', 'Property updated.');
    }

    public function destroy(Request $request, Property $property)
    {
        if ($property->landlord_id !== $request->user()->landlord->id) {
            abort(403, 'Unauthorized access to this property.');
        }

        $property->delete();
        return redirect()->route('landlord.properties.index')->with('success', 'Property deleted.');
    }
}
