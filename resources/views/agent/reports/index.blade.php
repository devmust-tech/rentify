<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">Reports</h2>
    </x-slot>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-stats-card title="Total Units" :value="$totalUnits" color="indigo" icon="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
        <x-stats-card title="Occupancy Rate" :value="$occupancyRate . '%'" :subtitle="$occupiedUnits . ' of ' . $totalUnits . ' occupied'" color="green" icon="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        <x-stats-card title="Total Collected" :value="'KSh ' . number_format($totalCollected, 2)" color="blue" icon="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
        <x-stats-card title="Total Arrears" :value="'KSh ' . number_format($totalArrears, 2)" color="red" icon="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
    </div>

    {{-- Report Links --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <a href="{{ route('agent.reports.rent-roll') }}" class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:ring-indigo-300 transition-all p-6">
            <div class="flex items-center gap-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 ring-1 ring-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Rent Roll</h3>
                    <p class="mt-0.5 text-sm text-gray-500">All units with rent amounts and tenants</p>
                </div>
            </div>
        </a>
        <a href="{{ route('agent.reports.arrears') }}" class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:ring-red-300 transition-all p-6">
            <div class="flex items-center gap-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 ring-1 ring-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Arrears Report</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Outstanding balances per tenant</p>
                </div>
            </div>
        </a>
        <a href="{{ route('agent.reports.occupancy') }}" class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:ring-emerald-300 transition-all p-6">
            <div class="flex items-center gap-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 ring-1 ring-emerald-100">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Occupancy Report</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Vacancy rates across properties</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Landlord Statements --}}
    @if($landlords->count())
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Landlord Statements</h3>
                <p class="mt-0.5 text-sm text-gray-500">Select a landlord to view their financial statement</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($landlords as $landlord)
                    <a href="{{ route('agent.reports.landlord-statement', $landlord) }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-600">
                                {{ substr($landlord->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $landlord->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $landlord->user->email }}</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</x-app-layout>
