<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Add Landlord</h2>
            <a href="{{ route('agent.landlords.index') }}"
               class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Landlords
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <form action="{{ route('agent.landlords.store') }}" method="POST" class="divide-y divide-gray-100">
                @csrf

                {{-- Personal Information --}}
                <fieldset class="p-6 sm:p-8">
                    <legend class="text-base font-semibold text-gray-900">Personal Information</legend>
                    <p class="mt-1 text-sm text-gray-500">Basic details about the landlord.</p>

                    <div class="mt-6 space-y-6">
                        {{-- Full Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required
                                   placeholder="John Doe">
                            @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required
                                   placeholder="landlord@example.com">
                            @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   required
                                   placeholder="0712 345 678">
                            @error('phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Invitation Notice --}}
                        <div class="rounded-lg bg-blue-50 p-3.5 ring-1 ring-blue-200">
                            <div class="flex items-start gap-2.5">
                                <svg class="h-5 w-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <p class="text-sm text-blue-700">An invitation email will be sent so the landlord can set their own password and activate their account.</p>
                            </div>
                        </div>

                        {{-- National ID --}}
                        <div>
                            <label for="national_id" class="block text-sm font-medium text-gray-700 mb-1.5">National ID</label>
                            <input type="text"
                                   name="national_id"
                                   id="national_id"
                                   value="{{ old('national_id') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 12345678">
                            @error('national_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Payment Details --}}
                <fieldset class="p-6 sm:p-8">
                    <legend class="text-base font-semibold text-gray-900">Payment Details</legend>
                    <p class="mt-1 text-sm text-gray-500">Bank and mobile money information for rent disbursement.</p>

                    <div class="mt-6 space-y-6">
                        {{-- Bank Name --}}
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1.5">Bank Name</label>
                            <input type="text"
                                   name="bank_name"
                                   id="bank_name"
                                   value="{{ old('bank_name') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. KCB, Equity, Co-operative">
                            @error('bank_name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Bank Account --}}
                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-1.5">Bank Account Number</label>
                            <input type="text"
                                   name="bank_account"
                                   id="bank_account"
                                   value="{{ old('bank_account') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="Account number">
                            @error('bank_account') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- M-Pesa Number --}}
                        <div>
                            <label for="mpesa_number" class="block text-sm font-medium text-gray-700 mb-1.5">M-Pesa Number</label>
                            <input type="text"
                                   name="mpesa_number"
                                   id="mpesa_number"
                                   value="{{ old('mpesa_number') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition"
                                   placeholder="e.g. 0712 345 678">
                            @error('mpesa_number') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Actions --}}
                <div class="flex items-center gap-x-3 bg-gray-50/50 px-6 py-4 sm:px-8">
                    <button type="submit"
                            class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create Landlord
                    </button>
                    <a href="{{ route('agent.landlords.index') }}"
                       class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
