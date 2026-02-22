<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Arrears Report</h2>
            <a href="{{ route('agent.reports.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Back to Reports</a>
        </div>
    </x-slot>

    {{-- Summary --}}
    <div class="mb-8">
        <x-stats-card title="Total Outstanding Arrears" :value="'KSh ' . number_format($totalArrears, 2)" color="red" icon="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Outstanding Invoices</h3>
            <p class="mt-0.5 text-sm text-gray-500">{{ $overdueInvoices->count() }} unpaid invoice(s)</p>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/30">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property / Unit</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Invoice Amount</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Paid</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Balance</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Due Date</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($overdueInvoices as $invoice)
                    @php
                        $paid = $invoice->payments->where('status.value', 'completed')->sum('amount');
                        $balance = $invoice->amount - $paid;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $invoice->lease->tenant->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->lease->unit->property->name }} / {{ $invoice->lease->unit->unit_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">KSh {{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-emerald-600">KSh {{ number_format($paid, 2) }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-red-600">KSh {{ number_format($balance, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$invoice->status" /></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                <p class="mt-2 text-sm font-medium text-gray-900">No outstanding arrears</p>
                                <p class="mt-1 text-sm text-gray-500">All invoices are paid up.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
