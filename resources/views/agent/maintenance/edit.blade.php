<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <a href="{{ route('agent.maintenance.show', $maintenance) }}"
                       class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Update Maintenance Request</h2>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $maintenance->unit->property->name }} &mdash; Unit {{ $maintenance->unit->unit_number }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('agent.maintenance.show', $maintenance) }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Back to Request
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('agent.maintenance.update', $maintenance) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Request Information Card --}}
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
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-3">
                        <div class="rounded-lg bg-gray-50/50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Unit</dt>
                            <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $maintenance->unit->property->name }} &mdash; {{ $maintenance->unit->unit_number }}</dd>
                        </div>
                        <div class="rounded-lg bg-gray-50/50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Tenant</dt>
                            <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $maintenance->tenant->user->name }}</dd>
                        </div>
                        <div class="rounded-lg bg-gray-50/50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Submitted</dt>
                            <dd class="mt-1.5 text-sm font-medium text-gray-900">{{ $maintenance->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="sm:col-span-3 rounded-lg bg-gray-50/50 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Description</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $maintenance->description }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Update Fields --}}
            <fieldset class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Update Details</h3>
                    <p class="mt-0.5 text-sm text-gray-500">Change the status, priority, assignment, and resolution</p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    {{-- Status & Priority Row --}}
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                            <select name="status" id="status"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                    required>
                                @foreach(App\Enums\MaintenanceStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ old('status', $maintenance->status->value) == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Priority --}}
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority</label>
                            <select name="priority" id="priority"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                    required>
                                @foreach(App\Enums\MaintenancePriority::cases() as $priority)
                                    <option value="{{ $priority->value }}" {{ old('priority', $maintenance->priority->value) == $priority->value ? 'selected' : '' }}>
                                        {{ $priority->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Assigned To --}}
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1.5">Assigned To</label>
                        <input type="text" name="assigned_to" id="assigned_to"
                               value="{{ old('assigned_to', $maintenance->assigned_to) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="Contractor or technician name">
                        @error('assigned_to') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Add Update Note --}}
                    <div>
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-1.5">Add Update Note</label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="4"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                  placeholder="Add an update note about the work performed or status change...">{{ old('resolution_notes') }}</textarea>
                        <p class="mt-1.5 text-xs text-gray-500">This note will be added to the request's notes history.</p>
                        @error('resolution_notes') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-x-3 pt-2">
                <a href="{{ route('agent.maintenance.show', $maintenance) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    Update Request
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
