<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">My Properties</h2>
                <p class="mt-1 text-sm text-gray-500">All properties in your portfolio</p>
            </div>
            <a href="{{ route('landlord.properties.create') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Property
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($properties as $property)
            <div class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-semibold text-gray-900">
                                <a href="{{ route('landlord.properties.show', $property) }}" class="text-indigo-600 hover:text-indigo-500 transition-colors">{{ $property->name }}</a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $property->address }}</p>
                            <p class="text-sm text-gray-400">{{ config('counties.counties')[$property->county] ?? $property->county }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 ring-1 ring-indigo-100">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center gap-x-4">
                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            {{ $property->units->where('status.value', 'occupied')->count() }} Occupied
                        </span>
                        <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                            {{ $property->units->where('status.value', 'vacant')->count() }} Vacant
                        </span>
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">
                            {{ $property->units->count() }} Total
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                    <div class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="rounded-full bg-gray-100 p-3 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">No properties yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first property.</p>
                            <a href="{{ route('landlord.properties.create') }}"
                               class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Property
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($properties->hasPages())
        <div class="mt-6">
            {{ $properties->links() }}
        </div>
    @endif
</x-app-layout>
