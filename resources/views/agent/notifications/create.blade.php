<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Send Notification</h2>
            <a href="{{ route('agent.notifications.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Notifications
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Notification Details</h3>
                <p class="mt-1 text-sm text-gray-500">Compose and send a notification to a landlord or tenant.</p>
            </div>

            <form action="{{ route('agent.notifications.store') }}" method="POST" class="px-6 py-6">
                @csrf

                <div class="space-y-6">
                    {{-- Recipient --}}
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1.5">Recipient</label>
                        <select name="user_id" id="user_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="">Select a recipient</option>
                            <optgroup label="Landlords">
                                @foreach($landlords as $landlord)
                                    <option value="{{ $landlord->user->id }}" {{ old('user_id') == $landlord->user->id ? 'selected' : '' }}>
                                        {{ $landlord->user->name }} ({{ $landlord->user->email }})
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Tenants">
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->user->id }}" {{ old('user_id') == $tenant->user->id ? 'selected' : '' }}>
                                        {{ $tenant->user->name }} ({{ $tenant->user->email }})
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('user_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Notification Type</label>
                        <select name="type" id="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" required>
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="payment_reminder" {{ old('type') == 'payment_reminder' ? 'selected' : '' }}>Payment Reminder</option>
                            <option value="maintenance_update" {{ old('type') == 'maintenance_update' ? 'selected' : '' }}>Maintenance Update</option>
                            <option value="lease_expiry" {{ old('type') == 'lease_expiry' ? 'selected' : '' }}>Lease Expiry Notice</option>
                        </select>
                        @error('type') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="Enter notification subject..." required>
                        @error('subject') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                        <textarea name="message" id="message" rows="6" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition" placeholder="Write your notification message..." required>{{ old('message') }}</textarea>
                        @error('message') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex items-center gap-x-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        Send Notification
                    </button>
                    <a href="{{ route('agent.notifications.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
