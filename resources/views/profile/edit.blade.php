<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Profile Settings</h2>
            <p class="mt-1 text-sm text-gray-500">Manage your account information, password, and preferences.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-gray-900/5">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-gray-900/5">
            @include('profile.partials.update-password-form')
        </div>

        <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-gray-900/5">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
