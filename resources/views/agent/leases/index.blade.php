<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Leases</h2>
            <a href="{{ route('agent.leases.create') }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Lease
            </a>
        </div>
    </x-slot>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('agent.leases.index') }}" class="mb-4">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by tenant name..."
                   class="w-full max-w-xs rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <select name="status" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                <option value="terminated" @selected(request('status') === 'terminated')>Terminated</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition-colors">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('agent.leases.index') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Clear</a>
            @endif
        </div>
    </form>

    <div x-data="{ selected: [], toggleAll(ids) { this.selected = this.selected.length === ids.length ? [] : [...ids]; } }">

    {{-- Bulk Action Bar --}}
    <div x-show="selected.length > 0" x-transition
         class="mb-3 flex items-center justify-between rounded-xl bg-indigo-600 px-5 py-3 shadow-lg">
        <span class="text-sm font-semibold text-white" x-text="selected.length + ' lease' + (selected.length === 1 ? '' : 's') + ' selected'"></span>
        <form method="POST" action="{{ route('agent.leases.bulk-delete') }}" x-ref="bulkDeleteForm"
              @confirm-bulk-delete.window="$el.submit()">
            @csrf @method('DELETE')
            <template x-for="id in selected" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
            <button type="button" @click="$dispatch('open-modal', 'bulk-delete')"
                    class="inline-flex items-center gap-x-1.5 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Selected
            </button>
        </form>
        <x-confirm-modal name="bulk-delete" title="Delete Leases"
            :message="'The selected leases and their associated invoices will be permanently removed.'"
            confirmLabel="Delete Leases" />
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="w-10 bg-gray-50/50 px-4 py-3.5">
                        <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               @click="toggleAll({{ json_encode($leases->pluck('id')->values()) }})">
                    </th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Start Date</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">End Date</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rent</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leases as $lease)
                    <tr class="hover:bg-gray-50/50 transition-colors" :class="selected.includes('{{ $lease->id }}') ? 'bg-indigo-50/40' : ''">
                        <td class="w-10 px-4 py-4">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   value="{{ $lease->id }}" x-model="selected">
                        </td>
                        <td class="px-6 py-4 text-sm"><span class="font-medium text-gray-900">{{ $lease->tenant->user->name }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $lease->unit->unit_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $lease->unit->property->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $lease->start_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $lease->end_date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($lease->rent_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$lease->status" /></td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="inline-flex items-center gap-x-3">
                                <a href="{{ route('agent.leases.show', $lease) }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                                <a href="{{ route('agent.leases.edit', $lease) }}" class="font-medium text-emerald-600 hover:text-emerald-500 transition-colors">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-500">No leases found</p>
                                <p class="mt-1 text-sm text-gray-400">Get started by creating a new lease.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($leases->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $leases->links() }}</div>
        @endif
    </div>
    </div>{{-- end x-data --}}
</x-app-layout>
