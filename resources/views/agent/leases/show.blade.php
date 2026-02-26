<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Lease Details</h2>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('agent.leases.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Leases
                </a>
                <a href="{{ route('agent.leases.edit', $lease) }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Lease
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Lease Details Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Lease Information</h3>
                <x-status-badge :status="$lease->status" />
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tenant</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $lease->tenant->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $lease->unit->unit_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $lease->unit->property->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $lease->start_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $lease->end_date?->format('d/m/Y') ?? 'Open-ended' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Monthly Rent</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">KSh {{ number_format($lease->rent_amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Deposit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">KSh {{ number_format($lease->deposit, 2) }}</dd>
                </div>
                @if($lease->terms)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Terms & Conditions</dt>
                        <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $lease->terms }}</dd>
                    </div>
                @endif
            </dl>

            {{-- Signature Section --}}
            @if($lease->signed_at)
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">E-Signature</h4>
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Signed on {{ $lease->signed_at->format('d/m/Y \a\t H:i') }}
                            </span>
                        </div>
                        @if($lease->signature_url)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <img src="{{ asset('storage/' . $lease->signature_url) }}" alt="Tenant signature" class="h-16">
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Rent Negotiation Section --}}
    @if($lease->negotiations->count() > 0 || $lease->status->value === 'pending')
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-indigo-100 p-2">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Rent Negotiation</h3>
                        <p class="text-sm text-gray-500">Negotiation history with {{ $lease->tenant->user->name }}</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                {{-- Negotiation Thread --}}
                @if($lease->negotiations->count() > 0)
                    <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-1">
                        @foreach($lease->negotiations->sortBy('created_at') as $negotiation)
                            @php
                                $isAgent = $negotiation->proposed_by === auth()->id();
                            @endphp
                            <div class="flex {{ $isAgent ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[75%]">
                                    <div class="rounded-xl px-4 py-3 {{ $isAgent ? 'bg-indigo-600 text-white ring-indigo-700' : 'bg-gray-100 text-gray-900 ring-gray-200' }} ring-1">
                                        <div class="flex items-center gap-x-2 mb-1">
                                            <span class="text-xs font-semibold {{ $isAgent ? 'text-indigo-200' : 'text-gray-500' }}">
                                                {{ $isAgent ? 'You' : $negotiation->proposer->name . ' (Tenant)' }}
                                            </span>
                                            <x-status-badge :status="$negotiation->status" class="!text-[10px] !px-1.5 !py-0.5" />
                                        </div>
                                        <p class="text-sm font-bold {{ $isAgent ? 'text-white' : 'text-gray-900' }}">
                                            KSh {{ number_format($negotiation->proposed_rent, 2) }}
                                        </p>
                                        @if($negotiation->message)
                                            <p class="text-sm mt-1 {{ $isAgent ? 'text-indigo-100' : 'text-gray-600' }}">
                                                {{ $negotiation->message }}
                                            </p>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1 {{ $isAgent ? 'text-right' : 'text-left' }}">
                                        {{ $negotiation->created_at->format('d/m/Y H:i') }}
                                        @if($negotiation->responded_at)
                                            &middot; Responded {{ $negotiation->responded_at->format('d/m/Y H:i') }}
                                        @endif
                                    </p>

                                    {{-- Action Buttons for Pending Negotiations from Tenant --}}
                                    @if($negotiation->status->value === 'pending' && !$isAgent)
                                        <div class="mt-3" x-data="{ action: null, showCounterForm: false }">
                                            <div class="flex items-center gap-x-2">
                                                <form method="POST" action="{{ route('agent.leases.negotiations.respond', [$lease, $negotiation]) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-x-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Accept
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('agent.leases.negotiations.respond', [$lease, $negotiation]) }}" class="inline"
                                                      x-data="{ rejectMsg: '' }">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-x-1.5 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 transition-colors">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Reject
                                                    </button>
                                                </form>

                                                <button type="button"
                                                        @click="showCounterForm = !showCounterForm"
                                                        class="inline-flex items-center gap-x-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                    Counter
                                                </button>
                                            </div>

                                            {{-- Counter-Offer Form --}}
                                            <div x-show="showCounterForm" x-transition x-cloak class="mt-3">
                                                <form method="POST" action="{{ route('agent.leases.negotiations.respond', [$lease, $negotiation]) }}" class="rounded-xl bg-white p-4 ring-1 ring-gray-200 shadow-sm">
                                                    @csrf
                                                    <input type="hidden" name="action" value="counter">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Counter Rent (KSh)</label>
                                                            <input type="number"
                                                                   name="counter_rent"
                                                                   step="0.01"
                                                                   min="0"
                                                                   value="{{ old('counter_rent', $lease->rent_amount) }}"
                                                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                                   required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Message (Optional)</label>
                                                            <textarea name="message"
                                                                      rows="2"
                                                                      maxlength="1000"
                                                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                                      placeholder="Explain your counter-offer..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 flex items-center gap-x-2">
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-x-1.5 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                                            Send Counter-Offer
                                                        </button>
                                                        <button type="button" @click="showCounterForm = false"
                                                                class="rounded-lg bg-white px-3.5 py-2 text-xs font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200">
                        <p class="text-sm text-gray-500 text-center">No rent negotiations yet. The tenant may propose a different rent amount.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Invoices Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Invoices</h3>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Due Date</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lease->invoices as $invoice)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$invoice->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-500">No invoices yet</p>
                                <p class="mt-1 text-sm text-gray-400">Invoices for this lease will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
