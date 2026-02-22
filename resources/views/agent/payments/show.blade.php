<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Payment Details</h2>
            <a href="{{ route('agent.payments.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Payment Information</h3>
                <x-status-badge :status="$payment->status" />
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tenant</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $payment->invoice->lease->tenant->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property & Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $payment->invoice->lease->unit->property->name }} - {{ $payment->invoice->lease->unit->unit_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                    <dd class="mt-1 text-lg font-bold text-emerald-600">KSh {{ number_format($payment->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $payment->method->label() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Paid At</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $payment->paid_at?->format('d/m/Y H:i') ?? '-' }}</dd>
                </div>
                @if($payment->reference)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reference / Transaction ID</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 font-mono">{{ $payment->reference }}</dd>
                    </div>
                @endif
                @if($payment->mpesa_receipt)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">M-Pesa Receipt</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 font-mono">{{ $payment->mpesa_receipt }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Invoice Summary --}}
        <div class="border-t border-gray-100 bg-gray-50/30 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Related Invoice</p>
                    <p class="mt-0.5 text-sm text-gray-700">KSh {{ number_format($payment->invoice->amount, 2) }} &mdash; Due {{ $payment->invoice->due_date->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('agent.invoices.show', $payment->invoice) }}" class="font-medium text-sm text-indigo-600 hover:text-indigo-500 transition-colors">
                    View Invoice
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
