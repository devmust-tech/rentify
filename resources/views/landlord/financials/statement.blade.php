<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('landlord.financials.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Landlord Statement</h2>
                        <p class="mt-0.5 text-sm text-gray-500">Detailed breakdown by property and unit</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @forelse($properties as $property)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                {{-- Property Header --}}
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <div class="flex items-center gap-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 ring-1 ring-indigo-100">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $property->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $property->address }}</p>
                        </div>
                    </div>
                </div>

                {{-- Units Breakdown --}}
                <div class="divide-y divide-gray-100">
                    @foreach($property->units as $unit)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-x-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Unit {{ $unit->unit_number }}</p>
                                        <p class="text-xs text-gray-500">KSh {{ number_format($unit->rent_amount, 2) }}/mo</p>
                                    </div>
                                </div>
                            </div>

                            @foreach($unit->leases as $lease)
                                <div class="ml-11 mt-3 overflow-hidden rounded-lg bg-gray-50/80 ring-1 ring-gray-900/5">
                                    <div class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-x-2">
                                                <span class="text-sm text-gray-700">{{ $lease->tenant?->user?->name ?? 'N/A' }}</span>
                                                <x-status-badge :status="$lease->status" />
                                            </div>
                                            @php $paid = $lease->invoices->sum(fn($inv) => $inv->payments->sum('amount')); @endphp
                                            <span class="text-sm font-bold text-emerald-600">KSh {{ number_format($paid, 2) }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400">Total collected from this lease</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-500">No properties to display</p>
                        <p class="mt-1 text-sm text-gray-400">Your property statement will appear here once properties are assigned.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
