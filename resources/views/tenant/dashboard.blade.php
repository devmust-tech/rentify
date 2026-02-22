<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tenant Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</p>
            </div>
        </div>
    </x-slot>

    @if($activeLease)
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <x-stats-card
                title="Monthly Rent"
                :value="'KSh ' . number_format($stats['rent_amount'] ?? 0, 2)"
                color="indigo"
                :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4\"/></svg>'"
            />
            <x-stats-card
                title="Next Due"
                :value="$stats['next_invoice'] ? 'KSh ' . number_format($stats['next_invoice']->amount, 2) : 'None'"
                :subtitle="$stats['next_invoice'] ? 'Due: ' . $stats['next_invoice']->due_date->format('d/m/Y') : ''"
                color="yellow"
                :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'"
            />
            <x-stats-card
                title="Total Paid"
                :value="'KSh ' . number_format($stats['total_paid'] ?? 0, 2)"
                color="green"
                :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg>'"
            />
            <x-stats-card
                title="Open Requests"
                :value="$stats['pending_maintenance'] ?? 0"
                color="red"
                :icon="'<svg class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085\"/></svg>'"
            />
        </div>

        <!-- Current Lease Card -->
        <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-indigo-100 p-2">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Current Lease</h3>
                </div>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Property</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->unit->property->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->unit->unit_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lease Period</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->start_date->format('d/m/Y') }} - {{ $activeLease->end_date?->format('d/m/Y') ?? 'Open' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-emerald-100 p-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Recent Payments</h3>
                </div>
                <a href="{{ route('tenant.payments.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View all</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentPayments as $payment)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 font-semibold text-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->invoice->description ?? 'Rent' }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->paid_at?->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-emerald-600">+KSh {{ number_format($payment->amount, 2) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="mt-2 text-sm font-medium text-gray-500">No recent payments</p>
                        <p class="mt-1 text-sm text-gray-400">Your payment history will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="px-6 py-16 text-center">
                <div class="flex flex-col items-center">
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="mt-2 text-sm font-medium text-gray-500">No active lease found</p>
                    <p class="mt-1 text-sm text-gray-400">Please contact your agent for lease assignment.</p>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
