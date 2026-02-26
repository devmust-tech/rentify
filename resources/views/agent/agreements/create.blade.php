<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Create Agreement</h2>
            <a href="{{ route('agent.agreements.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Agreements
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Agreement Information</h3>
                <p class="mt-1 text-sm text-gray-500">Fill in the details below to create a new landlord-agent agreement.</p>
            </div>

            <form action="{{ route('agent.agreements.store') }}" method="POST" class="px-6 py-6">
                @csrf

                <div class="space-y-6">
                    {{-- Landlord --}}
                    <div>
                        <label for="landlord_id" class="block text-sm font-medium text-gray-700 mb-1.5">Landlord</label>
                        <select name="landlord_id" id="landlord_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select a landlord</option>
                            @foreach($landlords as $landlord)
                                <option value="{{ $landlord->id }}" {{ old('landlord_id') == $landlord->id ? 'selected' : '' }}>
                                    {{ $landlord->user->name }} ({{ $landlord->user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('landlord_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Commission Rate & Payment Day --}}
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1.5">Commission Rate (%)</label>
                            <div class="relative">
                                <input type="number" name="commission_rate" id="commission_rate" value="{{ old('commission_rate') }}" step="0.01" min="0" max="100" class="block w-full rounded-lg border-gray-300 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required placeholder="e.g. 10.00">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Percentage of rent the agent takes as commission</p>
                            @error('commission_rate') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="payment_day" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Day</label>
                            <select name="payment_day" id="payment_day" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                                <option value="">Select day of month</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ old('payment_day') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Day of the month the landlord receives payment</p>
                            @error('payment_day') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Date Fields --}}
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            @error('start_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">End Date <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                            <p class="mt-1 text-xs text-gray-400">Leave blank for an open-ended agreement</p>
                            @error('end_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div>
                        <label for="terms" class="block text-sm font-medium text-gray-700 mb-1.5">Terms & Conditions</label>
                        <textarea name="terms" id="terms" rows="6" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="Enter agreement terms and conditions...">{{ old('terms') }}</textarea>
                        @error('terms') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Create Agreement
                    </button>
                    <a href="{{ route('agent.agreements.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
