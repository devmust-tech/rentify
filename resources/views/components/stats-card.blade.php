@props(['title', 'value', 'subtitle' => null, 'icon' => null, 'color' => 'blue', 'link' => null, 'trend' => null])

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-50', 'icon' => 'bg-blue-100 text-blue-600'],
        'green' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-100 text-emerald-600'],
        'yellow' => ['bg' => 'bg-amber-50', 'icon' => 'bg-amber-100 text-amber-600'],
        'red' => ['bg' => 'bg-red-50', 'icon' => 'bg-red-100 text-red-600'],
        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-100 text-purple-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'bg-indigo-100 text-indigo-600'],
    ];
    $c = $colors[$color] ?? $colors['blue'];
@endphp

<div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between">
        <div class="space-y-2">
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="rounded-xl p-3 {{ $c['icon'] }}">
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
    @if($link)
        <a href="{{ $link }}" class="absolute inset-0"></a>
    @endif
    <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full {{ $c['bg'] }} opacity-50"></div>
</div>
