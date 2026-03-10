<x-admin-layout>
    <x-slot name="title">Organizations</x-slot>

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Organizations</h1>
            <p class="mt-1 text-sm text-gray-500">All registered client organizations</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Organization</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Owner</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Users</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Created</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($organizations as $org)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($org->logo)
                                    <img src="{{ Storage::url($org->logo) }}" alt="" class="h-8 w-8 rounded-lg object-cover">
                                @else
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg text-white text-sm font-bold" style="background-color: {{ $org->primary_color }}">
                                        {{ strtoupper(substr($org->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $org->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $org->slug }}.{{ config('app.domain') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $org->owner?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $org->users_count }}</td>
                        <td class="px-6 py-4"><x-status-badge :status="$org->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $org->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm flex items-center justify-end gap-2">
                            <a href="{{ route('admin.organizations.show', $org) }}" class="font-medium text-indigo-600 hover:text-indigo-500">View</a>
                            @if($org->status->value === 'pending')
                                <form method="POST" action="{{ route('admin.organizations.approve', $org) }}" class="inline">
                                    @csrf
                                    <button class="font-medium text-emerald-600 hover:text-emerald-500">Approve</button>
                                </form>
                            @elseif($org->status->value === 'active')
                                <form method="POST" action="{{ route('admin.organizations.suspend', $org) }}" class="inline">
                                    @csrf
                                    <button class="font-medium text-red-600 hover:text-red-500">Suspend</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">No organizations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($organizations->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $organizations->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
