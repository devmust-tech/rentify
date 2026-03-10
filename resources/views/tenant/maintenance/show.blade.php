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

    {{-- Update History (read-only for tenant) --}}
    <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                    <h3 class="text-base font-semibold text-gray-900">Update History</h3>
                </div>
                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-100">
                    {{ $maintenance->notes->count() }} {{ Str::plural('update', $maintenance->notes->count()) }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6 space-y-4 min-h-[160px]">
            <div class="flex justify-center">
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-500">
                    Request opened · {{ $maintenance->created_at->format('d M Y, H:i') }}
                </span>
            </div>

            @if($maintenance->notes->count() > 0)
                @php $prevDate = null; @endphp
                @foreach($maintenance->notes->sortBy('created_at') as $note)
                    @php $noteDate = $note->created_at->format('Y-m-d'); @endphp
                    @if($noteDate !== $prevDate)
                        <div class="flex items-center gap-3 my-2">
                            <div class="flex-1 h-px bg-gray-100"></div>
                            <span class="text-xs text-gray-400 shrink-0">
                                @if($note->created_at->isToday()) Today
                                @elseif($note->created_at->isYesterday()) Yesterday
                                @else {{ $note->created_at->format('d M Y') }}
                                @endif
                            </span>
                            <div class="flex-1 h-px bg-gray-100"></div>
                        </div>
                        @php $prevDate = $noteDate; @endphp
                    @endif

                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 ring-2 ring-white shadow-sm">
                            {{ strtoupper(substr($note->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 max-w-2xl">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-gray-900">{{ $note->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $note->created_at->format('H:i') }}</span>
                                <span class="text-xs text-gray-300">·</span>
                                <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="rounded-2xl rounded-tl-sm bg-gray-50 px-4 py-3 ring-1 ring-gray-100">
                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $note->note }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <p class="text-sm text-gray-400">No updates from the team yet. Check back soon.</p>
                </div>
            @endif

            @if($maintenance->resolved_at)
                <div class="flex justify-center">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Resolved · {{ $maintenance->resolved_at->format('d M Y, H:i') }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
