<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Invoice Details</h2>
            <div class="flex items-center gap-x-3">
                <a href="{{ route('tenant.invoices.download', $invoice) }}"
                   class="inline-flex items-center gap-x-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download PDF
                </a>
                <a href="{{ route('tenant.invoices.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Invoices
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Invoice Details Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Invoice Information</h3>
                <x-status-badge :status="$invoice->status" />
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                @if($invoice->invoice_number)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                    <dd class="mt-1 font-mono text-sm font-semibold text-gray-900">{{ $invoice->invoice_number }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->description ?? 'Rent' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Invoice Amount</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">KSh {{ number_format($invoice->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Balance</dt>
                    <dd class="mt-1 text-lg font-bold {{ $invoice->balance > 0 ? 'text-red-600' : 'text-emerald-600' }}">KSh {{ number_format($invoice->balance, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->due_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-status-badge :status="$invoice->status" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property / Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $invoice->lease->unit->property->name }} / {{ $invoice->lease->unit->unit_number }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- M-Pesa payment polling — shown after STK push is sent --}}
    @if(session('pending_payment_id'))
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8"
         x-data="paymentPoller('{{ route('tenant.payments.status', session('pending_payment_id')) }}')"
         x-init="start()">
        <div class="border-b border-gray-100 bg-amber-50 px-6 py-4 flex items-center gap-3">
            <svg x-show="status === 'pending'" class="h-5 w-5 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <svg x-show="status === 'completed'" x-cloak class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg x-show="status === 'failed'" x-cloak class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-gray-900"
                   x-text="status === 'pending' ? 'Waiting for M-Pesa confirmation...' : (status === 'completed' ? 'Payment confirmed!' : 'Payment failed or cancelled')"></p>
                <p class="text-xs text-gray-500 mt-0.5"
                   x-show="status === 'pending'">Check your phone and enter your M-Pesa PIN. This will update automatically.</p>
                <p class="text-xs text-emerald-600 mt-0.5"
                   x-show="status === 'completed' && receipt" x-cloak x-text="'Receipt: ' + receipt"></p>
                <p class="text-xs text-red-500 mt-0.5"
                   x-show="status === 'failed'" x-cloak>Please try again or contact support.</p>
            </div>
            <button x-show="status !== 'pending'" x-cloak @click="$el.closest('[x-data]').remove()"
                class="ml-auto text-xs text-gray-400 hover:text-gray-600">Dismiss</button>
        </div>
    </div>
    <script>
    function paymentPoller(url) {
        return {
            status: 'pending',
            receipt: null,
            timer: null,
            start() {
                this.poll();
            },
            async poll() {
                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    this.status = data.status;
                    this.receipt = data.receipt;
                    if (data.status === 'pending') {
                        this.timer = setTimeout(() => this.poll(), 4000);
                    } else if (data.status === 'completed') {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                } catch (e) {
                    this.timer = setTimeout(() => this.poll(), 5000);
                }
            }
        }
    }
    </script>
    @endif

    {{-- Payment Options --}}
    @if($invoice->balance > 0)
        @if(request('checkout') === 'cancelled')
            <div class="mb-4 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200 text-sm text-amber-700">Checkout was cancelled. Your current balance is unchanged.</div>
        @endif

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8"
             x-data="{ method: 'mpesa' }">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Make a Payment</h3>
            </div>
            <div class="px-6 py-6">
                {{-- Method selector --}}
                <div class="flex gap-3 mb-6">
                    <button type="button" @click="method = 'mpesa'"
                        :class="method === 'mpesa' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                        class="flex-1 rounded-xl border-2 px-4 py-3 text-sm font-semibold transition-all text-center">
                        M-Pesa
                    </button>
                    @if(config('services.stripe.key'))
                    <button type="button" @click="method = 'card'"
                        :class="method === 'card' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                        class="flex-1 rounded-xl border-2 px-4 py-3 text-sm font-semibold transition-all text-center">
                        Card (Stripe)
                    </button>
                    @endif
                </div>

                {{-- M-Pesa form --}}
                <div x-show="method === 'mpesa'">
                    <form action="{{ route('tenant.payments.initiate', $invoice) }}" method="POST" class="max-w-sm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">M-Pesa Phone Number</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0712345678" required>
                        </div>
                        <input type="hidden" name="amount" value="{{ $invoice->balance }}">
                        <div class="mb-4 rounded-lg bg-gray-50 px-4 py-3">
                            <p class="text-sm text-gray-500">Amount: <span class="font-bold text-gray-900">KSh {{ number_format($invoice->balance, 2) }}</span></p>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send STK Push
                        </button>
                    </form>
                </div>

                {{-- Card (Stripe) form --}}
                @if(config('services.stripe.key'))
                <div x-show="method === 'card'" x-cloak>
                    <div class="mb-4 rounded-lg bg-gray-50 px-4 py-3">
                        <p class="text-sm text-gray-500">Amount: <span class="font-bold text-gray-900">KSh {{ number_format($invoice->balance, 2) }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">You will be redirected to Stripe to complete payment securely.</p>
                    </div>
                    <form action="{{ route('tenant.invoices.card.checkout', $invoice) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Pay with Card
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Payments Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">Payments</h3>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoice->payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600">KSh {{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$payment->method" /></td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->reference ?? $payment->mpesa_receipt ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :status="$payment->status" /></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                </svg>
                                <p class="mt-2 text-sm font-medium text-gray-500">No payments recorded</p>
                                <p class="mt-1 text-sm text-gray-400">Payments for this invoice will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
