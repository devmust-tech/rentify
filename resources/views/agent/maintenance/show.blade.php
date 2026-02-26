<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Maintenance Request Details</h2>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('agent.maintenance.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Requests
                </a>
                <a href="{{ route('agent.maintenance.edit', $maintenance) }}" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
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

    {{-- Notes Timeline --}}
    <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Notes History</h3>
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                    {{ $maintenance->notes->count() }} {{ Str::plural('note', $maintenance->notes->count()) }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            @if($maintenance->notes->count() > 0)
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
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No notes yet. Add the first note below.</p>
                </div>
            @endif

            {{-- Add Note Form --}}
            <div class="mt-6 border-t border-gray-100 pt-6">
                <form action="{{ route('agent.maintenance.add-note', $maintenance) }}" method="POST">
                    @csrf
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1.5">Add a Note</label>
                    <textarea name="note" id="note" rows="3"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                              placeholder="Write your update or note here..." required>{{ old('note') }}</textarea>
                    @error('note') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror

                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3.105 2.288a.75.75 0 0 0-.826.95l1.414 4.926A1.5 1.5 0 0 0 5.135 9.25h6.115a.75.75 0 0 1 0 1.5H5.135a1.5 1.5 0 0 0-1.442 1.086l-1.414 4.926a.75.75 0 0 0 .826.95 28.897 28.897 0 0 0 15.293-7.155.75.75 0 0 0 0-1.114A28.897 28.897 0 0 0 3.105 2.288Z" />
                            </svg>
                            Add Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
