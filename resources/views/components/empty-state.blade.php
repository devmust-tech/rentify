@props(['title' => 'No data found', 'message' => null, 'action' => null, 'actionUrl' => null])

<div class="text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
    </svg>
    <h3 class="mt-2 text-sm font-semibold text-gray-900">{{ $title }}</h3>
    @if($message)
        <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>
    @endif
    @if($action && $actionUrl)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                {{ $action }}
            </a>
        </div>
    @endif
</div>
