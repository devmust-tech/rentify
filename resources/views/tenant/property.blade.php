<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">My Property</h2>
    </x-slot>

    {{-- Unit & Property cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Unit Details --}}
        <div class="lg:col-span-1 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-indigo-100 p-2">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Your Unit</h3>
                </div>
            </div>
            <div class="px-6 py-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Unit Number</dt>
                        <dd class="mt-1 text-2xl font-bold text-indigo-600">{{ $unit->unit_number }}</dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Monthly Rent</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">KSh {{ number_format($lease->rent_amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Deposit</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">KSh {{ number_format($lease->deposit, 2) }}</dd>
                        </div>
                        @if($unit->size)
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Size</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $unit->size }}</dd>
                            </div>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$unit->status" /></dd>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Lease Period</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $lease->start_date->format('d M Y') }} — {{ $lease->end_date?->format('d M Y') ?? 'Open-ended' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Property Details --}}
        <div class="lg:col-span-2 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-emerald-100 p-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $property->name }}</h3>
                </div>
            </div>

            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Address</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $property->address ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">County</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $property->county_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Type</dt>
                        <dd class="mt-1"><x-status-badge :status="$property->property_type" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Total Units</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $property->total_units_count }}</dd>
                    </div>
                    @if($property->description)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Description</dt>
                            <dd class="mt-1 text-sm text-gray-700 leading-relaxed">{{ $property->description }}</dd>
                        </div>
                    @endif
                </dl>

                {{-- Property Photos --}}
                @if($property->photos && count($property->photos) > 0)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <h4 class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-3">Photos</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($property->photos as $photo)
                                <div class="aspect-video overflow-hidden rounded-lg ring-1 ring-gray-200">
                                    <img src="{{ Storage::url($photo) }}" alt="Property photo"
                                         class="h-full w-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Amenities --}}
                @if($property->amenities->count() > 0)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <h4 class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-3">Amenities</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($property->amenities as $amenity)
                                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 ring-1 ring-indigo-100">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $amenity->name }}
                                    @if($amenity->pivot->included_in_rent)
                                        <span class="text-indigo-400">(included)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
