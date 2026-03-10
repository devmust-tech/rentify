<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Your Workspace — Rentify</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex">

        {{-- LEFT: Brand Panel (lg+) --}}
        <div class="hidden lg:flex relative flex-col justify-between bg-slate-900" style="width: 500px; min-width: 500px; padding: 40px 48px;">

            {{-- Logo --}}
            <div class="relative z-10">
                <a href="/" class="inline-flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight" style="color: #fff;">Rentify</span>
                </a>
            </div>

            {{-- Hero --}}
            <div class="relative z-10 max-w-sm">
                <h1 class="text-3xl font-extrabold leading-[1.2] tracking-tight" style="color: #fff;">
                    Your workspace,
                    <span style="color: #a5b4fc;">your rules.</span>
                </h1>
                <p class="mt-4 text-[15px] leading-relaxed" style="color: #94a3b8;">
                    Create a dedicated workspace for your agency or property portfolio. Invite your team, manage tenants, and collect rent — all under your own brand.
                </p>

                {{-- Steps --}}
                <div class="mt-10 space-y-5">
                    <div class="flex items-start gap-3.5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" style="background: rgba(99,102,241,0.2); color: #a5b4fc;">1</div>
                        <div>
                            <p class="text-sm font-medium" style="color: #e2e8f0;">Create your workspace</p>
                            <p class="text-xs mt-0.5" style="color: #64748b;">Pick a name and custom URL for your team</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" style="background: rgba(99,102,241,0.2); color: #a5b4fc;">2</div>
                        <div>
                            <p class="text-sm font-medium" style="color: #e2e8f0;">Add your properties</p>
                            <p class="text-xs mt-0.5" style="color: #64748b;">List buildings, units, and amenities</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" style="background: rgba(99,102,241,0.2); color: #a5b4fc;">3</div>
                        <div>
                            <p class="text-sm font-medium" style="color: #e2e8f0;">Start collecting rent</p>
                            <p class="text-xs mt-0.5" style="color: #64748b;">Automated invoices, M-Pesa payments, reminders</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trust --}}
            <div class="relative z-10 flex items-center gap-5" style="color: #475569; font-size: 12px;">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" style="color: #818cf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    256-bit SSL
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" style="color: #818cf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Free 14-day trial
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" style="color: #818cf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    Unlimited users
                </span>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="flex-1 flex flex-col min-h-screen bg-white">

            {{-- Mobile header --}}
            <div class="lg:hidden bg-slate-900 px-6 py-6 text-center">
                <a href="/" class="inline-flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight" style="color: #fff;">Rentify</span>
                </a>
            </div>

            {{-- Form area --}}
            <div class="flex-1 flex items-center justify-center px-6 py-10 sm:px-8 lg:px-12">
                <div class="w-full max-w-[480px]"
                    x-data="{
                        orgName: '{{ old('org_name') }}',
                        slug: '{{ old('slug') }}',
                        get previewSlug() {
                            return this.slug || this.orgName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                        }
                    }">

                    {{-- Header + nav --}}
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create your workspace</h1>
                            <p class="mt-1 text-sm text-gray-500">Setup takes less than a minute.</p>
                        </div>
                        <a href="{{ url('/') }}" class="hidden lg:inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                            Sign in
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                            <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('org.register') }}">
                        @csrf

                        {{-- Section: Workspace --}}
                        <div class="space-y-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Workspace</p>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Organization / Agency Name</label>
                                <input type="text" name="org_name" x-model="orgName" value="{{ old('org_name') }}" required
                                    placeholder="e.g. Sunrise Realty"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Workspace URL</label>
                                <div class="flex rounded-xl border border-gray-300 overflow-hidden shadow-sm focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition">
                                    <input type="text" name="slug" x-model="slug" value="{{ old('slug') }}"
                                        placeholder="sunrise-realty"
                                        class="flex-1 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none border-0"
                                        @input="slug = $el.value">
                                    <span class="flex items-center bg-gray-100 border-l border-gray-300 px-3 py-3 text-sm text-gray-500 whitespace-nowrap font-mono">.rentify.test</span>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400">
                                    <span x-show="previewSlug" x-cloak class="inline-flex items-center gap-1 text-indigo-500 font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-9.86a4.5 4.5 0 00-6.364 6.364l4.5 4.5" /></svg>
                                        <span x-text="previewSlug + '.rentify.test'"></span>
                                    </span>
                                    <span x-show="!previewSlug">Choose a URL for your workspace</span>
                                </p>
                            </div>

                            {{-- Account Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Account Type</label>
                                <div class="grid grid-cols-2 gap-3" x-data="{ role: '{{ old('role', 'agent') }}' }">
                                    <label @click="role = 'agent'"
                                        :class="role === 'agent' ? 'border-indigo-500 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-gray-300 hover:border-gray-400'"
                                        class="relative flex cursor-pointer rounded-xl border p-4 transition">
                                        <input type="radio" name="role" value="agent" x-model="role" class="sr-only">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" :class="role === 'agent' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                                                <span class="text-sm font-semibold" :class="role === 'agent' ? 'text-indigo-900' : 'text-gray-700'">Agent</span>
                                            </div>
                                            <p class="mt-1 text-xs" :class="role === 'agent' ? 'text-indigo-600' : 'text-gray-400'">I manage properties for landlords</p>
                                        </div>
                                    </label>
                                    <label @click="role = 'landlord'"
                                        :class="role === 'landlord' ? 'border-indigo-500 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-gray-300 hover:border-gray-400'"
                                        class="relative flex cursor-pointer rounded-xl border p-4 transition">
                                        <input type="radio" name="role" value="landlord" x-model="role" class="sr-only">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" :class="role === 'landlord' ? 'text-indigo-600' : 'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>
                                                <span class="text-sm font-semibold" :class="role === 'landlord' ? 'text-indigo-900' : 'text-gray-700'">Landlord</span>
                                            </div>
                                            <p class="mt-1 text-xs" :class="role === 'landlord' ? 'text-indigo-600' : 'text-gray-400'">I own properties directly</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="my-6 border-t border-gray-100"></div>

                        {{-- Section: Your details --}}
                        <div class="space-y-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Your details</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        placeholder="John Kamau"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        placeholder="+254 712 345 678"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="you@example.com"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                                    <input type="password" name="password" required
                                        placeholder="Min 8 characters"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required
                                        placeholder="Re-enter password"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50/80 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="mt-7">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 active:scale-[0.98]">
                                Create Workspace
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>

                        {{-- Trust signals --}}
                        <div class="mt-5 flex items-center justify-center gap-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                                SSL Secured
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Setup in 60s
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                                No card needed
                            </span>
                        </div>

                        <p class="mt-4 text-center text-sm text-gray-500">
                            Already have a workspace?
                            <a href="{{ url('/') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Go to homepage</a>
                        </p>
                    </form>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 text-center border-t border-gray-100">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Rentify. All rights reserved.</p>
            </div>
        </div>

    </div>
</body>
</html>
