<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 leading-tight">My Dashboard</h1>
                <p class="mt-0.5 text-sm text-gray-500">Welcome back, <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span></p>
            </div>
            @if($activeLease)
                <a href="{{ route('tenant.maintenance.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-600/25 hover:bg-indigo-500 hover:-translate-y-px active:translate-y-0 transition-all duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Request
                </a>
            @endif
        </div>
    </x-slot>

    @if($activeLease)
        {{-- Stats Grid --}}
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

        {{-- Current Lease Card --}}
        <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <div class="rounded-xl bg-indigo-100 p-2.5">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Current Lease</h3>
                            <p class="text-xs text-gray-400">Active tenancy details</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                </div>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="rounded-xl bg-gray-50 px-4 py-4 ring-1 ring-gray-100">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Property</dt>
                        <dd class="mt-1.5 text-sm font-bold text-gray-900">{{ $activeLease->unit->property->name }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 px-4 py-4 ring-1 ring-gray-100">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Unit</dt>
                        <dd class="mt-1.5 text-sm font-bold text-gray-900">{{ $activeLease->unit->unit_number }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 px-4 py-4 ring-1 ring-gray-100">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Lease Period</dt>
                        <dd class="mt-1.5 text-sm font-bold text-gray-900">{{ $activeLease->start_date->format('d/m/Y') }} – {{ $activeLease->end_date?->format('d/m/Y') ?? 'Open' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Two Column Layout --}}
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Recent Payments --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-x-3">
                        <div class="rounded-xl bg-emerald-100 p-2.5">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Recent Payments</h3>
                            <p class="text-xs text-gray-400">Your payment history</p>
                        </div>
                    </div>
                    <a href="{{ route('tenant.payments.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">
                        View all
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentPayments as $payment)
                        <div class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50/70 transition-colors odd:bg-white even:bg-gray-50/30">
                            <div class="flex items-center gap-x-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 ring-2 ring-white">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $payment->invoice->description ?? 'Rent' }}</p>
                                    <p class="text-xs text-gray-400">{{ $payment->paid_at?->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100">+KSh {{ number_format($payment->amount, 2) }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="mt-3 text-sm font-medium text-gray-500">No recent payments</p>
                            <p class="mt-1 text-xs text-gray-400">Your payment history will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-x-3">
                        <div class="rounded-xl bg-indigo-100 p-2.5">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                            <p class="text-xs text-gray-400">Common tasks at a glance</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 p-4">
                    <a href="{{ route('tenant.invoices.index') }}" class="group flex flex-col items-center gap-y-2.5 rounded-xl bg-gray-50 p-4 ring-1 ring-gray-100 hover:bg-indigo-50 hover:ring-indigo-200 transition-all duration-200">
                        <div class="rounded-xl bg-white p-2.5 shadow-sm group-hover:bg-indigo-100 transition-colors">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-indigo-700 transition-colors">View Invoices</span>
                    </a>
                    <a href="{{ route('tenant.maintenance.create') }}" class="group flex flex-col items-center gap-y-2.5 rounded-xl bg-gray-50 p-4 ring-1 ring-gray-100 hover:bg-amber-50 hover:ring-amber-200 transition-all duration-200">
                        <div class="rounded-xl bg-white p-2.5 shadow-sm group-hover:bg-amber-100 transition-colors">
                            <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-amber-700 transition-colors">Report Issue</span>
                    </a>
                    <a href="{{ route('tenant.lease.index') }}" class="group flex flex-col items-center gap-y-2.5 rounded-xl bg-gray-50 p-4 ring-1 ring-gray-100 hover:bg-emerald-50 hover:ring-emerald-200 transition-all duration-200">
                        <div class="rounded-xl bg-white p-2.5 shadow-sm group-hover:bg-emerald-100 transition-colors">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-emerald-700 transition-colors">My Lease</span>
                    </a>
                    <a href="{{ route('tenant.notifications.index') }}" class="group flex flex-col items-center gap-y-2.5 rounded-xl bg-gray-50 p-4 ring-1 ring-gray-100 hover:bg-purple-50 hover:ring-purple-200 transition-all duration-200">
                        <div class="rounded-xl bg-white p-2.5 shadow-sm group-hover:bg-purple-100 transition-colors">
                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-purple-700 transition-colors">Notifications</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        {{-- No Active Lease --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="px-6 py-20 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="mt-4 text-base font-semibold text-gray-700">No active lease found</p>
                <p class="mt-1.5 text-sm text-gray-400">Please contact your agent for lease assignment.</p>
            </div>
        </div>
    @endif
</x-app-layout>
