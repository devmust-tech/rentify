<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Property</h2>
                <p class="mt-1 text-sm text-gray-500">Create a new self-managed property</p>
            </div>
            <a href="{{ route('landlord.properties.index') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Properties
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('landlord.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Property Information --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Property Information</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Basic details about the property</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Property Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="e.g. Sunrise Apartments" required>
                        @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1.5">Property Type</label>
                        <select name="property_type" id="property_type"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select type</option>
                            @foreach(App\Enums\PropertyType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('property_type') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('property_type') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
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
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Street Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="e.g. 123 Moi Avenue" required>
                        @error('address') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
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
                    <div x-show="previews.length > 0" class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-medium text-gray-700"><span x-text="files.length"></span> of 10 photos selected</p>
                            <button type="button" @click="clearAll()" class="text-xs font-medium text-red-600 hover:text-red-700 transition">Clear all</button>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                            <template x-for="(preview, index) in previews" :key="index">
                                <div class="relative group aspect-square rounded-lg overflow-hidden ring-1 ring-gray-200 bg-gray-50">
                                    <img :src="preview" class="h-full w-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" @click="removePreview(index)"
                                                class="rounded-full bg-white/90 p-1.5 text-red-600 hover:bg-white transition shadow">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                    <div class="absolute top-1.5 left-1.5 rounded bg-black/60 px-1.5 py-0.5 text-[10px] font-medium text-white" x-text="index + 1"></div>
                                </div>
                            </template>
                            <div x-show="files.length < 10"
                                 class="aspect-square rounded-lg border-2 border-dashed border-gray-300 hover:border-indigo-400 flex items-center justify-center cursor-pointer transition group"
                                 @click="$refs.photoInput.click()">
                                <div class="text-center">
                                    <svg class="mx-auto h-6 w-6 text-gray-400 group-hover:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    <span class="mt-1 block text-[10px] font-medium text-gray-500 group-hover:text-indigo-600 transition">Add more</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="previews.length === 0">
                        <div class="flex justify-center rounded-xl border-2 border-dashed px-6 py-10 transition cursor-pointer"
                             :class="{ 'border-indigo-400 bg-indigo-50/50': dragover, 'border-gray-300 hover:border-indigo-300 hover:bg-indigo-50/20': !dragover }"
                             @dragover.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             @drop.prevent="dragover = false; handleFiles($event.dataTransfer.files)"
                             @click="$refs.photoInput.click()">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                </svg>
                                <div class="mt-4 text-sm text-gray-600"><span class="font-semibold text-indigo-600">Click to upload</span> or drag and drop</div>
                                <p class="mt-1.5 text-xs text-gray-500">PNG, JPG, WEBP up to 5MB each &middot; max 10 photos</p>
                            </div>
                        </div>
                    </div>

                    <input x-ref="photoInput" id="photos" name="photos[]" type="file" multiple accept="image/*" class="sr-only" @change="handleFiles($event.target.files)">
                    @error('photos') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('photos.*') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            @push('scripts')
            <script>
                function photoUpload() {
                    return {
                        previews: [], files: [], dragover: false,
                        handleFiles(inputFiles) {
                            const newFiles = Array.from(inputFiles);
                            const remaining = 10 - this.files.length;
                            if (remaining <= 0) { alert('Maximum 10 photos allowed.'); return; }
                            const toAdd = newFiles.slice(0, remaining);
                            if (newFiles.length > remaining) alert('Only ' + remaining + ' more photo(s) can be added.');
                            toAdd.forEach(file => {
                                if (!file.type.startsWith('image/')) return;
                                if (file.size > 5 * 1024 * 1024) { alert(file.name + ' exceeds 5MB limit.'); return; }
                                this.files.push(file);
                                const reader = new FileReader();
                                reader.onload = (e) => this.previews.push(e.target.result);
                                reader.readAsDataURL(file);
                            });
                            this.syncInput();
                        },
                        removePreview(index) { this.previews.splice(index, 1); this.files.splice(index, 1); this.syncInput(); },
                        clearAll() { this.previews = []; this.files = []; this.syncInput(); },
                        syncInput() {
                            const input = document.getElementById('photos');
                            const dt = new DataTransfer();
                            this.files.forEach(f => dt.items.add(f));
                            input.files = dt.files;
                        }
                    };
                }
            </script>
            @endpush

            {{-- Amenities --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5" x-data="amenitySelector()">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Amenities</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Select the amenities available at this property</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    @foreach($amenities as $category => $categoryAmenities)
                        @php
                            $categoryColors = [
                                'utility'  => ['active' => 'bg-amber-100 ring-amber-300 text-amber-800', 'icon' => 'text-amber-600'],
                                'facility' => ['active' => 'bg-blue-100 ring-blue-300 text-blue-800', 'icon' => 'text-blue-600'],
                                'service'  => ['active' => 'bg-emerald-100 ring-emerald-300 text-emerald-800', 'icon' => 'text-emerald-600'],
                            ];
                            $colors = $categoryColors[$category] ?? $categoryColors['service'];
                        @endphp
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ ucfirst($category) === 'Utility' ? 'Utilities' : ucfirst($category) . (str_ends_with($category, 'y') ? '' : 's') }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($categoryAmenities as $amenity)
                                    <button type="button" @click="toggle('{{ $amenity->id }}')"
                                            :class="selected.includes('{{ $amenity->id }}') ? '{{ $colors['active'] }} ring-1 shadow-sm' : 'bg-gray-50 ring-gray-200 text-gray-600 hover:bg-gray-100'"
                                            class="inline-flex items-center gap-x-1.5 rounded-full px-3 py-1.5 text-sm font-medium ring-1 transition-all cursor-pointer select-none">
                                        @if($amenity->icon)
                                            <svg class="h-3.5 w-3.5 shrink-0" :class="selected.includes('{{ $amenity->id }}') ? '{{ $colors['icon'] }}' : 'text-gray-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $amenity->icon }}" />
                                            </svg>
                                        @endif
                                        {{ $amenity->name }}
                                        <svg x-show="selected.includes('{{ $amenity->id }}')" class="h-3.5 w-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="amenities[]" :value="id">
                    </template>

                    <div x-show="selected.length > 0" x-collapse class="pt-4 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Configure selected amenities</h4>
                        <div class="space-y-3">
                            @foreach($amenities->flatten() as $amenity)
                                <div x-show="selected.includes('{{ $amenity->id }}')" x-collapse
                                     class="rounded-lg border border-gray-200 bg-gray-50/50 p-4">
                                    <div x-data="{ includedInRent: {{ old("amenity_data.{$amenity->id}.included_in_rent", '1') ? 'true' : 'false' }} }">
                                        <p class="text-sm font-semibold text-gray-800 mb-3">{{ $amenity->name }}</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="flex items-center justify-between sm:col-span-2">
                                                <label class="text-sm text-gray-600">Included in rent?</label>
                                                <button type="button" @click="includedInRent = !includedInRent"
                                                        :class="includedInRent ? 'bg-indigo-600' : 'bg-gray-300'"
                                                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                                    <span :class="includedInRent ? 'translate-x-4' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                                <input type="hidden" :name="'amenity_data[{{ $amenity->id }}][included_in_rent]'" :value="includedInRent ? '1' : '0'">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1">Provider</label>
                                                <input type="text" name="amenity_data[{{ $amenity->id }}][provider]" value="{{ old("amenity_data.{$amenity->id}.provider") }}"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. Kenya Power">
                                            </div>
                                            <div x-show="!includedInRent">
                                                <label class="block text-xs text-gray-500 mb-1">Monthly Cost (KSh)</label>
                                                <input type="number" name="amenity_data[{{ $amenity->id }}][monthly_cost]" value="{{ old("amenity_data.{$amenity->id}.monthly_cost") }}"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0.00" step="0.01" min="0">
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs text-gray-500 mb-1">Notes</label>
                                                <input type="text" name="amenity_data[{{ $amenity->id }}][notes]" value="{{ old("amenity_data.{$amenity->id}.notes") }}"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Any additional details...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </fieldset>

            @push('scripts')
            <script>
                function amenitySelector() {
                    return {
                        selected: @json(old('amenities', [])),
                        toggle(id) {
                            const idx = this.selected.indexOf(id);
                            if (idx === -1) { this.selected.push(id); } else { this.selected.splice(idx, 1); }
                        }
                    };
                }
            </script>
            @endpush

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-x-3 pt-2">
                <a href="{{ route('landlord.properties.index') }}"
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
