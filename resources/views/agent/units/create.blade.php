<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add Unit</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $property->name }}</p>
            </div>
            <a href="{{ route('agent.properties.units.index', $property) }}"
               class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Units
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <form action="{{ route('agent.properties.units.store', $property) }}" method="POST" class="p-6 sm:p-8">
                @csrf

                <div class="space-y-6">
                    {{-- Unit Number --}}
                    <div>
                        <label for="unit_number" class="block text-sm font-medium text-gray-700 mb-1.5">Unit Number</label>
                        <input type="text"
                               name="unit_number"
                               id="unit_number"
                               value="{{ old('unit_number') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               required
                               placeholder="e.g. A1, 101">
                        @error('unit_number') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rent Amount --}}
                    <div>
                        <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Rent (KES)</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-400 text-sm">KSh</span>
                            </div>
                            <input type="number"
                                   name="rent_amount"
                                   id="rent_amount"
                                   value="{{ old('rent_amount') }}"
                                   step="0.01"
                                   min="0"
                                   class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required
                                   placeholder="0.00">
                        </div>
                        @error('rent_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deposit Amount --}}
                    <div>
                        <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Deposit Amount (KES)</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-400 text-sm">KSh</span>
                            </div>
                            <input type="number"
                                   name="deposit_amount"
                                   id="deposit_amount"
                                   value="{{ old('deposit_amount') }}"
                                   step="0.01"
                                   min="0"
                                   class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="0.00">
                        </div>
                        @error('deposit_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Size --}}
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-1.5">Size</label>
                        <input type="text"
                               name="size"
                               id="size"
                               value="{{ old('size') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                               placeholder="e.g. 1BR, 2BR, Studio, Bedsitter">
                        @error('size') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit"
                            class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create Unit
                    </button>
                    <a href="{{ route('agent.properties.units.index', $property) }}"
                       class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
