<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Payments</h2>
            <a href="{{ route('agent.payments.create') }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Record Payment
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Unit</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Paid At</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm">
                            <span class="font-medium text-gray-900">{{ $payment->invoice->lease->tenant->user->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->invoice->lease->unit->unit_number }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-emerald-600">KSh {{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->method->label() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->reference ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('agent.payments.show', $payment) }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-500">No payments found</p>
                                <p class="mt-1 text-sm text-gray-400">Get started by recording a new payment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($payments->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
