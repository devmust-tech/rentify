<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Create Invoice</h2>
            <a href="{{ route('agent.invoices.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Invoices
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Invoice Details</h3>
                <p class="mt-1 text-sm text-gray-500">Create a new invoice for an active lease.</p>
            </div>

            <form action="{{ route('agent.invoices.store') }}" method="POST" class="px-6 py-6">
                @csrf

                <div class="space-y-6">
                    {{-- Lease --}}
                    <div>
                        <label for="lease_id" class="block text-sm font-medium text-gray-700 mb-1.5">Lease</label>
                        <select name="lease_id" id="lease_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select a lease</option>
                            @foreach($leases as $lease)
                                <option value="{{ $lease->id }}" {{ old('lease_id') == $lease->id ? 'selected' : '' }}>
                                    {{ $lease->tenant->user->name }} - {{ $lease->unit->property->name }} {{ $lease->unit->unit_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('lease_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Amount (KSh)</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-400 text-sm">KSh</span>
                            </div>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                        </div>
                        @error('amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                        @error('due_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="Optional description for this invoice...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Create Invoice
                    </button>
                    <a href="{{ route('agent.invoices.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
