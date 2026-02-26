<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('landlord.leases.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Lease Details</h2>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $lease->tenant->user->name }} - {{ $lease->unit->unit_number }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-x-3">
                @if($lease->status->value === 'pending')
                    @if($lease->signed_at)
                        <form action="{{ route('landlord.leases.approve', $lease) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Approve Lease
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-x-1.5 rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-700 ring-1 ring-inset ring-amber-600/10">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Awaiting Tenant Signature
                        </span>
                    @endif
                @endif
                <a href="{{ route('landlord.leases.edit', $lease) }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
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

    {{-- Invoices Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Invoices</h3>
                <a href="{{ route('landlord.invoices.create') }}" class="inline-flex items-center gap-x-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Invoice
                </a>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Due Date</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lease->invoices as $invoice)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$invoice->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('landlord.invoices.show', $invoice) }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
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
