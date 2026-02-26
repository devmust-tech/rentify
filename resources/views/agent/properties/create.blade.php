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
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5" x-data="photoUpload()">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Photos <span class="text-red-500">*</span></h3>
                    <p class="mt-0.5 text-sm text-gray-500">Upload 1 to 10 images of the property (required)</p>
                </div>
                <div class="px-6 py-6">
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700 mb-1.5">Property Photos</label>
                        <div class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-10 transition hover:border-indigo-400"
                             :class="{ 'border-indigo-400 bg-indigo-50/50': dragover }"
                             @dragover.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             @drop.prevent="dragover = false; handleFiles($event.dataTransfer.files)">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                </svg>
                                <div class="mt-4 flex text-sm text-gray-600">
                                    <label for="photos"
                                           class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 transition-colors">
                                        <span>Upload photos</span>
                                        <input id="photos" name="photos[]" type="file" multiple accept="image/*" class="sr-only" @change="handleFiles($event.target.files)" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500">PNG, JPG, WEBP up to 5MB each (max 10 photos)</p>
                                <p class="mt-1 text-xs font-medium" :class="previews.length > 0 ? 'text-indigo-600' : 'text-gray-400'" x-text="previews.length + '/10 photos selected'"></p>
                            </div>
                        </div>

                        {{-- Photo Previews --}}
                        <div x-show="previews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                            <template x-for="(preview, index) in previews" :key="index">
                                <div class="relative group rounded-lg overflow-hidden ring-1 ring-gray-200">
                                    <img :src="preview" class="h-24 w-full object-cover">
                                    <button type="button" @click="removePreview(index)"
                                            class="absolute top-1 right-1 rounded-full bg-red-500 p-1 text-white opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        @error('photos') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error('photos.*') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            @push('scripts')
            <script>
                function photoUpload() {
                    return {
                        previews: [],
                        dragover: false,
                        handleFiles(files) {
                            if (this.previews.length + files.length > 10) {
                                alert('Maximum 10 photos allowed.');
                                return;
                            }
                            for (const file of files) {
                                if (!file.type.startsWith('image/')) continue;
                                if (file.size > 5 * 1024 * 1024) { alert(file.name + ' exceeds 5MB limit.'); continue; }
                                const reader = new FileReader();
                                reader.onload = (e) => this.previews.push(e.target.result);
                                reader.readAsDataURL(file);
                            }
                        },
                        removePreview(index) {
                            this.previews.splice(index, 1);
                            // Reset file input - user must re-select
                            const input = document.getElementById('photos');
                            const dt = new DataTransfer();
                            for (let i = 0; i < input.files.length; i++) {
                                if (i !== index) dt.items.add(input.files[i]);
                            }
                            input.files = dt.files;
                        }
                    };
                }
            </script>
            @endpush

            {{-- Amenities --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Amenities</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Select the amenities available at this property</p>
                </div>
                <div class="px-6 py-6 space-y-8">
                    @foreach($amenities as $category => $categoryAmenities)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-x-2">
                                @if($category === 'utility')
                                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-amber-50 ring-1 ring-amber-200">
                                        <svg class="h-3.5 w-3.5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" /></svg>
                                    </span>
                                @elseif($category === 'facility')
                                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-blue-50 ring-1 ring-blue-200">
                                        <svg class="h-3.5 w-3.5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>
                                    </span>
                                @else
                                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-emerald-50 ring-1 ring-emerald-200">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                                    </span>
                                @endif
                                {{ ucfirst($category) }}
                            </h4>
                            <div class="space-y-4">
                                @foreach($categoryAmenities as $amenity)
                                    <div x-data="{ checked: {{ in_array($amenity->id, old('amenities', [])) ? 'true' : 'false' }}, includedInRent: {{ old("amenity_data.{$amenity->id}.included_in_rent", '1') ? 'true' : 'false' }} }" class="rounded-lg border border-gray-200 transition-all" :class="checked ? 'border-indigo-300 bg-indigo-50/30 ring-1 ring-indigo-200' : 'hover:border-gray-300'">
                                        <div class="flex items-center gap-x-3 px-4 py-3">
                                            <input type="checkbox"
                                                   name="amenities[]"
                                                   value="{{ $amenity->id }}"
                                                   x-model="checked"
                                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 transition">
                                            @if($amenity->icon)
                                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $amenity->icon }}" />
                                                </svg>
                                            @endif
                                            <label class="text-sm font-medium text-gray-900 cursor-pointer select-none" @click="checked = !checked">{{ $amenity->name }}</label>
                                        </div>

                                        {{-- Sub-fields when checked --}}
                                        <div x-show="checked" x-collapse class="border-t border-gray-200 px-4 py-4 space-y-4 bg-white/50">
                                            {{-- Included in Rent Toggle --}}
                                            <div class="flex items-center justify-between">
                                                <label class="text-sm text-gray-700">Included in rent?</label>
                                                <button type="button"
                                                        @click="includedInRent = !includedInRent"
                                                        :class="includedInRent ? 'bg-indigo-600' : 'bg-gray-200'"
                                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                                    <span :class="includedInRent ? 'translate-x-5' : 'translate-x-0'"
                                                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                                <input type="hidden" :name="'amenity_data[' + '{{ $amenity->id }}' + '][included_in_rent]'" :value="includedInRent ? '1' : '0'">
                                            </div>

                                            {{-- Provider --}}
                                            <div>
                                                <label class="block text-sm text-gray-700 mb-1">Provider <span class="text-gray-400">(optional)</span></label>
                                                <input type="text"
                                                       name="amenity_data[{{ $amenity->id }}][provider]"
                                                       value="{{ old("amenity_data.{$amenity->id}.provider") }}"
                                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                                       placeholder="e.g. Safaricom, Kenya Power">
                                            </div>

                                            {{-- Monthly Cost (only if not included in rent) --}}
                                            <div x-show="!includedInRent">
                                                <label class="block text-sm text-gray-700 mb-1">Monthly Cost (KSh)</label>
                                                <input type="number"
                                                       name="amenity_data[{{ $amenity->id }}][monthly_cost]"
                                                       value="{{ old("amenity_data.{$amenity->id}.monthly_cost") }}"
                                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                                       placeholder="0.00"
                                                       step="0.01"
                                                       min="0">
                                            </div>

                                            {{-- Notes --}}
                                            <div>
                                                <label class="block text-sm text-gray-700 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
                                                <input type="text"
                                                       name="amenity_data[{{ $amenity->id }}][notes]"
                                                       value="{{ old("amenity_data.{$amenity->id}.notes") }}"
                                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                                       placeholder="Any additional details...">
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
