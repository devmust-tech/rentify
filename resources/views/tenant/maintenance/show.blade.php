<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Maintenance Request Details</h2>
            <a href="{{ route('tenant.maintenance.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Requests
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">{{ $maintenance->title }}</h3>
                <div class="flex items-center gap-x-2">
                    <x-status-badge :status="$maintenance->priority" />
                    <x-status-badge :status="$maintenance->status" />
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-status-badge :status="$maintenance->status" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1"><x-status-badge :status="$maintenance->priority" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->created_at->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->unit->property->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->unit->unit_number }}</dd>
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $maintenance->description }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($maintenance->photos && count($maintenance->photos))
        <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Photos</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($maintenance->photos as $photo)
                        <a href="{{ Storage::url($photo) }}" target="_blank" class="group relative overflow-hidden rounded-lg ring-1 ring-gray-200 hover:ring-indigo-300 transition-all">
                            <img src="{{ Storage::url($photo) }}" alt="Maintenance photo" class="h-40 w-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Notes Timeline (read-only for tenant) --}}
    @if($maintenance->notes->count() > 0)
        <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Updates</h3>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                        {{ $maintenance->notes->count() }} {{ Str::plural('update', $maintenance->notes->count()) }}
                    </span>
                </div>
            </div>

            <div class="px-6 py-6">
                <div class="relative">
                    {{-- Timeline line --}}
                    <div class="absolute left-4 top-2 bottom-0 w-0.5 bg-gray-200"></div>

                    <div class="space-y-6">
                        @foreach($maintenance->notes->sortBy('created_at') as $note)
                            <div class="relative flex gap-x-4">
                                {{-- Timeline dot --}}
                                <div class="relative flex h-8 w-8 flex-none items-center justify-center">
                                    <div class="h-2.5 w-2.5 rounded-full bg-indigo-500 ring-2 ring-white"></div>
                                </div>

                                {{-- Note content --}}
                                <div class="flex-1 rounded-lg bg-gray-50 p-4 ring-1 ring-gray-100">
                                    <div class="flex items-center justify-between gap-x-4">
                                        <div class="flex items-center gap-x-2">
                                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-xs font-medium text-indigo-700">
                                                {{ strtoupper(substr($note->user->name, 0, 1)) }}
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $note->user->name }}</span>
                                        </div>
                                        <time class="flex-none text-xs text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</time>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $note->note }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
