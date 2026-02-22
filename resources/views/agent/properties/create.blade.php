<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Property</h2>
                <p class="mt-1 text-sm text-gray-500">Create a new property listing in your portfolio</p>
            </div>
            <a href="{{ route('agent.properties.index') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Properties
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('agent.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Property Information --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Property Information</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Basic details about the property</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    {{-- Landlord --}}
                    <div>
                        <label for="landlord_id" class="block text-sm font-medium text-gray-700 mb-1.5">Landlord</label>
                        <select name="landlord_id" id="landlord_id"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                required>
                            <option value="">Select a landlord</option>
                            @foreach($landlords as $landlord)
                                <option value="{{ $landlord->id }}" {{ old('landlord_id') == $landlord->id ? 'selected' : '' }}>
                                    {{ $landlord->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('landlord_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Property Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Property Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="e.g. Sunrise Apartments"
                               required>
                        @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Property Type --}}
                    <div>
                        <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1.5">Property Type</label>
                        <select name="property_type" id="property_type"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                required>
                            <option value="">Select type</option>
                            @foreach(App\Enums\PropertyType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('property_type') == $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('property_type') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                  placeholder="Describe the property, amenities, nearby facilities...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Location Details --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Location Details</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Where is this property located?</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    {{-- Address --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Street Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="e.g. 123 Moi Avenue"
                               required>
                        @error('address') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- County --}}
                    <div>
                        <label for="county" class="block text-sm font-medium text-gray-700 mb-1.5">County</label>
                        <x-county-select name="county" :selected="old('county')" />
                        @error('county') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Photos --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Photos</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Upload images of the property</p>
                </div>
                <div class="px-6 py-6">
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700 mb-1.5">Property Photos</label>
                        <div class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-10 transition hover:border-indigo-400">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                </svg>
                                <div class="mt-4 flex text-sm text-gray-600">
                                    <label for="photos"
                                           class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 transition-colors">
                                        <span>Upload photos</span>
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
                </div>
            </fieldset>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-x-3 pt-2">
                <a href="{{ route('agent.properties.index') }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                    </svg>
                    Create Property
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
