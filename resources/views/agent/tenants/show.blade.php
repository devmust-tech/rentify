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
                {{-- Send / resend invitation email --}}
                <form method="POST" action="{{ route('agent.tenants.invite', $tenant) }}"
                    @confirm-send-invite.window="$el.submit()">
                    @csrf
                    <button type="button" @click="$dispatch('open-modal', 'send-invite')"
                        class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        Send Invite
                    </button>
                </form>
                <x-confirm-modal name="send-invite" title="Send Invitation"
                    message="An invitation email will be sent to {{ $tenant->user->email }} with a link to set their password and activate their account."
                    confirmLabel="Send Invite" variant="info" />
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
                    @php $ec = is_array($tenant->emergency_contact) ? $tenant->emergency_contact : json_decode($tenant->emergency_contact, true); @endphp
                    <div class="overflow-hidden rounded-xl border border-orange-100 sm:col-span-2">
                        <div class="flex items-center gap-3 border-b border-orange-100 bg-orange-50 px-4 py-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-orange-100">
                                <svg class="h-3.5 w-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-orange-800">Emergency Contact</p>
                        </div>
                        <div class="bg-orange-50/30 px-4 py-4">
                            @if($ec && ($ec['name'] ?? null))
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div class="flex items-start gap-2.5">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Full Name</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900">{{ $ec['name'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2.5">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Phone Number</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900">{{ $ec['phone'] ?? '—' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2.5">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Relationship</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900">{{ $ec['relationship'] ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-orange-500/70">No emergency contact provided.</p>
                            @endif
                        </div>
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
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $lease->end_date?->format('d M Y') ?? 'Open-ended' }}</td>
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
