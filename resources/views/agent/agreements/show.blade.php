<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-3">
                <a href="{{ route('agent.agreements.index') }}"
                   class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Agreement Details</h2>
                    <p class="mt-0.5 text-sm text-gray-500">Agreement with {{ $agreement->landlord->user->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-x-3">
                @if($agreement->status->value === 'pending' && !$agreement->signed_at)
                    <span class="inline-flex items-center gap-x-1.5 rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-700 ring-1 ring-inset ring-amber-600/10">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Awaiting Landlord Signature
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- Agreement Details Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Agreement Information</h3>
                <x-status-badge :status="$agreement->status" />
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Landlord</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $agreement->landlord->user->name }}</dd>
                    <dd class="text-xs text-gray-500">{{ $agreement->landlord->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">{{ $agreement->commission_rate }}%</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Day</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">Day {{ $agreement->payment_day }} of each month</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $agreement->start_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $agreement->end_date?->format('d/m/Y') ?? 'Open-ended' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1.5"><x-status-badge :status="$agreement->status" /></dd>
                </div>
                @if($agreement->terms)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Terms & Conditions</dt>
                        <dd class="mt-2 rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200">
                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $agreement->terms }}</p>
                        </dd>
                    </div>
                @endif
            </dl>

            {{-- Signature Section --}}
            @if($agreement->signed_at)
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Landlord Signature</h4>
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Signed on {{ $agreement->signed_at->format('d/m/Y \a\t H:i') }}
                            </span>
                        </div>
                        @if($agreement->signature_url)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <img src="{{ asset('storage/' . $agreement->signature_url) }}" alt="Landlord signature" class="h-16">
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
