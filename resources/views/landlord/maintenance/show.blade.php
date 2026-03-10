<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Maintenance Request Details</h2>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('landlord.maintenance.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Requests
                </a>
                <a href="{{ route('landlord.maintenance.edit', $maintenance) }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Update Request
                </a>
            </div>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Request Information</h3>
                <div class="flex items-center gap-x-2">
                    <x-status-badge :status="$maintenance->priority" />
                    <x-status-badge :status="$maintenance->status" />
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="sm:col-span-2 lg:col-span-3">
                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ $maintenance->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->unit->unit_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->unit->property->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tenant</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->tenant->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->assigned_to ?? 'Not assigned' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($maintenance->resolved_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Resolved At</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->resolved_at->format('d/m/Y H:i') }}</dd>
                    </div>
                @endif
                <div class="sm:col-span-2 lg:col-span-3">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $maintenance->description }}</dd>
                </div>
                @if($maintenance->resolution_notes)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Resolution Notes</dt>
                        <dd class="mt-2 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800 leading-relaxed ring-1 ring-emerald-100">{{ $maintenance->resolution_notes }}</dd>
                    </div>
                @endif
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

    {{-- Conversation Timeline --}}
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

        {{-- Chat area --}}
        <div class="px-6 py-6 space-y-4 min-h-[200px]">

            {{-- Opening system event --}}
            <div class="flex justify-center">
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-500">
                    Request opened · {{ $maintenance->created_at->format('d M Y, H:i') }}
                </span>
            </div>

            @if($maintenance->notes->count() > 0)
                @php $prevDate = null; @endphp
                @foreach($maintenance->notes->sortBy('created_at') as $note)
                    @php $noteDate = $note->created_at->format('Y-m-d'); @endphp

                    {{-- Date separator --}}
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

                    {{-- Message bubble --}}
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
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="rounded-full bg-gray-100 p-3 mb-3">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">No updates yet</p>
                    <p class="text-xs text-gray-400 mt-1">Add the first update below.</p>
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

        {{-- Compose box --}}
        <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
            <form action="{{ route('landlord.maintenance.add-note', $maintenance) }}" method="POST">
                @csrf
                <div class="flex items-end gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <textarea name="note" id="note" rows="2"
                                  class="block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition resize-none"
                                  placeholder="Write an update, resolution, or note…" required>{{ old('note') }}</textarea>
                        @error('note') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors shrink-0">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3.105 2.288a.75.75 0 00-.826.95l1.414 4.926A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.897 28.897 0 0015.293-7.155.75.75 0 000-1.114A28.897 28.897 0 003.105 2.288z"/></svg>
                        Post Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
