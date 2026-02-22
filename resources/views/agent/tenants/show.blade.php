<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $tenant->user->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">Tenant Profile</p>
            </div>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('agent.tenants.index') }}"
                   class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Tenants
                </a>
                <a href="{{ route('agent.tenants.edit', $tenant) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        {{-- Tenant Details Card --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Tenant Details</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Email</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $tenant->user->email }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Phone</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $tenant->user->phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">ID Document</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">
                            @if($tenant->id_document)
                                <a href="{{ Storage::url($tenant->id_document) }}" target="_blank" class="inline-flex items-center gap-x-1.5 text-indigo-600 hover:text-indigo-500 transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    View Document
                                </a>
                            @else
                                <span class="text-gray-400">Not uploaded</span>
                            @endif
                        </dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Emergency Contact</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900 whitespace-pre-line">{{ $tenant->emergency_contact ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Lease History Table --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Lease History</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Start Date</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">End Date</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tenant->leases as $lease)
                        <tr class="transition-colors hover:bg-gray-50/50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $lease->unit->unit_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $lease->unit->property->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $lease->start_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $lease->end_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm"><x-status-badge :status="$lease->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <p class="mt-4 text-sm font-medium text-gray-900">No lease history</p>
                                    <p class="mt-1 text-sm text-gray-500">This tenant does not have any leases on record.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
