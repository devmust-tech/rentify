@php
    $current = request()->routeIs('agent.*') ? request()->route()->getName() : '';
    $unreadNotificationCount = auth()->user()->notifications()->unread()->count();
@endphp

<div class="space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route('agent.dashboard') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
        Dashboard
    </a>
</div>

<div class="mt-6">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Property Management</p>
    <div class="space-y-0.5">
        {{-- Properties --}}
        <div x-data="{ open: {{ str_contains($current, 'properties') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'properties') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Properties
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.properties.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.properties.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Properties</a>
                <a href="{{ route('agent.properties.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.properties.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">Add Property</a>
            </div>
        </div>

        {{-- Landlords --}}
        <div x-data="{ open: {{ str_contains($current, 'landlords') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'landlords') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Landlords
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.landlords.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.landlords.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Landlords</a>
                <a href="{{ route('agent.landlords.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.landlords.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">Add Landlord</a>
            </div>
        </div>

        {{-- Tenants --}}
        <div x-data="{ open: {{ str_contains($current, 'tenants') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'tenants') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Tenants
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.tenants.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.tenants.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Tenants</a>
                <a href="{{ route('agent.tenants.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.tenants.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">Add Tenant</a>
            </div>
        </div>

        {{-- Leases --}}
        <div x-data="{ open: {{ str_contains($current, 'leases') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'leases') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Leases
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.leases.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.leases.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Leases</a>
                <a href="{{ route('agent.leases.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.leases.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">Create Lease</a>
            </div>
        </div>

        {{-- Agreements --}}
        <div x-data="{ open: {{ str_contains($current, 'agreements') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'agreements') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Agreements
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.agreements.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.agreements.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Agreements</a>
                <a href="{{ route('agent.agreements.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.agreements.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">New Agreement</a>
            </div>
        </div>
    </div>
</div>

<div class="mt-6">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Finance</p>
    <div class="space-y-0.5">
        {{-- Invoices --}}
        <div x-data="{ open: {{ str_contains($current, 'invoices') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'invoices') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    Invoices
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.invoices.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.invoices.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Invoices</a>
                <a href="{{ route('agent.invoices.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.invoices.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">Create Invoice</a>
            </div>
        </div>

        {{-- Payments --}}
        <div x-data="{ open: {{ str_contains($current, 'payments') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'payments') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Payments
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.payments.index') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.payments.index' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">All Payments</a>
                <a href="{{ route('agent.payments.create') }}" class="block rounded-md px-3 py-1.5 text-sm transition {{ $current === 'agent.payments.create' ? 'text-indigo-400 font-medium' : 'text-gray-400 hover:text-white' }}">Record Payment</a>
            </div>
        </div>
    </div>
</div>

<div class="mt-6">
    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">Operations</p>
    <div class="space-y-0.5">
        {{-- Maintenance --}}
        <a href="{{ route('agent.maintenance.index') }}" class="group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'maintenance') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Maintenance
        </a>

        {{-- Reports --}}
        <div x-data="{ open: {{ str_contains($current, 'reports') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'reports') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reports
                </span>
                <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-0.5 border-l border-white/10 pl-4">
                <a href="{{ route('agent.reports.rent-roll') }}" class="block rounded-md px-3 py-1.5 text-sm text-gray-400 hover:text-white transition">Rent Roll</a>
                <a href="{{ route('agent.reports.arrears') }}" class="block rounded-md px-3 py-1.5 text-sm text-gray-400 hover:text-white transition">Arrears</a>
                <a href="{{ route('agent.reports.occupancy') }}" class="block rounded-md px-3 py-1.5 text-sm text-gray-400 hover:text-white transition">Occupancy</a>
            </div>
        </div>

        {{-- Notifications --}}
        <a href="{{ route('agent.notifications.index') }}" class="group flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 {{ str_contains($current, 'notifications') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
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
