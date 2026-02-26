<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tenants</h2>
                <p class="mt-1 text-sm text-gray-500">Manage tenants across your properties</p>
            </div>
            <a href="{{ route('landlord.tenants.create') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Tenant
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rent</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-x-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-indigo-700 font-semibold text-sm">
                                    {{ strtoupper(substr($tenant->user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $tenant->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $tenant->user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tenant->leases->first()?->unit?->unit_number ?? '--' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $tenant->leases->first()?->unit?->property?->name ?? '--' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $tenant->leases->first() ? 'KSh ' . number_format($tenant->leases->first()->rent_amount, 2) : '--' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="inline-flex items-center gap-x-3">
                                <a href="{{ route('landlord.tenants.show', $tenant) }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                                <a href="{{ route('landlord.tenants.edit', $tenant) }}" class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-gray-900">No tenants yet</p>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding a new tenant.</p>
                                <a href="{{ route('landlord.tenants.create') }}" class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">Add Tenant</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($tenants->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
