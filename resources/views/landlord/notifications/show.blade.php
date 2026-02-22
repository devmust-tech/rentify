<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('landlord.notifications.index') }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Notification</h2>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('landlord.notifications.index') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Notifications
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-8">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/10">
                        {{ $notification->type instanceof \BackedEnum ? ucfirst(str_replace('_', ' ', $notification->type->value)) : $notification->type }}
                    </span>
                    <span class="inline-flex items-center gap-x-1 rounded-lg bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                        <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                        Read
                    </span>
                </div>
            </div>

            <div class="px-6 py-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ $notification->subject }}</h3>

                <div class="mt-4 rounded-lg bg-gray-50/50 p-5">
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $notification->message }}</p>
                </div>

                <div class="mt-6 border-t border-gray-100 pt-5">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50/50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Received</dt>
                            <dd class="mt-1.5 text-sm font-medium text-gray-900">
                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                        @if($notification->read_at)
                            <div class="rounded-lg bg-gray-50/50 p-4">
                                <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Read</dt>
                                <dd class="mt-1.5 text-sm font-medium text-gray-900">
                                    {{ $notification->read_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
