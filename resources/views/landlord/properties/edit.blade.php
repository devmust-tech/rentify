<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Property</h2>
                <p class="mt-1 text-sm text-gray-500">Update details for {{ $property->name }}</p>
            </div>
            <a href="{{ route('landlord.properties.show', $property) }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Property
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('landlord.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Property Information --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Property Information</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Basic details about the property</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Property Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $property->name) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                        @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1.5">Property Type</label>
                        <select name="property_type" id="property_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select type</option>
                            @foreach(App\Enums\PropertyType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('property_type', $property->property_type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('property_type') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">{{ old('description', $property->description) }}</textarea>
                        @error('description') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Location Details --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Location Details</h3>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Street Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                        @error('address') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="county" class="block text-sm font-medium text-gray-700 mb-1.5">County</label>
                        <x-county-select name="county" :selected="old('county', $property->county)" />
                        @error('county') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Existing Photos --}}
            @if($property->photos && count($property->photos) > 0)
                <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Current Photos</h3>
                        <p class="mt-0.5 text-sm text-gray-500">{{ count($property->photos) }}/10 photos. Click the X to remove a photo.</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                            @foreach($property->photos as $index => $photo)
                                <div class="relative group rounded-lg overflow-hidden ring-1 ring-gray-200">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Property photo" class="h-24 w-full object-cover">
                                    <form method="POST" action="{{ route('landlord.properties.remove-photo', $property) }}" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="photo_index" value="{{ $index }}">
                                        <button type="submit" class="rounded-full bg-red-500 p-1 text-white shadow-sm hover:bg-red-600">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </fieldset>
            @endif

            {{-- Add More Photos --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Add More Photos</h3>
                    <p class="mt-0.5 text-sm text-gray-500">New photos will be added to existing ones (max 10 total)</p>
                </div>
                <div class="px-6 py-6">
                    <div class="flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-10 transition hover:border-indigo-400">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                            <div class="mt-4 flex text-sm text-gray-600">
                                <label for="photos" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                    <span>Upload additional photos</span>
                                    <input id="photos" name="photos[]" type="file" multiple accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">PNG, JPG, WEBP up to 5MB each</p>
                        </div>
                    </div>
                    @error('photos') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('photos.*') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            {{-- Amenities --}}
            @php
                $existingAmenityIds = $property->amenities->pluck('id')->toArray();
                $existingAmenityData = $property->amenities->keyBy('id')->map(fn($a) => [
                    'included_in_rent' => $a->pivot->included_in_rent,
                    'provider' => $a->pivot->provider,
                    'monthly_cost' => $a->pivot->monthly_cost,
                    'notes' => $a->pivot->notes,
                ]);
            @endphp
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Amenities</h3>
                </div>
                <div class="px-6 py-6 space-y-8">
                    @foreach($amenities as $category => $categoryAmenities)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">{{ ucfirst($category) }}</h4>
                            <div class="space-y-4">
                                @foreach($categoryAmenities as $amenity)
                                    @php
                                        $isChecked = in_array($amenity->id, old('amenities', $existingAmenityIds));
                                        $pivotData = $existingAmenityData[$amenity->id] ?? [];
                                        $isIncluded = old("amenity_data.{$amenity->id}.included_in_rent", $pivotData['included_in_rent'] ?? true);
                                        $providerVal = old("amenity_data.{$amenity->id}.provider", $pivotData['provider'] ?? '');
                                        $costVal = old("amenity_data.{$amenity->id}.monthly_cost", $pivotData['monthly_cost'] ?? '');
                                        $notesVal = old("amenity_data.{$amenity->id}.notes", $pivotData['notes'] ?? '');
                                    @endphp
                                    <div x-data="{ checked: {{ $isChecked ? 'true' : 'false' }}, includedInRent: {{ $isIncluded ? 'true' : 'false' }} }" class="rounded-lg border border-gray-200 transition-all" :class="checked ? 'border-indigo-300 bg-indigo-50/30 ring-1 ring-indigo-200' : 'hover:border-gray-300'">
                                        <div class="flex items-center gap-x-3 px-4 py-3">
                                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" x-model="checked"
                                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 transition">
                                            @if($amenity->icon)
                                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $amenity->icon }}" />
                                                </svg>
                                            @endif
                                            <label class="text-sm font-medium text-gray-900 cursor-pointer select-none" @click="checked = !checked">{{ $amenity->name }}</label>
                                        </div>
                                        <div x-show="checked" x-collapse class="border-t border-gray-200 px-4 py-4 space-y-4 bg-white/50">
                                            <div class="flex items-center justify-between">
                                                <label class="text-sm text-gray-700">Included in rent?</label>
                                                <button type="button" @click="includedInRent = !includedInRent" :class="includedInRent ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out">
                                                    <span :class="includedInRent ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                                <input type="hidden" :name="'amenity_data[' + '{{ $amenity->id }}' + '][included_in_rent]'" :value="includedInRent ? '1' : '0'">
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-700 mb-1">Provider <span class="text-gray-400">(optional)</span></label>
                                                <input type="text" name="amenity_data[{{ $amenity->id }}][provider]" value="{{ $providerVal }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                                            </div>
                                            <div x-show="!includedInRent">
                                                <label class="block text-sm text-gray-700 mb-1">Monthly Cost (KSh)</label>
                                                <input type="number" name="amenity_data[{{ $amenity->id }}][monthly_cost]" value="{{ $costVal }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" step="0.01" min="0">
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-700 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
                                                <input type="text" name="amenity_data[{{ $amenity->id }}][notes]" value="{{ $notesVal }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-x-3 pt-2">
                <a href="{{ route('landlord.properties.show', $property) }}" class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    Update Property
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
