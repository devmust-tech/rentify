<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Create Lease</h2>
            <a href="{{ route('agent.leases.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Leases
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl" x-data="leaseForm()">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Lease Information</h3>
                <p class="mt-1 text-sm text-gray-500">Fill in the details below to create a new lease agreement.</p>
            </div>

            <form action="{{ route('agent.leases.store') }}" method="POST" class="px-6 py-6">
                @csrf

                <div class="space-y-6">
                    {{-- Tenant --}}
                    <div>
                        <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-1.5">Tenant</label>
                        <select name="tenant_id" id="tenant_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select a tenant</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->user->name }} ({{ $tenant->user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('tenant_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Unit --}}
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1.5">Unit</label>
                        <select name="unit_id" id="unit_id" x-model="selectedUnitId" @change="onUnitChange()" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select a unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->property->name }} - {{ $unit->unit_number }} (KSh {{ number_format($unit->rent_amount, 2) }}/mo)
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
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
                            <p class="mt-1 text-xs text-gray-400">Leave blank for open-ended lease</p>
                            @error('end_date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Amount Fields --}}
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Rent (KSh)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 text-sm">KSh</span>
                                </div>
                                <input type="number" name="rent_amount" id="rent_amount" x-model="rentAmount" value="{{ old('rent_amount') }}" step="0.01" min="0" class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            </div>
                            <p x-show="autoLoaded" x-cloak class="mt-1 text-xs text-emerald-600">Auto-loaded from unit</p>
                            @error('rent_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="deposit" class="block text-sm font-medium text-gray-700 mb-1.5">Deposit (KSh)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 text-sm">KSh</span>
                                </div>
                                <input type="number" name="deposit" id="deposit" x-model="depositAmount" value="{{ old('deposit') }}" step="0.01" min="0" class="block w-full rounded-lg border-gray-300 pl-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            </div>
                            @error('deposit') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div>
                        <label for="terms" class="block text-sm font-medium text-gray-700 mb-1.5">Terms & Conditions</label>
                        <textarea name="terms" id="terms" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="Enter lease terms and conditions...">{{ old('terms') }}</textarea>
                        @error('terms') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Create Lease
                    </button>
                    <a href="{{ route('agent.leases.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function leaseForm() {
            const units = @json($units->mapWithKeys(fn($u) => [$u->id => ['rent' => $u->rent_amount, 'deposit' => $u->deposit_amount]]));
            return {
                selectedUnitId: '{{ old('unit_id', '') }}',
                rentAmount: '{{ old('rent_amount', '') }}',
                depositAmount: '{{ old('deposit', '') }}',
                autoLoaded: false,
                onUnitChange() {
                    const unit = units[this.selectedUnitId];
                    if (unit) {
                        this.rentAmount = parseFloat(unit.rent).toFixed(2);
                        this.depositAmount = parseFloat(unit.deposit).toFixed(2);
                        this.autoLoaded = true;
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
