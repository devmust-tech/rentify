<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Tenant: {{ $tenant->user->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">Update tenant information</p>
            </div>
            <a href="{{ route('agent.tenants.show', $tenant) }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Tenant
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('agent.tenants.update', $tenant) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Personal Information</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Account credentials and contact details</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $tenant->user->name) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required>
                            @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $tenant->user->email) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required>
                            @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required>
                            @error('phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="font-normal text-gray-400">(leave blank to keep)</span></label>
                            <input type="password" name="password" id="password"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="Minimum 8 characters">
                            @error('password') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="preferred_contact" class="block text-sm font-medium text-gray-700 mb-1.5">Preferred Contact Method</label>
                        <select name="preferred_contact" id="preferred_contact"
                                class="block w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @foreach(['phone' => 'Phone Call', 'whatsapp' => 'WhatsApp', 'email' => 'Email', 'sms' => 'SMS'] as $v => $l)
                                <option value="{{ $v }}" {{ old('preferred_contact', $tenant->preferred_contact ?? 'phone') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('preferred_contact') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Identity & Employment --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Identity & Employment</h3>
                    <p class="mt-0.5 text-sm text-gray-500">KYC and employment information</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="national_id" class="block text-sm font-medium text-gray-700 mb-1.5">National ID / Passport</label>
                            <input type="text" name="national_id" id="national_id" value="{{ old('national_id', $tenant->national_id) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('national_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kra_pin" class="block text-sm font-medium text-gray-700 mb-1.5">KRA PIN</label>
                            <input type="text" name="kra_pin" id="kra_pin" value="{{ old('kra_pin', $tenant->kra_pin) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('kra_pin') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1.5">Occupation</label>
                            <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $tenant->occupation) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('occupation') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="employer" class="block text-sm font-medium text-gray-700 mb-1.5">Employer</label>
                            <input type="text" name="employer" id="employer" value="{{ old('employer', $tenant->employer) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('employer') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="monthly_income" class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Income</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 text-sm">KSh</span>
                                </div>
                                <input type="number" name="monthly_income" id="monthly_income" value="{{ old('monthly_income', $tenant->monthly_income) }}" step="0.01" min="0"
                                       class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            </div>
                            @error('monthly_income') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- ID Document --}}
                    <div>
                        <label for="id_document" class="block text-sm font-medium text-gray-700 mb-1.5">ID Document Upload</label>
                        @if($tenant->id_document)
                            <div class="mb-3 flex items-center gap-x-3 rounded-lg bg-gray-50 px-4 py-3 ring-1 ring-gray-200">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <a href="{{ Storage::url($tenant->id_document) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View current document</a>
                            </div>
                        @endif
                        <div class="flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-6 transition hover:border-indigo-400">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <div class="mt-2 flex text-sm text-gray-600">
                                    <label for="id_document" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                        <span>{{ $tenant->id_document ? 'Replace document' : 'Upload document' }}</span>
                                        <input id="id_document" name="id_document" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">PDF, JPG, PNG up to 5MB</p>
                            </div>
                        </div>
                        @error('id_document') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Emergency Contact --}}
            @php $ec = is_array($tenant->emergency_contact) ? $tenant->emergency_contact : json_decode($tenant->emergency_contact, true); @endphp
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-orange-200/50">
                <div class="border-b border-orange-100 bg-orange-50/50 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-100">
                            <svg class="h-4 w-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-orange-900">Emergency Contact</h3>
                            <p class="mt-0.5 text-sm text-orange-600">Person to contact in case of emergency</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $ec['name'] ?? '') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-400 focus:ring-orange-400 text-sm transition">
                        @error('emergency_contact_name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                            <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $ec['phone'] ?? '') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-400 focus:ring-orange-400 text-sm transition">
                            @error('emergency_contact_phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-1.5">Relationship</label>
                            <select name="emergency_contact_relationship" id="emergency_contact_relationship"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-400 focus:ring-orange-400 text-sm transition">
                                <option value="">Select...</option>
                                @foreach(['Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Colleague', 'Other'] as $rel)
                                    <option value="{{ $rel }}" {{ old('emergency_contact_relationship', $ec['relationship'] ?? '') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                            @error('emergency_contact_relationship') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- Guarantor --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Guarantor</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Person who guarantees the tenant's obligations</p>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="guarantor_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="guarantor_name" id="guarantor_name" value="{{ old('guarantor_name', $tenant->guarantor_name) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('guarantor_name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="guarantor_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                            <input type="text" name="guarantor_phone" id="guarantor_phone" value="{{ old('guarantor_phone', $tenant->guarantor_phone) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('guarantor_phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="guarantor_relationship" class="block text-sm font-medium text-gray-700 mb-1.5">Relationship</label>
                            <select name="guarantor_relationship" id="guarantor_relationship"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                                <option value="">Select...</option>
                                @foreach(['Parent', 'Spouse', 'Sibling', 'Employer', 'Friend', 'Other'] as $rel)
                                    <option value="{{ $rel }}" {{ old('guarantor_relationship', $tenant->guarantor_relationship) == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                            @error('guarantor_relationship') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="guarantor_id" class="block text-sm font-medium text-gray-700 mb-1.5">ID Number</label>
                            <input type="text" name="guarantor_id" id="guarantor_id" value="{{ old('guarantor_id', $tenant->guarantor_id) }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('guarantor_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- Household --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5" x-data="{ hasPets: {{ old('has_pets', $tenant->has_pets) ? 'true' : 'false' }} }">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Household Composition</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Who will be living in the unit?</p>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="occupants" class="block text-sm font-medium text-gray-700 mb-1.5">Total Occupants</label>
                            <input type="number" name="occupants" id="occupants" value="{{ old('occupants', $tenant->occupants) }}" min="1" max="20"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('occupants') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="children" class="block text-sm font-medium text-gray-700 mb-1.5">Children</label>
                            <input type="number" name="children" id="children" value="{{ old('children', $tenant->children) }}" min="0" max="20"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            @error('children') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 cursor-pointer transition hover:border-gray-300 has-[:checked]:border-indigo-300 has-[:checked]:bg-indigo-50/50">
                                <input type="hidden" name="has_pets" value="0">
                                <input type="checkbox" name="has_pets" value="1" x-model="hasPets"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                <span class="text-sm font-medium text-gray-700">Has Pets</span>
                            </label>
                        </div>
                    </div>
                    <div x-show="hasPets" x-collapse>
                        <label for="pet_details" class="block text-sm font-medium text-gray-700 mb-1.5">Pet Details</label>
                        <input type="text" name="pet_details" id="pet_details" value="{{ old('pet_details', $tenant->pet_details) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="e.g. 1 dog (Labrador), 2 cats">
                        @error('pet_details') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Actions --}}
            <div class="flex items-center gap-x-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Update Tenant
                </button>
                <a href="{{ route('agent.tenants.show', $tenant) }}"
                   class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
