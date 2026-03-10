<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::where('agent_id', $request->user()->id)
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->with(['landlord.user', 'units'])
            ->paginate(15)->withQueryString();

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
            'photos.*' => 'required|file|image|max:5120',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),
            'last_renovated' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_floors' => 'nullable|integer|min:1|max:200',
            'total_units_count' => 'nullable|integer|min:1|max:10000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'parking_type' => 'nullable|string|in:none,open,covered,underground,street',
            'ev_charging' => 'nullable|boolean',
            'fiber_ready' => 'nullable|boolean',
            'backup_power' => 'nullable|boolean',
            'water_storage_liters' => 'nullable|integer|min:0',
            'pet_policy' => 'nullable|string|in:allowed,not_allowed,case_by_case',
            'smoking_policy' => 'nullable|string|in:allowed,not_allowed,designated_areas',
            'security_features' => 'nullable|array',
            'security_features.*' => 'string',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'amenity_data' => 'nullable|array',
            'amenity_data.*.included_in_rent' => 'nullable|boolean',
            'amenity_data.*.provider' => 'nullable|string|max:255',
            'amenity_data.*.monthly_cost' => 'nullable|numeric|min:0',
            'amenity_data.*.notes' => 'nullable|string|max:1000',
        ]);

        $photos = [];
        foreach (($request->file('photos') ?? []) as $photo) {
            if (! $photo instanceof UploadedFile || ! $photo->isValid()) {
                continue;
            }

            $storedPhoto = $this->storePhotoFile($photo);
            if (! empty($storedPhoto)) {
                $photos[] = $storedPhoto;
            }
        }

        if (empty($photos)) {
            return back()->withErrors(['photos' => 'Please upload at least one valid photo.'])->withInput();
        }

        $property = Property::create([
            ...$validated,
            'agent_id' => $request->user()->id,
            'photos' => $photos,
            'ev_charging' => $request->boolean('ev_charging'),
            'fiber_ready' => $request->boolean('fiber_ready'),
            'backup_power' => $request->boolean('backup_power'),
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

    public function show(string $org, Property $property)
    {
        $property->load(['landlord.user', 'units.activeLease.tenant.user', 'amenities']);
        return view('agent.properties.show', compact('property'));
    }

    public function edit(string $org, Property $property)
    {
        $landlords = Landlord::with('user')->get();
        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $property->load('amenities');
        return view('agent.properties.edit', compact('property', 'landlords', 'amenities'));
    }

    public function removePhoto(Request $request, string $org, Property $property)
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

    public function update(Request $request, string $org, Property $property)
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
            'photos.*' => 'nullable|file|image|max:5120',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),
            'last_renovated' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_floors' => 'nullable|integer|min:1|max:200',
            'total_units_count' => 'nullable|integer|min:1|max:10000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'parking_type' => 'nullable|string|in:none,open,covered,underground,street',
            'ev_charging' => 'nullable|boolean',
            'fiber_ready' => 'nullable|boolean',
            'backup_power' => 'nullable|boolean',
            'water_storage_liters' => 'nullable|integer|min:0',
            'pet_policy' => 'nullable|string|in:allowed,not_allowed,case_by_case',
            'smoking_policy' => 'nullable|string|in:allowed,not_allowed,designated_areas',
            'security_features' => 'nullable|array',
            'security_features.*' => 'string',
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
            foreach (($request->file('photos') ?? []) as $photo) {
                if (! $photo instanceof UploadedFile || ! $photo->isValid()) {
                    continue;
                }

                $storedPhoto = $this->storePhotoFile($photo);
                if (! empty($storedPhoto)) {
                    $photos[] = $storedPhoto;
                }
            }
            $validated['photos'] = $photos;
        }

        $validated['ev_charging'] = $request->boolean('ev_charging');
        $validated['fiber_ready'] = $request->boolean('fiber_ready');
        $validated['backup_power'] = $request->boolean('backup_power');

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

    public function destroy(string $org, Property $property)
    {
        $property->delete();
        return redirect()->route('agent.properties.index')->with('success', 'Property deleted.');
    }

    public function bulkDelete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'string']);

        $count = Property::where('agent_id', $request->user()->id)
            ->whereIn('id', $request->ids)
            ->count();

        Property::where('agent_id', $request->user()->id)
            ->whereIn('id', $request->ids)
            ->delete();

        return redirect()->route('agent.properties.index')
            ->with('success', "{$count} " . \Illuminate\Support\Str::plural('property', $count) . " deleted.");
    }

    private function storePhotoFile(UploadedFile $photo): ?string
    {
        $sourcePath = $photo->getPathname();

        if (empty($sourcePath) || ! is_file($sourcePath) || ! is_readable($sourcePath)) {
            return null;
        }

        $targetPath = 'properties/' . $photo->hashName();
        $stream = fopen($sourcePath, 'r');

        if (! is_resource($stream)) {
            return null;
        }

        try {
            $stored = Storage::disk('public')->put($targetPath, $stream);
        } catch (\Throwable $e) {
            report($e);
            return null;
        } finally {
            fclose($stream);
        }

        return $stored ? $targetPath : null;
    }
}
