<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Invoice Details</h2>
            <a href="{{ route('tenant.invoices.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
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
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->description ?? 'Rent' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Invoice Amount</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">KSh {{ number_format($invoice->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Balance</dt>
                    <dd class="mt-1 text-lg font-bold {{ $invoice->balance > 0 ? 'text-red-600' : 'text-emerald-600' }}">KSh {{ number_format($invoice->balance, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->due_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-status-badge :status="$invoice->status" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property / Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->lease->unit->property->name }} / {{ $invoice->lease->unit->unit_number }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- M-PESA Payment --}}
    @if($invoice->balance > 0)
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8" x-data="{ showPayForm: false }">
            <div class="px-6 py-6">
                <button @click="showPayForm = !showPayForm" class="inline-flex items-center gap-x-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pay via M-PESA
                </button>
                <div x-show="showPayForm" x-cloak class="mt-6">
                    <form action="{{ route('tenant.payments.initiate', $invoice) }}" method="POST" class="max-w-sm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">M-PESA Phone Number</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0712345678" required>
                        </div>
                        <div class="mb-4 rounded-lg bg-gray-50 px-4 py-3">
                            <p class="text-sm text-gray-500">Amount: <span class="font-bold text-gray-900">KSh {{ number_format($invoice->balance, 2) }}</span></p>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send STK Push
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

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
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoice->payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600">KSh {{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$payment->method" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->reference ?? $payment->mpesa_receipt ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$payment->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
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
