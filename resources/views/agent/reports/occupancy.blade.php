<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Occupancy Report</h2>
            <a href="{{ route('agent.reports.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Back to Reports</a>
        </div>
    </x-slot>

    {{-- Overall Stats --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <x-stats-card title="Overall Occupancy" :value="$overallRate . '%'" color="indigo" icon="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
        <x-stats-card title="Total Occupied" :value="$overallOccupied . ' / ' . $overallTotal" color="green" icon="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        <x-stats-card title="Total Vacant" :value="($overallTotal - $overallOccupied)" color="yellow" icon="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
    </div>

    {{-- Per Property Breakdown --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Property Breakdown</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/30">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Total Units</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Occupied</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Vacant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Occupancy Rate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($summary as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $item->property->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->property->address }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->total }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-emerald-600">{{ $item->occupied }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-amber-600">{{ $item->vacant }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-x-3">
                                <div class="w-24 rounded-full bg-gray-200 h-2">
                                    <div class="rounded-full h-2 {{ $item->rate >= 80 ? 'bg-emerald-500' : ($item->rate >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $item->rate }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $item->rate }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
