<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $landlord->user->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">Landlord Profile</p>
            </div>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('agent.landlords.index') }}"
                   class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Landlords
                </a>
                <a href="{{ route('agent.landlords.edit', $landlord) }}"
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
        {{-- Landlord Details Card --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Landlord Details</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Email</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $landlord->user->email }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Phone</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $landlord->user->phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">National ID</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $landlord->national_id ?? 'N/A' }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Bank</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">
                            @if($landlord->payment_details['bank_name'] ?? null)
                                {{ $landlord->payment_details['bank_name'] }} &mdash; {{ $landlord->payment_details['bank_account'] ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">M-Pesa</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $landlord->payment_details['mpesa_number'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50/50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Total Properties</dt>
                        <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $landlord->properties->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Properties Table --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Properties</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Address</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Units</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($landlord->properties as $property)
                        <tr class="transition-colors hover:bg-gray-50/50">
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('agent.properties.show', $property) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                    {{ $property->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $property->address }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                    {{ $property->units->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('agent.properties.show', $property) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                                    </svg>
                                    <p class="mt-4 text-sm font-medium text-gray-900">No properties assigned</p>
                                    <p class="mt-1 text-sm text-gray-500">This landlord does not have any properties yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
