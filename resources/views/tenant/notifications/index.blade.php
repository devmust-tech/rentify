<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
        </div>
    </x-slot>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <a href="{{ route('tenant.notifications.show', $notification) }}" class="block overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-colors hover:bg-gray-50/50 {{ !$notification->read_at ? 'border-l-4 border-l-indigo-500' : '' }}">
                <div class="px-6 py-5">
                    <div class="flex items-start justify-between gap-x-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 {{ !$notification->read_at ? '' : 'text-gray-600' }}">
                                {{ $notification->subject ?? 'Notification' }}
                            </h3>
                            <p class="mt-1.5 text-sm text-gray-500 leading-relaxed line-clamp-2">
                                {{ $notification->message ?? '' }}
                            </p>
                            <p class="mt-2 text-xs text-gray-400">
                                <time>{{ $notification->created_at->diffForHumans() }}</time>
                            </p>
                        </div>
                        <div class="flex shrink-0 items-center gap-x-2">
                            @if(!$notification->read_at)
                                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-500">No notifications yet</p>
                        <p class="mt-1 text-sm text-gray-400">Notifications from your agent will appear here.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</x-app-layout>
