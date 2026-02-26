<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('landlord.properties.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $property->name }}</h2>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $property->address }}, {{ $property->county_name }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('landlord.properties.units.create', $property) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Unit
                </a>
                <a href="{{ route('landlord.properties.edit', $property) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                    </svg>
                    Edit Property
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-6 py-5">
            <div class="flex items-center gap-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 ring-1 ring-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Units</p>
                    <p class="mt-0.5 text-2xl font-bold text-gray-900">{{ $property->units->count() }}</p>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-6 py-5">
            <div class="flex items-center gap-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 ring-1 ring-emerald-100">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Occupied</p>
                    <p class="mt-0.5 text-2xl font-bold text-emerald-600">{{ $property->units->where('status.value', 'occupied')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-6 py-5">
            <div class="flex items-center gap-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 ring-1 ring-amber-100">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Vacant</p>
                    <p class="mt-0.5 text-2xl font-bold text-amber-600">{{ $property->units->where('status.value', 'vacant')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Property Details Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Property Details</h3>
        </div>
        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $property->address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">County</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $property->county_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1.5"><x-status-badge :status="$property->property_type" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Management</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">
                        @if($property->agent_id)
                            Managed by Agent
                        @else
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">Self-Managed</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Photo Gallery --}}
    @if($property->photos && count($property->photos))
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Photos</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($property->photos as $photo)
                        <a href="{{ Storage::url($photo) }}" target="_blank" class="group relative overflow-hidden rounded-lg ring-1 ring-gray-200 hover:ring-indigo-300 transition-all">
                            <img src="{{ Storage::url($photo) }}" alt="Property photo" class="h-40 w-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Units Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Units</h3>
                    <p class="mt-0.5 text-sm text-gray-500">All units belonging to this property</p>
                </div>
                <a href="{{ route('landlord.properties.units.create', $property) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Unit
                </a>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/30">
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Size</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rent</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($property->units as $unit)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $unit->unit_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $unit->size ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($unit->rent_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$unit->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $unit->activeLease?->tenant?->user?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('landlord.properties.units.edit', [$property, $unit]) }}"
                               class="inline-flex items-center gap-x-1 text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                </svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="rounded-full bg-gray-100 p-3 mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">No units yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding a unit to this property.</p>
                                <a href="{{ route('landlord.properties.units.create', $property) }}"
                                   class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Add Unit
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
