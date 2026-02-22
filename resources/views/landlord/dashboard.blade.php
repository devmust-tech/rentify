<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Landlord Dashboard</h2>
                <p class="mt-1 text-sm text-gray-500">Overview of your properties and income</p>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stats-card
            title="Properties"
            :value="$stats['total_properties']"
            color="indigo"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\"/></svg>'"
        />
        <x-stats-card
            title="Occupied Units"
            :value="$stats['occupied_units'] . '/' . $stats['total_units']"
            color="green"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\"/></svg>'"
        />
        <x-stats-card
            title="Total Income"
            :value="'KSh ' . number_format($stats['total_income'], 2)"
            color="blue"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'"
        />
        <x-stats-card
            title="Pending Amount"
            :value="'KSh ' . number_format($stats['pending_amount'], 2)"
            color="yellow"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'"
        />
    </div>

    <!-- Recent Payments -->
    <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div class="flex items-center gap-x-3">
                <div class="rounded-lg bg-emerald-100 p-2">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Recent Payments</h3>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentPayments as $payment)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center gap-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 font-semibold text-sm">
                            {{ strtoupper(substr($payment->invoice->lease->tenant->user->name ?? 'N', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $payment->invoice->lease->tenant->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->paid_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-emerald-600">+KSh {{ number_format($payment->amount, 2) }}</span>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="mt-2 text-sm font-medium text-gray-500">No recent payments</p>
                    <p class="mt-1 text-sm text-gray-400">Payments from your tenants will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
