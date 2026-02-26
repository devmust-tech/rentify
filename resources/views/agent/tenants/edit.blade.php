<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Edit Tenant: {{ $tenant->user->name }}</h2>
            <a href="{{ route('agent.tenants.show', $tenant) }}"
               class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Tenant
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <form action="{{ route('agent.tenants.update', $tenant) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $tenant->user->name) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               required
                               placeholder="Jane Doe">
                        @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email', $tenant->user->email) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               required
                               placeholder="tenant@example.com">
                        @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                        <input type="text"
                               name="phone"
                               id="phone"
                               value="{{ old('phone', $tenant->phone) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               required
                               placeholder="0712 345 678">
                        @error('phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="font-normal text-gray-400">(leave blank to keep current)</span></label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="Minimum 8 characters">
                        @error('password') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Emergency Contact --}}
                    @php $ec = is_array($tenant->emergency_contact) ? $tenant->emergency_contact : json_decode($tenant->emergency_contact, true); @endphp
                    <fieldset class="rounded-lg border border-gray-200 p-4">
                        <legend class="px-2 text-sm font-semibold text-gray-700">Emergency Contact</legend>
                        <div class="space-y-4">
                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">Contact Name</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $ec['name'] ?? '') }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                       placeholder="e.g. John Doe">
                                @error('emergency_contact_name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $ec['phone'] ?? '') }}"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                           placeholder="0712 345 678">
                                    @error('emergency_contact_phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-1.5">Relationship</label>
                                    <select name="emergency_contact_relationship" id="emergency_contact_relationship"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
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

                    {{-- ID Document --}}
                    <div>
                        <label for="id_document" class="block text-sm font-medium text-gray-700 mb-1.5">ID Document</label>
                        @if($tenant->id_document)
                            <div class="mb-3 flex items-center gap-x-3 rounded-lg bg-gray-50 px-4 py-3 ring-1 ring-gray-200">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <a href="{{ Storage::url($tenant->id_document) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View current document</a>
                            </div>
                        @endif
                        <div class="flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-8 transition hover:border-indigo-400">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <div class="mt-3 flex text-sm text-gray-600">
                                    <label for="id_document" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                        <span>{{ $tenant->id_document ? 'Replace document' : 'Upload document' }}</span>
                                        <input id="id_document" name="id_document" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500">PDF, JPG, PNG up to 5MB</p>
                            </div>
                        </div>
                        @error('id_document') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit"
                            class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
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
    </div>
</x-app-layout>
