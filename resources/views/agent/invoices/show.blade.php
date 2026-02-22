<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Invoice Details</h2>
            <a href="{{ route('agent.invoices.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Invoices
            </a>
        </div>
    </x-slot>

    {{-- Invoice Details Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Invoice Information</h3>
                <x-status-badge :status="$invoice->status" />
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tenant</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->lease->tenant->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->lease->unit->property->name }} - {{ $invoice->lease->unit->unit_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->due_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Invoice Amount</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">KSh {{ number_format($invoice->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Paid</dt>
                    <dd class="mt-1 text-lg font-bold text-emerald-600">KSh {{ number_format($invoice->payments->sum('amount'), 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Balance</dt>
                    <dd class="mt-1 text-lg font-bold {{ ($invoice->amount - $invoice->payments->sum('amount')) > 0 ? 'text-red-600' : 'text-gray-900' }}">
                        KSh {{ number_format($invoice->amount - $invoice->payments->sum('amount'), 2) }}
                    </dd>
                </div>
                @if($invoice->description)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-700 leading-relaxed">{{ $invoice->description }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Payments</h3>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Paid At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoice->payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600">KSh {{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->method->label() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->reference ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-500">No payments recorded</p>
                                <p class="mt-1 text-sm text-gray-400">Payments for this invoice will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
