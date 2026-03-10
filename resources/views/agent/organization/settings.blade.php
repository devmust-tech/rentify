<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">Workspace Settings</h2>
    </x-slot>

    <div class="max-w-2xl space-y-8">

        @if(auth()->id() !== $org->owner_id)
            <div class="rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200 text-sm text-amber-700">
                Only the workspace owner can modify these settings.
            </div>
        @else

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Branding & Identity</h3>
            </div>
            <form method="POST" action="{{ route('agent.settings.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Organization Name</label>
                    <input type="text" name="name" value="{{ old('name', $org->name) }}" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Primary Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="primary_color" value="{{ old('primary_color', $org->primary_color) }}"
                                class="h-10 w-12 rounded-lg border-gray-300 shadow-sm cursor-pointer">
                            <input type="text" id="primary_color_text" value="{{ old('primary_color', $org->primary_color) }}"
                                class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm font-mono bg-gray-50" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Accent Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="accent_color" value="{{ old('accent_color', $org->accent_color) }}"
                                class="h-10 w-12 rounded-lg border-gray-300 shadow-sm cursor-pointer">
                            <input type="text" id="accent_color_text" value="{{ old('accent_color', $org->accent_color) }}"
                                class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm font-mono bg-gray-50" readonly>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo</label>
                    @if($org->logo)
                        <div class="mb-3">
                            <img src="{{ Storage::url($org->logo) }}" alt="Current logo" class="h-16 w-auto rounded-lg ring-1 ring-gray-200">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full rounded-lg border-gray-300 shadow-sm text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG up to 2MB</p>
                    @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Timezone</label>
                        <input type="text" name="timezone" value="{{ old('timezone', $org->settings['timezone'] ?? 'Africa/Nairobi') }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', $org->settings['currency'] ?? 'KES') }}"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Agent Commission Rate (%)
                        <span class="text-xs text-gray-400 font-normal ml-1">— auto-calculated on every recorded payment</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="commission_rate"
                            value="{{ old('commission_rate', $org->settings['commission_rate'] ?? 0) }}"
                            min="0" max="100" step="0.01"
                            class="w-32 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition">
                        <span class="text-sm text-gray-500">%</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Set to 0 to disable. Example: 5 = 5% of each payment recorded as commission.</p>
                    @error('commission_rate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        @endif
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('input[type="color"]').forEach(picker => {
            const textId = picker.name + '_text';
            const text = document.getElementById(textId);
            picker.addEventListener('input', () => { if (text) text.value = picker.value; });
        });
    </script>
    @endpush
</x-app-layout>
