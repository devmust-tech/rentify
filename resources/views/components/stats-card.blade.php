@props(['title', 'value', 'subtitle' => null, 'icon' => null, 'color' => 'blue', 'link' => null, 'trend' => null])

@php
    $colors = [
        'blue'   => ['icon' => 'bg-blue-500 text-white', 'orb' => 'bg-blue-50', 'stripe' => 'bg-blue-500', 'trend_up' => 'text-blue-600 bg-blue-50', 'trend_dn' => 'text-red-500 bg-red-50'],
        'green'  => ['icon' => 'bg-emerald-500 text-white', 'orb' => 'bg-emerald-50', 'stripe' => 'bg-emerald-500', 'trend_up' => 'text-emerald-600 bg-emerald-50', 'trend_dn' => 'text-red-500 bg-red-50'],
        'yellow' => ['icon' => 'bg-amber-500 text-white', 'orb' => 'bg-amber-50', 'stripe' => 'bg-amber-500', 'trend_up' => 'text-emerald-600 bg-emerald-50', 'trend_dn' => 'text-red-500 bg-red-50'],
        'red'    => ['icon' => 'bg-red-500 text-white', 'orb' => 'bg-red-50', 'stripe' => 'bg-red-500', 'trend_up' => 'text-emerald-600 bg-emerald-50', 'trend_dn' => 'text-red-500 bg-red-50'],
        'purple' => ['icon' => 'bg-purple-500 text-white', 'orb' => 'bg-purple-50', 'stripe' => 'bg-purple-500', 'trend_up' => 'text-emerald-600 bg-emerald-50', 'trend_dn' => 'text-red-500 bg-red-50'],
        'indigo' => ['icon' => 'bg-indigo-500 text-white', 'orb' => 'bg-indigo-50', 'stripe' => 'bg-indigo-500', 'trend_up' => 'text-indigo-600 bg-indigo-50', 'trend_dn' => 'text-red-500 bg-red-50'],
    ];
    $c = $colors[$color] ?? $colors['blue'];
    $trendPositive = $trend && str_starts_with($trend, '+');
    $trendNegative = $trend && str_starts_with($trend, '-');
@endphp

<div class="group relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
    {{-- Colored top accent stripe --}}
    <div class="absolute top-0 left-0 right-0 h-0.5 {{ $c['stripe'] }}"></div>

    <div class="p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1 space-y-1">
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">{{ $title }}</p>
                <p class="text-2xl font-bold tracking-tight text-gray-900 leading-snug">{{ $value }}</p>
                @if($subtitle)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $subtitle }}</p>
                @endif
                @if($trend)
                    <div class="flex items-center gap-1.5 pt-1.5">
                        @if($trendPositive)
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $c['trend_up'] }}">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                {{ $trend }}
                            </span>
                        @elseif($trendNegative)
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $c['trend_dn'] }}">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                {{ $trend }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">{{ $trend }}</span>
                        @endif
                        <span class="text-[11px] text-gray-400">vs last month</span>
                    </div>
                @endif
            </div>
            @if($icon)
                <div class="shrink-0 rounded-2xl p-3 {{ $c['icon'] }} shadow-md">
                    @if(str_starts_with(trim($icon), '<'))
                        {!! $icon !!}
                    @else
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                        </svg>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($link)
        <a href="{{ $link }}" class="absolute inset-0" aria-label="{{ $title }}"></a>
    @endif

    {{-- Decorative orb --}}
    <div class="pointer-events-none absolute -right-8 -bottom-8 h-32 w-32 rounded-full {{ $c['orb'] }} opacity-60 transition-transform duration-500 group-hover:scale-125"></div>
</div>
