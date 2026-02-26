<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Units</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $property->name }}</p>
            </div>
            <a href="{{ route('landlord.properties.units.create', $property) }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Unit
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Size</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rent</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deposit</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($units as $unit)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $unit->unit_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $unit->size ?? '--' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($unit->rent_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $unit->deposit_amount ? 'KSh ' . number_format($unit->deposit_amount, 2) : '--' }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$unit->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $unit->activeLease?->tenant?->user?->name ?? '--' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('landlord.properties.units.edit', [$property, $unit]) }}"
                               class="inline-flex items-center gap-x-1 text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <p class="text-sm font-medium text-gray-900">No units yet</p>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding a unit to this property.</p>
                            <a href="{{ route('landlord.properties.units.create', $property) }}" class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">Add Unit</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($units->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $units->links() }}</div>
        @endif
    </div>
</x-app-layout>
