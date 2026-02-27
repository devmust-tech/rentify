<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight">Dashboard</h2>
                <p class="mt-0.5 text-sm text-gray-500">Overview of your properties and income</p>
            </div>
            <a href="{{ route('landlord.properties.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-600/25 hover:bg-indigo-500 hover:-translate-y-px active:translate-y-0 transition-all duration-150">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Property
            </a>
        </div>
    </x-slot>

    {{-- Stats Grid --}}
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

    {{-- Revenue Chart --}}
    @php
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[now()->subMonths($i)->format('M')] = 0;
        }
        foreach($recentPayments as $payment) {
            $monthKey = $payment->paid_at?->format('M');
            if ($monthKey !== null && array_key_exists($monthKey, $months)) {
                $months[$monthKey] += $payment->amount;
            }
        }
        $chartLabels = json_encode(array_keys($months));
        $chartData   = json_encode(array_values($months));
    @endphp

    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <h3 class="text-base font-semibold text-gray-900">Income Overview</h3>
                <p class="mt-0.5 text-sm text-gray-500">Rental income trend over the last 6 months</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-100">
                KSh {{ number_format($stats['total_income'], 0) }} total
            </span>
        </div>
        <div class="px-6 pt-4 pb-6">
            <canvas id="incomeChart" height="90"></canvas>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div class="flex items-center gap-x-3">
                <div class="rounded-xl bg-emerald-100 p-2.5">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Recent Payments</h3>
                    <p class="text-xs text-gray-400">Latest rent collections from tenants</p>
                </div>
            </div>
            <a href="{{ route('landlord.payments.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">
                View all
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentPayments as $payment)
                <div class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50/70 transition-colors odd:bg-white even:bg-gray-50/30">
                    <div class="flex items-center gap-x-3">
                        <div class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 font-bold text-sm ring-2 ring-white">
                            {{ strtoupper(substr($payment->invoice->lease->tenant->user->name ?? 'N', 0, 1)) }}
                            <span class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $payment->invoice->lease->tenant->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ $payment->paid_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100">+KSh {{ number_format($payment->amount, 2) }}</span>
                </div>
            @empty
                <div class="px-6 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-medium text-gray-500">No recent payments</p>
                    <p class="mt-1 text-xs text-gray-400">Payments from your tenants will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('incomeChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const ctx = canvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(59,130,246,0.22)');
    gradient.addColorStop(0.6, 'rgba(59,130,246,0.05)');
    gradient.addColorStop(1, 'rgba(59,130,246,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $chartLabels !!},
            datasets: [{
                label: 'Income',
                data: {!! $chartData !!},
                borderColor: 'rgba(59,130,246,1)',
                backgroundColor: gradient,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(59,130,246,1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    bodyFont: { weight: '700', size: 13, family: 'Inter' },
                    titleFont: { size: 11, family: 'Inter' },
                    padding: { x: 14, y: 10 },
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: (ctx) => ' KSh ' + Number(ctx.parsed.y).toLocaleString('en-KE', { minimumFractionDigits: 2 }),
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11, family: 'Inter' },
                        padding: 8,
                        callback: (v) => v >= 1000 ? 'KSh ' + (v / 1000).toFixed(0) + 'K' : 'KSh ' + v
                    },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 11, family: 'Inter' }, padding: 6 },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
</x-app-layout>
