<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Landlord Statement</h2>
                <p class="mt-0.5 text-sm text-gray-500">{{ $landlord->user->name }} ({{ $landlord->user->email }})</p>
            </div>
            <a href="{{ route('agent.reports.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Back to Reports</a>
        </div>
    </x-slot>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <x-stats-card title="Total Income" :value="'KSh ' . number_format($totalIncome, 2)" color="green" icon="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
        <x-stats-card title="Pending Amount" :value="'KSh ' . number_format($totalPending, 2)" color="yellow" icon="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        <x-stats-card title="Properties" :value="count($properties)" color="indigo" icon="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
    </div>

    {{-- Properties Breakdown --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Properties Summary</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/30">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Units</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Occupied</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Income Collected</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Pending</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($properties as $prop)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $prop->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $prop->units }}</td>
                        <td class="px-6 py-4 text-sm text-emerald-600 font-medium">{{ $prop->occupied }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KSh {{ number_format($prop->income, 2) }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-amber-600">KSh {{ number_format($prop->pending, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td class="px-6 py-4 text-sm font-bold text-gray-900" colspan="3">Total</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">KSh {{ number_format($totalIncome, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-amber-600">KSh {{ number_format($totalPending, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Payment Details --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Recent Payments</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50/30">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tenant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property / Unit</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $hasPayments = false; @endphp
                @foreach($landlord->properties as $property)
                    @foreach($property->units as $unit)
                        @foreach($unit->leases as $lease)
                            @foreach($lease->invoices as $invoice)
                                @foreach($invoice->payments->where('status.value', 'completed') as $payment)
                                    @php $hasPayments = true; @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->paid_at?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $lease->tenant->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $property->name }} / {{ $unit->unit_number }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-emerald-600">KSh {{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$payment->method" /></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
                @if(!$hasPayments)
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No payments recorded yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
