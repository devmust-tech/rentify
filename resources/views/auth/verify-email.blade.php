<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="mx-auto w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-5">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5-1.885a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75a2.25 2.25 0 012.25-2.25h15a2.25 2.25 0 012.25 2.25v9.128z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Check your email</h1>
        <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-sm mx-auto">
            Thanks for signing up! We've sent a verification link to your email address. Please click the link to activate your account.
        </p>
    </div>

    {{-- Success Status --}}
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-green-700 font-medium">
                A new verification link has been sent to the email address you provided during registration.
            </p>
        </div>
    @endif

    {{-- Resend form --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
            class="relative w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/25 hover:from-indigo-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
            </svg>
            Resend verification email
        </button>
    </form>

    {{-- Divider --}}
    <div class="mt-6 relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="bg-white px-3 text-gray-400">or</span>
        </div>
    </div>

    {{-- Log out --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
            </svg>
            Sign out
        </button>
    </form>
</x-guest-layout>
