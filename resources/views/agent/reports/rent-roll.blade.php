<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Rent Roll</h2>
            <a href="{{ route('agent.reports.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Back to Reports</a>
        </div>
    </x-slot>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <x-stats-card title="Total Potential Rent" :value="'KSh ' . number_format($totalRent, 2)" color="indigo" icon="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
        <x-stats-card title="Occupied Rent" :value="'KSh ' . number_format($occupiedRent, 2)" color="green" icon="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        <x-stats-card title="Vacant Loss" :value="'KSh ' . number_format($totalRent - $occupiedRent, 2)" color="red" icon="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
    </div>

    @foreach($properties as $property)
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-6">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">{{ $property->name }}</h3>
                <p class="mt-0.5 text-sm text-gray-500">{{ $property->address }}</p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50/30">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Size</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rent</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($property->units as $unit)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $unit->unit_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $unit->size ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($unit->rent_amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm"><x-status-badge :status="$unit->status" /></td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $unit->activeLease?->tenant?->user?->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</x-app-layout>
