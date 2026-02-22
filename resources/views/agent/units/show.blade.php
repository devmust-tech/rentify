<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('agent.properties.show', $property) }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Unit {{ $unit->unit_number }} &mdash; {{ $property->name }}</h2>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $property->address }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('agent.properties.units.edit', [$property, $unit]) }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                Edit Unit
            </a>
        </div>
    </x-slot>

    <div class="space-y-8">
        {{-- Unit Details Card --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Unit Details</h3>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Rent Amount</dt>
                        <dd class="mt-1.5 text-lg font-bold text-gray-900">KSh {{ number_format($unit->rent_amount, 2) }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Deposit</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $unit->deposit_amount ? 'KSh ' . number_format($unit->deposit_amount, 2) : 'N/A' }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Size</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $unit->size ?? 'N/A' }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Status</dt>
                        <dd class="mt-1.5">
                            <x-status-badge :status="$unit->status" />
                        </dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4 sm:col-span-2 lg:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Current Tenant</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $unit->activeLease?->tenant?->user?->name ?? 'Vacant' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Recent Maintenance Requests --}}
        @if($unit->maintenanceRequests->count() > 0)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Maintenance Requests</h3>
                        <p class="mt-0.5 text-sm text-gray-500">Recent maintenance activity for this unit</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                        {{ $unit->maintenanceRequests->count() }}
                    </span>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Priority</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($unit->maintenanceRequests->sortByDesc('created_at')->take(10) as $request)
                            <tr class="transition-colors hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ Str::limit($request->description, 60) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <x-status-badge :status="$request->priority" />
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <x-status-badge :status="$request->status" />
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $request->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <a href="{{ route('agent.maintenance.show', $request) }}"
                                       class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
