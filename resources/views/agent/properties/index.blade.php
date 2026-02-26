<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Properties</h2>
                <p class="mt-1 text-sm text-gray-500">Manage all properties in your portfolio</p>
            </div>
            <a href="{{ route('agent.properties.create') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Add Property
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/50">
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">County</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Type</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Landlord</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Units</th>
                    <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($properties as $property)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('agent.properties.show', $property) }}"
                               class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                {{ $property->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property->county_name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <x-status-badge :status="$property->property_type" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property->landlord->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">
                                {{ $property->units->count() }} {{ Str::plural('unit', $property->units->count()) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="inline-flex items-center gap-x-3">
                                <a href="{{ route('agent.properties.show', $property) }}"
                                   class="font-medium text-gray-600 hover:text-gray-500 transition-colors" title="View">
                                    <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                                <a href="{{ route('agent.properties.edit', $property) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors" title="Edit">
                                    <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                <a href="{{ route('agent.properties.units.index', $property) }}"
                                   class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors">
                                    Units
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="rounded-full bg-gray-100 p-3 mb-4">
                                    <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">No properties yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding your first property.</p>
                                <a href="{{ route('agent.properties.create') }}"
                                   class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                    </svg>
                                    Add Property
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($properties->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
