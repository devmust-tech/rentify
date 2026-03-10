<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                <p class="mt-1 text-sm text-gray-500">Stay updated on your properties and tenants</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4" data-notification-feed>
        @include('landlord.notifications.partials.list', ['notifications' => $notifications])
    </div>

    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</x-app-layout>
