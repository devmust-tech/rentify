@props(['title' => 'No data found', 'message' => null, 'action' => null, 'actionUrl' => null, 'iconColor' => 'text-gray-400'])

<div class="text-center py-16 px-6">
    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50">
        <svg class="h-10 w-10 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    </div>
    <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
    @if($message)
        <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">{{ $message }}</p>
    @endif
    @if($action && $actionUrl)
        <div class="mt-8">
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-md transition-all duration-200">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                {{ $action }}
            </a>
        </div>
    @endif
</div>
