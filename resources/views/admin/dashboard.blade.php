<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Platform Overview</h1>
        <p class="mt-1 text-sm text-gray-500">Monitor all organizations and platform activity</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5 mb-8">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <p class="text-sm font-medium text-gray-500">Total Orgs</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalOrgs) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <p class="text-sm font-medium text-amber-600">Pending Approval</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ number_format($pendingOrgs) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <p class="text-sm font-medium text-emerald-600">Active Orgs</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">{{ number_format($activeOrgs) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <p class="text-sm font-medium text-gray-500">Total Users</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
            <p class="text-sm font-medium text-gray-500">Monthly Revenue</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">KES {{ number_format($monthlyRevenue, 0) }}</p>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900">Pending Approvals</h2>
            <a href="{{ route('admin.organizations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View all →</a>
        </div>

        @if($pendingOrgsList->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-400">No pending organizations.</div>
        @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/50">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/50">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/50">Subdomain</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/50">Submitted</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/50">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pendingOrgsList as $org)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $org->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->owner?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $org->slug }}.{{ config('app.domain') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $org->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.organizations.approve', $org) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500 transition">Approve</button>
                                </form>
                                <a href="{{ route('admin.organizations.show', $org) }}" class="ml-2 text-sm text-indigo-600 hover:text-indigo-500 font-medium">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin-layout>
