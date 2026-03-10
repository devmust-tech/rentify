<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">Billing & Plan</h2>
    </x-slot>

    @if(request('checkout') === 'success')
        <div class="mb-6 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-200 text-sm text-emerald-700 flex items-center gap-3">
            <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Payment successful! Your plan has been upgraded.
        </div>
    @elseif(request('checkout') === 'cancelled')
        <div class="mb-6 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200 text-sm text-amber-700">
            Checkout was cancelled. Your current plan is unchanged.
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 p-4 ring-1 ring-red-200 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="max-w-3xl space-y-6">

        {{-- Current Plan Card --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Current Subscription</h3>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Plan</dt>
                        <dd class="mt-1 text-lg font-bold text-indigo-600 capitalize">{{ $plan }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'active'   => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    'trialing' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                    'past_due' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    'canceled' => 'bg-red-50 text-red-700 ring-red-200',
                                ];
                                $sc = $org->isOnTrial() ? 'trialing' : ($subStatus ?? 'inactive');
                                $color = $statusColors[$sc] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $color }}">
                                {{ $org->isOnTrial() ? 'Trial' : ucfirst(str_replace('_', ' ', $sc)) }}
                            </span>
                        </dd>
                    </div>
                    @if($org->isOnTrial())
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Trial Ends</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $trialEndsAt->format('d M Y') }}
                                <span class="text-xs text-amber-600">({{ $org->trialDaysLeft() }} days left)</span>
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Features</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ count($org->features ?? []) }} active</dd>
                    </div>
                </dl>

                @if($org->isOnTrial())
                    <div class="mt-4 rounded-lg bg-amber-50 p-4 ring-1 ring-amber-100 text-sm text-amber-800">
                        <strong>Your trial ends in {{ $org->trialDaysLeft() }} days.</strong>
                        Subscribe to a plan below to keep access after your trial.
                    </div>
                @elseif($subStatus === 'past_due')
                    <div class="mt-4 rounded-lg bg-red-50 p-4 ring-1 ring-red-100 text-sm text-red-800">
                        <strong>Payment overdue.</strong> Please update your payment method to avoid service interruption.
                    </div>
                @endif

                @if($org->stripe_customer_id)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <form method="POST" action="{{ route($portalRoute ?? 'agent.billing.portal') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Manage subscription &amp; payment method
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Plan Selector --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Choose a Plan</h3>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($plans as $key => $p)
                        <div class="relative rounded-xl border-2 p-5 flex flex-col gap-3 transition-all
                            {{ $plan === $key ? 'border-indigo-500 bg-indigo-50/50 shadow-md' : 'border-gray-200 bg-white hover:border-gray-300' }}">

                            @if($plan === $key)
                                <span class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center rounded-full bg-indigo-600 px-3 py-0.5 text-xs font-bold text-white shadow">Current Plan</span>
                            @endif

                            <div>
                                <h4 class="text-base font-bold text-gray-900">{{ $p['label'] }}</h4>
                                <p class="text-lg font-bold text-indigo-600 mt-0.5">{{ $p['price'] }}</p>
                            </div>

                            <ul class="space-y-1.5 text-xs text-gray-600 flex-1">
                                @foreach(\App\Models\Organization::planFeatures($key) as $feature)
                                    <li class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ ucfirst($feature) }}
                                    </li>
                                @endforeach
                            </ul>

                            @if($plan !== $key)
                                <form method="POST" action="{{ route($checkoutRoute ?? 'agent.billing.checkout', ['org' => $org->slug]) }}">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $key }}">
                                    <button type="submit"
                                        class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition mt-2">
                                        Subscribe to {{ $p['label'] }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                <p class="mt-4 text-xs text-gray-400 text-center">
                    Payments are securely processed by Stripe. Cancel anytime.
                    Need a custom plan? <a href="mailto:support@rentify.app" class="text-indigo-600 hover:underline">Contact us</a>.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
