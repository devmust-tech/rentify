<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Unit</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $property->name }}</p>
            </div>
            <a href="{{ route('agent.properties.show', $property) }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Property
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('agent.properties.units.store', $property) }}" method="POST" class="space-y-8">
            @csrf

            {{-- Basic Info --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Unit Information</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Basic details about this unit</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="unit_number" class="block text-sm font-medium text-gray-700 mb-1.5">Unit Number <span class="text-red-500">*</span></label>
                            <input type="text" name="unit_number" id="unit_number" value="{{ old('unit_number') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required placeholder="e.g. A1, 101">
                            @error('unit_number') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="floor_number" class="block text-sm font-medium text-gray-700 mb-1.5">Floor</label>
                            <input type="number" name="floor_number" id="floor_number" value="{{ old('floor_number') }}" min="0" max="200"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 3">
                            @error('floor_number') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700 mb-1.5">Size Label</label>
                            <input type="text" name="size" id="size" value="{{ old('size') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 1BR, Studio">
                            @error('size') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="size_sqm" class="block text-sm font-medium text-gray-700 mb-1.5">Area (sqm)</label>
                            <input type="number" name="size_sqm" id="size_sqm" value="{{ old('size_sqm') }}" step="0.01" min="0"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 45.5">
                            @error('size_sqm') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="furnishing" class="block text-sm font-medium text-gray-700 mb-1.5">Furnishing</label>
                            <select name="furnishing" id="furnishing"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                                <option value="">Not specified</option>
                                @foreach(['unfurnished' => 'Unfurnished', 'semi_furnished' => 'Semi-Furnished', 'furnished' => 'Fully Furnished'] as $v => $l)
                                    <option value="{{ $v }}" {{ old('furnishing') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                            @error('furnishing') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1.5">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" min="0" max="20"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="0">
                            @error('bedrooms') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1.5">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" min="0" max="20"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="0">
                            @error('bathrooms') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 cursor-pointer transition hover:border-gray-300 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50/50">
                                <input type="hidden" name="balcony" value="0">
                                <input type="checkbox" name="balcony" value="1" {{ old('balcony') ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                <span class="text-sm font-medium text-gray-700">Has Balcony</span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- Financials --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Financials</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Rent, deposit, and billing details</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Rent <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 text-sm">KSh</span>
                                </div>
                                <input type="number" name="rent_amount" id="rent_amount" value="{{ old('rent_amount') }}" step="0.01" min="0"
                                       class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                       required placeholder="0.00">
                            </div>
                            @error('rent_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Deposit Amount</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 text-sm">KSh</span>
                                </div>
                                <input type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount') }}" step="0.01" min="0"
                                       class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                       placeholder="0.00">
                            </div>
                            @error('deposit_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="service_charge" class="block text-sm font-medium text-gray-700 mb-1.5">Service Charge</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 text-sm">KSh</span>
                                </div>
                                <input type="number" name="service_charge" id="service_charge" value="{{ old('service_charge') }}" step="0.01" min="0"
                                       class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                       placeholder="0.00">
                            </div>
                            @error('service_charge') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="deposit_months" class="block text-sm font-medium text-gray-700 mb-1.5">Deposit Months</label>
                            <input type="number" name="deposit_months" id="deposit_months" value="{{ old('deposit_months') }}" min="1" max="12"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 2">
                            @error('deposit_months') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="billing_cycle" class="block text-sm font-medium text-gray-700 mb-1.5">Billing Cycle</label>
                            <select name="billing_cycle" id="billing_cycle"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                                <option value="monthly" {{ old('billing_cycle', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            </select>
                            @error('billing_cycle') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="min_lease_months" class="block text-sm font-medium text-gray-700 mb-1.5">Min Lease (months)</label>
                            <input type="number" name="min_lease_months" id="min_lease_months" value="{{ old('min_lease_months') }}" min="1" max="60"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 12">
                            @error('min_lease_months') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="available_from" class="block text-sm font-medium text-gray-700 mb-1.5">Available From</label>
                        <input type="date" name="available_from" id="available_from" value="{{ old('available_from') }}"
                               class="block w-full sm:w-1/3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                        @error('available_from') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Meters & Media --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Meters & Media</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Utility meters and media links</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="meter_type" class="block text-sm font-medium text-gray-700 mb-1.5">Meter Type</label>
                            <select name="meter_type" id="meter_type"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                                <option value="">Not specified</option>
                                <option value="shared" {{ old('meter_type') === 'shared' ? 'selected' : '' }}>Shared</option>
                                <option value="individual" {{ old('meter_type') === 'individual' ? 'selected' : '' }}>Individual</option>
                            </select>
                            @error('meter_type') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="electricity_meter" class="block text-sm font-medium text-gray-700 mb-1.5">Electricity Meter No.</label>
                            <input type="text" name="electricity_meter" id="electricity_meter" value="{{ old('electricity_meter') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. KP12345678">
                            @error('electricity_meter') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="water_meter" class="block text-sm font-medium text-gray-700 mb-1.5">Water Meter No.</label>
                            <input type="text" name="water_meter" id="water_meter" value="{{ old('water_meter') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. WM98765">
                            @error('water_meter') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="video_tour_url" class="block text-sm font-medium text-gray-700 mb-1.5">Video Tour URL</label>
                        <input type="url" name="video_tour_url" id="video_tour_url" value="{{ old('video_tour_url') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="https://youtube.com/watch?v=...">
                        @error('video_tour_url') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Actions --}}
            <div class="flex items-center gap-x-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Unit
                </button>
                <a href="{{ route('agent.properties.show', $property) }}"
                   class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
