@php
    $current = request()->routeIs('tenant.*') ? request()->route()->getName() : '';
    $unreadNotificationCount = auth()->user()->notifications()->unread()->count();
@endphp

{{-- Dashboard --}}
<div class="space-y-0.5">
    <a href="{{ route('tenant.dashboard') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
        Dashboard
    </a>
</div>

{{-- My Tenancy --}}
<div class="mt-6">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-600">My Tenancy</p>
    <div class="space-y-0.5">

        {{-- My Lease --}}
        <a href="{{ route('tenant.lease.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'lease') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            My Lease
        </a>

        {{-- Maintenance --}}
        <div x-data="{ open: {{ str_contains($current, 'maintenance') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'maintenance') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Maintenance
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('tenant.maintenance.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'tenant.maintenance.index' ? 'text-indigo-400 font-medium' : 'text-gray-500 hover:text-white' }}">All Requests</a>
                <a href="{{ route('tenant.maintenance.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'tenant.maintenance.create' ? 'text-indigo-400 font-medium' : 'text-gray-500 hover:text-white' }}">New Request</a>
            </div>
        </div>
    </div>
</div>

{{-- Billing --}}
<div class="mt-6">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-600">Billing</p>
    <div class="space-y-0.5">

        {{-- Invoices --}}
        <a href="{{ route('tenant.invoices.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'invoices') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
            Invoices
        </a>

        {{-- Payments --}}
        <a href="{{ route('tenant.payments.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'payments') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Payments
        </a>
    </div>
</div>

{{-- General --}}
<div class="mt-6">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-600">General</p>
    <div class="space-y-0.5">

        {{-- Notifications --}}
        <a href="{{ route('tenant.notifications.index') }}" class="group flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'notifications') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/8 hover:text-white' }}">
            <span class="flex items-center gap-x-3">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifications
            </span>
            @if($unreadNotificationCount > 0)
                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}</span>
            @endif
        </a>
    </div>
</div>
