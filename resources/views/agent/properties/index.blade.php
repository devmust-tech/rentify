<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Properties</h2>
                <p class="mt-1 text-sm text-gray-500">Manage all properties in your portfolio</p>
            </div>
            <a href="{{ route('agent.properties.create') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Add Property
            </a>
        </div>
    </x-slot>

    {{-- Search --}}
    <form method="GET" action="{{ route('agent.properties.index') }}" class="mb-4">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search properties..."
                   class="w-full max-w-sm rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition-colors">Search</button>
            @if(request('search'))
                <a href="{{ route('agent.properties.index') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Clear</a>
            @endif
        </div>
    </form>

    <div x-data="{ selected: [], toggleAll(ids) { this.selected = this.selected.length === ids.length ? [] : [...ids]; } }">

    {{-- Bulk Action Bar --}}
    <div x-show="selected.length > 0" x-transition
         class="mb-3 flex items-center justify-between rounded-xl bg-indigo-600 px-5 py-3 shadow-lg">
        <span class="text-sm font-semibold text-white" x-text="selected.length + ' propert' + (selected.length === 1 ? 'y' : 'ies') + ' selected'"></span>
        <form method="POST" action="{{ route('agent.properties.bulk-delete') }}" x-ref="bulkDeleteForm"
              @confirm-bulk-delete.window="$el.submit()">
            @csrf @method('DELETE')
            <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
            <button type="button" @click="$dispatch('open-modal', 'bulk-delete')"
                    class="inline-flex items-center gap-x-1.5 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Selected
            </button>
        </form>
        <x-confirm-modal name="bulk-delete" title="Delete Properties"
            :message="'The selected properties and all their units will be permanently removed.'"
            confirmLabel="Delete Properties" />
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/50">
                    <th scope="col" class="w-10 px-4 py-3.5">
                        <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               @click="toggleAll({{ json_encode($properties->pluck('id')->values()) }})">
                    </th>
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
                    <tr class="transition-colors hover:bg-gray-50/50" :class="selected.includes('{{ $property->id }}') ? 'bg-indigo-50/40' : ''">
                        <td class="w-10 px-4 py-4">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   value="{{ $property->id }}" x-model="selected">
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('agent.properties.show', $property) }}"
                               class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                {{ $property->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property->county_name }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$property->property_type" /></td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property->landlord->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">
                                {{ $property->units->count() }} {{ Str::plural('unit', $property->units->count()) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="inline-flex items-center gap-x-3">
                                <a href="{{ route('agent.properties.show', $property) }}" class="font-medium text-gray-600 hover:text-gray-500 transition-colors" title="View">
                                    <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                </a>
                                <a href="{{ route('agent.properties.edit', $property) }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors" title="Edit">
                                    <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                                </a>
                                <a href="{{ route('agent.properties.units.index', $property) }}" class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors">Units</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="rounded-full bg-gray-100 p-3 mb-4">
                                    <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">No properties yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding your first property.</p>
                                <a href="{{ route('agent.properties.create') }}" class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">Add Property</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($properties->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $properties->links() }}</div>
        @endif
    </div>
    </div>{{-- end x-data --}}
</x-app-layout>
