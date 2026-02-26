<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create your account</h1>
        <p class="mt-2 text-sm text-gray-500">Join Rentify and start managing your properties like a pro.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('role', '') }}', showPassword: false, showConfirm: false }">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
            <div class="mt-1.5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4.5 w-4.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="auth-input block w-full rounded-lg border border-gray-300 bg-gray-50/50 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500 focus:outline-none"
                    placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <div class="mt-1.5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4.5 w-4.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                    class="auth-input block w-full rounded-lg border border-gray-300 bg-gray-50/50 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500 focus:outline-none"
                    placeholder="you@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Phone --}}
        <div class="mt-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
            <div class="mt-1.5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4.5 w-4.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </div>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                    class="auth-input block w-full rounded-lg border border-gray-300 bg-gray-50/50 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500 focus:outline-none"
                    placeholder="+254712345678" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
        </div>

        {{-- Role Selector (Card Toggle) --}}
        <div class="mt-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Register as</label>
            <input type="hidden" name="role" :value="role" />
            <div class="grid grid-cols-2 gap-3">
                {{-- Agent Card --}}
                <button type="button" @click="role = 'agent'"
                    :class="role === 'agent'
                        ? 'border-indigo-500 bg-indigo-50/80 ring-2 ring-indigo-500/20'
                        : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'"
                    class="relative flex flex-col items-center gap-2 rounded-xl border-2 p-4 transition-all duration-200 cursor-pointer group">
                    <div :class="role === 'agent' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200'"
                        class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <p :class="role === 'agent' ? 'text-indigo-900' : 'text-gray-700'"
                            class="text-sm font-semibold transition-colors">Agent</p>
                        <p class="text-xs text-gray-400 mt-0.5">Manage properties for landlords</p>
                    </div>
                    {{-- Check indicator --}}
                    <div x-show="role === 'agent'" x-transition.scale class="absolute top-2 right-2">
                        <div class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                </button>

                {{-- Landlord Card --}}
                <button type="button" @click="role = 'landlord'"
                    :class="role === 'landlord'
                        ? 'border-indigo-500 bg-indigo-50/80 ring-2 ring-indigo-500/20'
                        : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'"
                    class="relative flex flex-col items-center gap-2 rounded-xl border-2 p-4 transition-all duration-200 cursor-pointer group">
                    <div :class="role === 'landlord' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200'"
                        class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <p :class="role === 'landlord' ? 'text-indigo-900' : 'text-gray-700'"
                            class="text-sm font-semibold transition-colors">Landlord</p>
                        <p class="text-xs text-gray-400 mt-0.5">Own and rent out properties</p>
                    </div>
                    {{-- Check indicator --}}
                    <div x-show="role === 'landlord'" x-transition.scale class="absolute top-2 right-2">
                        <div class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                </button>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1.5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4.5 w-4.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="new-password"
                    class="auth-input block w-full rounded-lg border border-gray-300 bg-gray-50/50 py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500 focus:outline-none"
                    placeholder="Create a strong password" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg x-show="!showPassword" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Confirm Password --}}
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
            <div class="mt-1.5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4.5 w-4.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" required autocomplete="new-password"
                    class="auth-input block w-full rounded-lg border border-gray-300 bg-gray-50/50 py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500 focus:outline-none"
                    placeholder="Confirm your password" />
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg x-show="!showConfirm" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="showConfirm" x-cloak class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        {{-- Submit --}}
        <div class="mt-7">
            <button type="submit"
                class="relative w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/25 hover:from-indigo-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                Create account
            </button>
        </div>

        {{-- Login link --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Sign in</a>
        </p>
    </form>
</x-guest-layout>
