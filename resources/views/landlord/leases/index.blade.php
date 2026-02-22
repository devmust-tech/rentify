<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Leases</h2>
                <p class="mt-1 text-sm text-gray-500">All leases across your properties</p>
            </div>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Period</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rent</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leases as $lease)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm">
                            <span class="font-medium text-gray-900">{{ $lease->tenant->user->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $lease->unit->unit_number }} - {{ $lease->unit->property->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $lease->start_date->format('d/m/Y') }} - {{ $lease->end_date?->format('d/m/Y') ?? 'Open' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($lease->rent_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$lease->status" /></td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('landlord.leases.show', $lease) }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-500">No leases found</p>
                                <p class="mt-1 text-sm text-gray-400">Leases for your properties will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($leases->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $leases->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
