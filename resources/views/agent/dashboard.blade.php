<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('agent.properties.create') }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Property
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stats-card
            title="Total Properties"
            :value="$stats['total_properties']"
            color="indigo"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\"/></svg>'"
            :link="route('agent.properties.index')"
        />
        <x-stats-card
            title="Occupied Units"
            :value="$stats['occupied_units'] . '/' . $stats['total_units']"
            color="green"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4\"/></svg>'"
        />
        <x-stats-card
            title="Total Collected"
            :value="'KSh ' . number_format($stats['total_collected'], 2)"
            color="blue"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'"
        />
        <x-stats-card
            title="Pending Invoices"
            :value="$stats['pending_invoices']"
            color="yellow"
            :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'"
            :link="route('agent.invoices.index')"
        />
    </div>

    <!-- Two Column Layout -->
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Payments -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-emerald-100 p-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Recent Payments</h3>
                </div>
                <a href="{{ route('agent.payments.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">View all</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentPayments as $payment)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 font-semibold text-sm">
                                {{ strtoupper(substr($payment->invoice->lease->tenant->user->name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->invoice->lease->tenant->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->paid_at?->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-emerald-600">+KSh {{ number_format($payment->amount, 2) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="mt-2 text-sm text-gray-500">No recent payments</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Overdue Invoices -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-red-100 p-2">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Overdue Invoices</h3>
                </div>
                <a href="{{ route('agent.invoices.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">View all</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($overdueInvoices as $invoice)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-700 font-semibold text-sm">
                                {{ strtoupper(substr($invoice->lease->tenant->user->name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $invoice->lease->tenant->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-red-500 font-medium">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-red-600">KSh {{ number_format($invoice->amount, 2) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="mt-2 text-sm text-gray-500">No overdue invoices - all clear!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <a href="{{ route('agent.properties.create') }}" class="group flex flex-col items-center gap-y-2 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md hover:ring-indigo-500/20 transition-all duration-200">
                <div class="rounded-xl bg-indigo-50 p-3 group-hover:bg-indigo-100 transition">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition">Add Property</span>
            </a>
            <a href="{{ route('agent.leases.create') }}" class="group flex flex-col items-center gap-y-2 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md hover:ring-emerald-500/20 transition-all duration-200">
                <div class="rounded-xl bg-emerald-50 p-3 group-hover:bg-emerald-100 transition">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-600 transition">New Lease</span>
            </a>
            <a href="{{ route('agent.invoices.create') }}" class="group flex flex-col items-center gap-y-2 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md hover:ring-blue-500/20 transition-all duration-200">
                <div class="rounded-xl bg-blue-50 p-3 group-hover:bg-blue-100 transition">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition">Create Invoice</span>
            </a>
            <a href="{{ route('agent.payments.create') }}" class="group flex flex-col items-center gap-y-2 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md hover:ring-amber-500/20 transition-all duration-200">
                <div class="rounded-xl bg-amber-50 p-3 group-hover:bg-amber-100 transition">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-sm font-medium text-gray-700 group-hover:text-amber-600 transition">Record Payment</span>
            </a>
        </div>
    </div>
</x-app-layout>
