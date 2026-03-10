@props([
    'name',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmLabel' => 'Delete',
    'cancelLabel' => 'Cancel',
    'variant' => 'danger',
])

@php
$variants = [
    'danger'  => ['icon' => 'bg-red-100 text-red-600',  'btn' => 'bg-red-600 hover:bg-red-500 focus-visible:outline-red-600'],
    'warning' => ['icon' => 'bg-amber-100 text-amber-600', 'btn' => 'bg-amber-600 hover:bg-amber-500 focus-visible:outline-amber-600'],
    'info'    => ['icon' => 'bg-blue-100 text-blue-600',  'btn' => 'bg-blue-600 hover:bg-blue-500 focus-visible:outline-blue-600'],
];
$v = $variants[$variant] ?? $variants['danger'];
@endphp

<x-modal :name="$name" maxWidth="sm">
    <div class="p-6">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $v['icon'] }}">
                @if($variant === 'danger')
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                @else
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <button type="button"
                    @click="$dispatch('close-modal', '{{ $name }}')"
                    class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50 transition">
                {{ $cancelLabel }}
            </button>
            <button type="button"
                    @click="$dispatch('confirm-{{ $name }}'); $dispatch('close-modal', '{{ $name }}')"
                    class="rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 {{ $v['btn'] }}">
                {{ $confirmLabel }}
            </button>
        </div>
    </div>
</x-modal>
