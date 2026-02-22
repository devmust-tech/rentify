<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Record Payment</h2>
            <a href="{{ route('agent.payments.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Payment Information</h3>
                <p class="mt-1 text-sm text-gray-500">Record a new payment against an outstanding invoice.</p>
            </div>

            <form action="{{ route('agent.payments.store') }}" method="POST" class="px-6 py-6">
                @csrf

                <div class="space-y-6">
                    {{-- Invoice --}}
                    <div>
                        <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-1.5">Invoice</label>
                        <select name="invoice_id" id="invoice_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select an invoice</option>
                            @foreach($invoices as $invoice)
                                <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                    {{ $invoice->lease->tenant->user->name }} - KSh {{ number_format($invoice->amount, 2) }} (Due: {{ $invoice->due_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
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

                    {{-- Payment Method --}}
                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Method</label>
                        <select name="method" id="method" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select a method</option>
                            @foreach(App\Enums\PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}" {{ old('method') == $method->value ? 'selected' : '' }}>
                                    {{ $method->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('method') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Reference --}}
                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-700 mb-1.5">Reference / Transaction ID</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="e.g. TXN-123456">
                        @error('reference') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- M-Pesa Receipt --}}
                    <div>
                        <label for="mpesa_receipt" class="block text-sm font-medium text-gray-700 mb-1.5">M-Pesa Receipt <span class="text-gray-400 font-normal">(if applicable)</span></label>
                        <input type="text" name="mpesa_receipt" id="mpesa_receipt" value="{{ old('mpesa_receipt') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="e.g. QHK7Y8X9Z0">
                        @error('mpesa_receipt') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Record Payment
                    </button>
                    <a href="{{ route('agent.payments.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
