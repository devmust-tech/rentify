<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rentify — Smart Property Management for Kenya</title>
    <meta name="description" content="Kenya's #1 property management platform. List properties, screen tenants, collect rent via M-Pesa — all in one place.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: #1e293b; background: #fff; -webkit-font-smoothing: antialiased; }

        [x-cloak] { display: none !important; }

        /* Reveal animations */
        .reveal { opacity: 0; transform: translateY(20px); }
        .revealed { animation: fadeUp 0.65s cubic-bezier(0.22, 0.61, 0.25, 1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .d1 { animation-delay: .05s } .d2 { animation-delay: .12s }
        .d3 { animation-delay: .20s } .d4 { animation-delay: .28s }
        .d5 { animation-delay: .36s } .d6 { animation-delay: .44s }

        /* Animated dots/blobs in hero */
        @keyframes blob { 0%,100% { transform: scale(1) translate(0,0); } 33% { transform: scale(1.1) translate(12px,-8px); } 66% { transform: scale(0.9) translate(-8px,10px); } }
        .animate-blob { animation: blob 8s ease-in-out infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Smooth tick bounce */
        @keyframes ping { 75%,100% { transform: scale(2); opacity: 0; } }
        .animate-ping { animation: ping 1.5s cubic-bezier(0,0,.2,1) infinite; }
        @keyframes pulse { 50% { opacity: .5; } }
        .animate-pulse { animation: pulse 2s cubic-bezier(.4,0,.6,1) infinite; }
        @keyframes bounce { 0%,100% { transform: translateY(-25%); animation-timing-function: cubic-bezier(.8,0,1,1); } 50% { transform: translateY(0); animation-timing-function: cubic-bezier(0,0,.2,1); } }
        .animate-bounce { animation: bounce 1s infinite; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════ NAVBAR ══ --}}
<header x-data="{ scrolled: false, menuOpen: false }" @scroll.window="scrolled = window.scrollY > 20"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
    :class="scrolled ? 'bg-white/95 shadow-md shadow-black/5 backdrop-blur-md' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center shadow-md flex-shrink-0">
                    <svg viewBox="0 0 20 20" fill="white" class="w-5 h-5">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">Renti<span class="text-teal-500">fy</span></span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                @foreach([['Features','#features'],['How It Works','#how-it-works'],['Pricing','#pricing'],['FAQ','#faq']] as $l)
                <a href="{{ $l[1] }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors duration-200">{{ $l[0] }}</a>
                @endforeach
            </nav>

            {{-- Right CTAs --}}
            <div class="flex items-center gap-2">
                <a href="http://demo.{{ config('app.domain') }}/login" class="hidden sm:block px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors duration-200">Log in</a>
                <a href="{{ route('org.register') }}" class="px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold transition-colors shadow-sm shadow-teal-600/20">Get Started</a>

                {{-- Mobile hamburger --}}
                <button @click="menuOpen = !menuOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg x-show="!menuOpen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="menuOpen" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="menuOpen" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white border-t border-gray-100 px-4 py-4 space-y-1">
        @foreach([['Features','#features'],['How It Works','#how-it-works'],['Pricing','#pricing'],['FAQ','#faq']] as $l)
        <a href="{{ $l[1] }}" @click="menuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">{{ $l[0] }}</a>
        @endforeach
        <a href="{{ route('org.register') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">Log in</a>
    </div>
</header>

<main>

{{-- ═══════════════════════════════════════════════════════ HERO ══ --}}
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden pt-16 bg-gradient-to-br from-slate-50 via-teal-50/40 to-orange-50/20">

    {{-- Background blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-32 w-96 h-96 bg-teal-300 rounded-full mix-blend-multiply opacity-20 blur-3xl animate-blob"></div>
        <div class="absolute top-40 -left-32 w-96 h-96 bg-orange-300 rounded-full mix-blend-multiply opacity-15 blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-20 right-1/3 w-80 h-80 bg-teal-200 rounded-full mix-blend-multiply opacity-20 blur-3xl animate-blob animation-delay-4000"></div>
        {{-- Grid pattern --}}
        <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
            <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#0f172a" stroke-width="1"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 w-full">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- Left: Copy --}}
            <div>
                {{-- Badge --}}
                <div class="reveal d1 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold mb-6 bg-teal-500/10 text-teal-700 ring-1 ring-teal-500/20">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-teal-500"></span>
                    </span>
                    Kenya's #1 Property Management Platform
                </div>

                <h1 class="reveal d2 text-4xl sm:text-5xl lg:text-[3.5rem] font-bold leading-tight tracking-tight text-slate-900 mb-6">
                    Manage Properties.<br>
                    <span class="text-teal-600">Find Tenants.</span><br>
                    <span class="relative inline-block">
                        Collect Rent Seamlessly.
                        <svg class="absolute -bottom-1.5 left-0 w-full" viewBox="0 0 420 10" fill="none" preserveAspectRatio="none">
                            <path d="M2 7 Q105 2 210 7 Q315 12 418 7" stroke="#f97316" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>

                <p class="reveal d3 text-lg leading-relaxed text-slate-600 max-w-xl mb-8">
                    Rentify helps landlords, agents, and tenants across Kenya streamline every aspect of property management — from verified listings to instant M-Pesa rent collection.
                </p>

                {{-- CTAs --}}
                <div class="reveal d4 flex flex-col sm:flex-row gap-3 mb-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-base transition-all duration-200 shadow-lg shadow-teal-600/25 hover:-translate-y-0.5">
                            Open Dashboard →
                        </a>
                    @else
                        <a href="{{ route('org.register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-base transition-all duration-200 shadow-lg shadow-teal-600/25 hover:-translate-y-0.5">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                            List Your Property
                        </a>
                        <a href="{{ route('org.register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-base transition-all duration-200 hover:-translate-y-0.5 ring-2 ring-orange-500 text-orange-600 hover:bg-orange-50">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                            Find a Home
                        </a>
                    @endauth
                </div>

                {{-- Trust signals --}}
                <div class="reveal d5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
                    @foreach(['No credit card required', 'Free forever plan', 'M-Pesa integrated'] as $t)
                    <span class="flex items-center gap-1.5">
                        <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-teal-500 flex-shrink-0"><path fill-rule="evenodd" d="M8 16A8 8 0 108 0a8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L7 8.586 5.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $t }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Right: Dashboard mockup --}}
            <div class="reveal d3 relative">
                {{-- Floating trust badge --}}
                <div class="absolute -top-4 -left-4 z-10 flex items-center gap-2 px-3.5 py-2 rounded-xl shadow-xl bg-white ring-1 ring-gray-100">
                    <span class="text-xl">🏆</span>
                    <div>
                        <p class="text-xs text-gray-500 leading-tight">Trusted by</p>
                        <p class="text-sm font-bold text-gray-900 leading-tight">2,000+ Landlords</p>
                    </div>
                </div>

                {{-- Browser mockup --}}
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-1 ring-black/10">
                    {{-- Chrome bar --}}
                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-100 border-b border-gray-200">
                        <div class="flex gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400 inline-block"></span>
                        </div>
                        <div class="flex-1 text-center text-xs font-medium text-gray-500 font-mono">app.rentify.co.ke/dashboard</div>
                    </div>
                    {{-- Dashboard body --}}
                    <div class="bg-white p-5">
                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            @foreach([['Properties','24','+2 this month'],['Active Tenants','47','96% occupancy'],['Revenue (KES)','485K','↑ 12% vs last']] as $s)
                            <div class="rounded-xl p-3.5 bg-gray-50">
                                <p class="text-gray-500 font-medium uppercase tracking-wider mb-1" style="font-size:9px">{{ $s[0] }}</p>
                                <p class="text-xl font-bold text-gray-900">{{ $s[1] }}</p>
                                <p class="text-teal-500 font-medium mt-0.5" style="font-size:9px">{{ $s[2] }}</p>
                            </div>
                            @endforeach
                        </div>
                        {{-- Property rows --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-semibold text-gray-700">Recent Properties</p>
                                <span class="text-xs text-teal-500">View all →</span>
                            </div>
                            <div class="space-y-2">
                                @foreach([['Westlands 2BR Apt','Nairobi','KES 45,000','Occupied','bg-green-400'],['Mombasa Beach Studio','Mombasa','KES 28,000','Available','bg-amber-400']] as $p)
                                <div class="flex items-center justify-between rounded-lg px-3 py-2.5 bg-gray-50">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center flex-shrink-0">
                                            <svg viewBox="0 0 16 16" fill="white" class="w-4 h-4"><path d="M8 1L2 6v8h4V9h4v5h4V6L8 1z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-800">{{ $p[0] }}</p>
                                            <p class="text-gray-400" style="font-size:10px">{{ $p[1] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-gray-800">{{ $p[2] }}</p>
                                        <div class="flex items-center gap-1 justify-end mt-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $p[4] }} inline-block"></span>
                                            <p class="text-gray-400" style="font-size:10px">{{ $p[3] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Payment notification --}}
                        <div class="flex items-center gap-3 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 px-4 py-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg viewBox="0 0 20 20" fill="white" class="w-4 h-4"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM2 9v7a2 2 0 002 2h12a2 2 0 002-2V9H2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-xs font-semibold">M-Pesa Payment Received</p>
                                <p class="text-teal-100" style="font-size:10px">Grace W. · KES 45,000 · Just now</p>
                            </div>
                            <span class="text-white bg-white/20 rounded-full px-2 py-0.5 font-bold whitespace-nowrap" style="font-size:10px">✓ Paid</span>
                        </div>
                    </div>
                </div>

                {{-- Floating M-Pesa badge --}}
                <div class="absolute -bottom-4 -right-4 z-10 flex items-center gap-2 px-4 py-2.5 rounded-xl shadow-xl bg-white ring-1 ring-gray-100">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">M</div>
                    <div>
                        <p class="text-xs text-gray-500">M-Pesa</p>
                        <p class="text-sm font-bold text-gray-800">Integrated</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1">
        <span class="text-xs font-medium text-slate-400">Scroll to explore</span>
        <div class="w-5 h-8 rounded-full border-2 border-slate-300 flex justify-center pt-1.5">
            <div class="w-1 h-2 rounded-full bg-slate-400 animate-bounce"></div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════ HOW IT WORKS ══ --}}
<section id="how-it-works" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-50 text-teal-600 mb-4">Simple Process</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Up and running in 3 steps</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">From listing to rent collection — Rentify handles the complexity so you don't have to.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
            @php
            $steps = [
                ['01','List Your Property','Create a detailed listing with photos, amenities, and pricing. Go live in under 5 minutes.','from-teal-500 to-teal-700','shadow-teal-500/25','M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4'],
                ['02','Get Verified Tenants','We screen applicants with ID verification, background checks, and rental history analysis.','from-blue-500 to-blue-700','shadow-blue-500/25','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['03','Collect Rent Seamlessly','Automated M-Pesa STK push on due date, instant notifications, and real-time payment tracking.','from-orange-500 to-orange-600','shadow-orange-500/25','M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="reveal d{{ $i+1 }} flex flex-col items-center text-center">
                <div class="relative mb-6">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg text-white bg-gradient-to-br {{ $step[3] }} {{ $step[4] }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step[5] }}"/>
                        </svg>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white ring-2 ring-teal-200 shadow-sm text-xs font-bold text-teal-600 flex items-center justify-center">{{ $i+1 }}</div>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $step[1] }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════ FEATURES ══ --}}
<section id="features" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-50 text-teal-600 mb-4">Everything You Need</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Built for the Kenyan market</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Every feature is designed around the way property management actually works in Kenya.</p>
        </div>
        @php
        $features = [
            ['Tenant Screening','National ID verification, credit history checks, and reference validation. Know your tenant before they move in.','bg-teal-50 text-teal-600','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['M-Pesa Rent Collection','Automated STK push requests every rent cycle. Tenants pay with one tap; you get notified instantly.','bg-green-50 text-green-600','M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            ['Digital Lease Management','Create, sign, and store leases online. Auto-renewal alerts and lease expiry tracking built in.','bg-blue-50 text-blue-600','M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['Maintenance Requests','Tenants submit issues with photos; landlords assign, track, and resolve from a unified dashboard.','bg-orange-50 text-orange-600','M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
            ['Analytics Dashboard','Real-time occupancy rates, revenue trends, arrears summaries, and portfolio insights at a glance.','bg-purple-50 text-purple-600','M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['Multi-Role Access','Landlords, property agents, and tenants each get a tailored portal — one platform, three experiences.','bg-rose-50 text-rose-600','M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ];
        @endphp
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($features as $i => $f)
            <div class="reveal d{{ ($i % 3) + 1 }} group rounded-2xl bg-white border border-gray-100 p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 cursor-default">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 {{ $f[2] }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f[3] }}"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2.5">{{ $f[0] }}</h3>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $f[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════ SOCIAL PROOF ══ --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Stats bar --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 p-8 rounded-2xl bg-gradient-to-r from-teal-600 to-teal-700 mb-20 reveal"
             style="background: linear-gradient(to right, #0d9488, #0f766e);">
            @foreach([['2,000+','Properties Listed'],['10,000+','Happy Tenants'],['KES 500M+','Rent Processed'],['47','Counties Covered']] as $s)
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-white mb-1">{{ $s[0] }}</div>
                <div class="text-sm font-medium text-teal-100">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Testimonials --}}
        <div class="text-center mb-12 reveal">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-50 text-teal-600 mb-4">Testimonials</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Loved across Kenya</h2>
        </div>

        @php
        $testimonials = [
            ['James Kamau','Landlord, Nairobi','JK','from-teal-400 to-teal-600',"I used to chase tenants for rent every month. With Rentify, M-Pesa reminders go out automatically and I get paid on time — every single month."],
            ['Grace Wanjiku','Tenant, Westlands','GW','from-orange-400 to-orange-600',"Finding a verified, legit apartment used to be stressful. Rentify showed me vetted listings and I moved in within a week. The maintenance request system is a game-changer."],
            ['Samuel Ochieng','Property Agent, Mombasa','SO','from-blue-400 to-blue-600',"Managing 40+ properties across Mombasa was chaos before Rentify. Now I have listings, tenant records, and lease renewals in one clean dashboard."],
            ['Amina Hassan','Landlord, Kisumu','AH','from-purple-400 to-purple-600',"The analytics dashboard helped me discover two units were underpriced. I corrected it at renewal — that's extra KES 36K a year from data I already had."],
        ];
        @endphp

        <div class="relative max-w-3xl mx-auto"
            x-data="{
                active: 0,
                testimonials: {{ count($testimonials) }},
                init() {
                    setInterval(() => { this.active = (this.active + 1) % this.testimonials }, 5000)
                }
            }"
            x-init="init()">
            @foreach($testimonials as $i => $t)
            <div x-show="active === {{ $i }}"
                x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                @if($i > 0) style="display:none" @endif
                class="rounded-2xl bg-slate-50 ring-1 ring-slate-100 p-8 sm:p-10 relative">
                {{-- Quote mark --}}
                <svg class="absolute top-6 right-8 w-12 h-12 text-slate-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <div class="flex items-start gap-5 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $t[3] }} flex items-center justify-center text-white text-lg font-bold flex-shrink-0 shadow-lg">{{ $t[2] }}</div>
                    <div>
                        <div class="font-bold text-lg text-slate-900">{{ $t[0] }}</div>
                        <div class="text-sm text-slate-500">{{ $t[1] }}</div>
                        <div class="flex gap-0.5 mt-1">
                            @for($s=0;$s<5;$s++)
                            <svg viewBox="0 0 16 16" fill="#F59E0B" class="w-3.5 h-3.5"><path d="M8 1l2.121 4.303L15 6.117l-3.5 3.41.826 4.813L8 12.19 3.674 14.34l.826-4.813L1 6.117l4.879-.814L8 1z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-base sm:text-lg text-slate-700 leading-relaxed">"{{ $t[4] }}"</p>
            </div>
            @endforeach

            {{-- Dots --}}
            <div class="flex justify-center gap-2 mt-6">
                @foreach($testimonials as $i => $t)
                <button @click="active = {{ $i }}"
                    class="rounded-full transition-all duration-300 h-2.5"
                    :class="active === {{ $i }} ? 'w-6 bg-teal-500' : 'w-2.5 bg-slate-300 hover:bg-slate-400'">
                </button>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════ PRICING ══ --}}
<section id="pricing" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        x-data="{ annual: false }">
        <div class="text-center mb-16 reveal">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-50 text-teal-600 mb-4">Pricing</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Transparent, honest pricing</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-8">Start free, scale as you grow. No hidden fees, no contracts.</p>
            {{-- Toggle --}}
            <div class="inline-flex items-center gap-3">
                <span class="text-sm font-medium" :class="!annual ? 'text-slate-900' : 'text-slate-400'">Monthly</span>
                <button @click="annual = !annual" class="relative w-12 h-6 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2" :class="annual ? 'bg-teal-500' : 'bg-slate-200'">
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-300 block" :class="annual ? 'translate-x-6' : ''"></span>
                </button>
                <span class="text-sm font-medium flex items-center gap-1.5" :class="annual ? 'text-slate-900' : 'text-slate-400'">
                    Annual
                    <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded-full">Save 20%</span>
                </span>
            </div>
        </div>

        @php
        $plans = [
            ['name'=>'Free','monthly'=>'KES 0','annual'=>'KES 0','period_m'=>'/month','period_a'=>'/year','desc'=>'Perfect for individual landlords getting started.','badge'=>null,'primary'=>false,
             'features'=>['1 property listing','Tenant messaging','Basic lease templates','Manual rent tracking','Email support'],
             'missing'=>['M-Pesa integration','Tenant screening','Analytics dashboard'],
             'cta'=>'Get Started Free','href'=>'/register-org'],
            ['name'=>'Pro','monthly'=>'KES 2,999','annual'=>'KES 28,790','period_m'=>'/month','period_a'=>'/year','desc'=>'For landlords and agents managing multiple properties.','badge'=>'Most Popular','primary'=>true,
             'features'=>['Up to 20 properties','M-Pesa rent collection','Tenant screening & verification','Digital lease management','Maintenance request tracking','Analytics dashboard','Priority support'],
             'missing'=>[],
             'cta'=>'Start Free Trial','href'=>'/register-org'],
            ['name'=>'Enterprise','monthly'=>'Custom','annual'=>'Custom','period_m'=>'','period_a'=>'','desc'=>'For agencies and large portfolio managers.','badge'=>null,'primary'=>false,
             'features'=>['Unlimited properties','Multi-agent management','Advanced analytics & reports','API & system integrations','Custom lease workflows','Dedicated account manager','SLA-backed support'],
             'missing'=>[],
             'cta'=>'Contact Sales','href'=>'/register-org'],
        ];
        @endphp
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8 items-start">
            @foreach($plans as $i => $plan)
            <div class="reveal d{{ $i+1 }} relative flex flex-col rounded-2xl overflow-hidden {{ $plan['primary'] ? 'bg-gradient-to-b from-teal-600 to-teal-700 shadow-2xl ring-2 ring-teal-400/30 md:-mt-4' : 'bg-white ring-1 ring-slate-200' }}">
                @if($plan['badge'])
                <div class="text-center py-2 bg-orange-500 text-white text-xs font-bold">{{ $plan['badge'] }}</div>
                @endif
                <div class="p-8 pb-6 border-b {{ $plan['primary'] ? 'border-teal-500/40' : 'border-slate-100' }}">
                    <h3 class="text-lg font-bold mb-1 {{ $plan['primary'] ? 'text-white' : 'text-slate-900' }}">{{ $plan['name'] }}</h3>
                    <p class="text-sm mb-5 {{ $plan['primary'] ? 'text-teal-100' : 'text-slate-500' }}">{{ $plan['desc'] }}</p>
                    <div class="flex items-end gap-1">
                        <span class="text-4xl font-bold {{ $plan['primary'] ? 'text-white' : 'text-slate-900' }}">
                            <span x-show="!annual">{{ $plan['monthly'] }}</span>
                            <span x-show="annual" style="display:none">{{ $plan['annual'] }}</span>
                        </span>
                        @if($plan['period_m'])
                        <span class="text-sm pb-1 {{ $plan['primary'] ? 'text-teal-100' : 'text-slate-500' }}">
                            <span x-show="!annual">{{ $plan['period_m'] }}</span>
                            <span x-show="annual" style="display:none">{{ $plan['period_a'] }}</span>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex-1 p-8">
                    <ul class="space-y-3 mb-8">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-start gap-2.5">
                            <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mt-0.5 flex-shrink-0 {{ $plan['primary'] ? 'text-teal-200' : 'text-teal-500' }}"><path fill-rule="evenodd" d="M8 16A8 8 0 108 0a8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L7 8.586 5.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm {{ $plan['primary'] ? 'text-teal-50' : 'text-slate-600' }}">{{ $feature }}</span>
                        </li>
                        @endforeach
                        @foreach($plan['missing'] as $missing)
                        <li class="flex items-start gap-2.5 opacity-40">
                            <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mt-0.5 flex-shrink-0 text-slate-400"><path fill-rule="evenodd" d="M8 16A8 8 0 108 0a8 8 0 000 16zM7 5a1 1 0 012 0v4a1 1 0 01-2 0V5zm1 7a1 1 0 110-2 1 1 0 010 2z" clip-rule="evenodd"/></svg>
                            <span class="text-sm line-through text-slate-400">{{ $missing }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('org.register') }}"
                        class="block w-full text-center py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5 {{ $plan['primary'] ? 'bg-white text-teal-700 hover:bg-teal-50 shadow-md' : 'bg-slate-900 text-white hover:bg-slate-800' }}">
                        {{ $plan['cta'] }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════ FAQ ══ --}}
<section id="faq" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-50 text-teal-600 mb-4">FAQ</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Frequently asked questions</h2>
            <p class="text-lg text-slate-600">Can't find your answer? <a href="mailto:hello@rentify.co.ke" class="text-teal-600 hover:underline">Contact us</a>.</p>
        </div>

        @php
        $faqs = [
            ['How does M-Pesa integration work?', "Rentify sends an automated STK Push to your tenant's M-Pesa number on the due date. Once the tenant approves the prompt on their phone, the payment is confirmed and both parties receive instant notifications. All transactions are recorded automatically."],
            ['How does tenant verification work?', "Tenants submit their National ID, employment details, and references through the app. Rentify runs an automated ID check and cross-references rental history. Landlords receive a verified tenant profile with a screening score before approving any application."],
            ['Can property agents manage multiple landlords?', "Yes. Agents have a dedicated portal where they can manage properties across multiple landlords, coordinate tenant applications, handle maintenance requests, and generate reports — all from one unified dashboard."],
            ['Is my financial data secure?', "Rentify uses 256-bit TLS encryption for all data in transit and AES-256 encryption at rest. We comply with Kenya's Data Protection Act, 2019. Your financial data is never sold or shared with third parties."],
            ['What happens when a tenant misses a rent payment?', "Rentify automatically sends escalating reminders via SMS and in-app notifications. The landlord receives an alert and can issue a formal notice directly through the platform. A full arrears history is recorded on your dashboard."],
            ['Can I manage properties in multiple counties?', "Absolutely. Rentify supports all 47 counties in Kenya. You can filter, sort, and manage properties by location, view county-level analytics, and assign different agents to different regions — all from a single account."],
        ];
        @endphp

        <div x-data="{ open: null }" class="space-y-3">
            @foreach($faqs as $i => $faq)
            <div class="reveal d{{ ($i % 2) + 1 }} rounded-xl border transition-colors duration-300"
                :class="open === {{ $i }} ? 'border-teal-200 bg-teal-50/50' : 'border-slate-200 bg-white hover:border-slate-300'">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="flex w-full items-center justify-between px-6 py-5 text-left">
                    <span class="font-semibold text-base text-slate-900 pr-4">{{ $faq[0] }}</span>
                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-all duration-300"
                        :class="open === {{ $i }} ? 'rotate-45 bg-teal-500 text-white' : 'bg-slate-100 text-slate-500'">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5" class="w-3 h-3">
                            <path stroke-linecap="round" d="M8 3v10M3 8h10"/>
                        </svg>
                    </span>
                </button>
                <div x-show="open === {{ $i }}"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    style="display:none">
                    <p class="px-6 pb-5 text-sm text-slate-600 leading-relaxed">{{ $faq[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════ FINAL CTA ══ --}}
<section class="py-24 relative overflow-hidden bg-gradient-to-br from-teal-700 via-teal-600 to-teal-800"
         style="background: linear-gradient(135deg, #0f766e, #0d9488, #115e59);">
    {{-- Background --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-teal-500/30 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-teal-900/50 blur-3xl"></div>
        <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
            <defs><pattern id="dots" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="white"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#dots)"/>
        </svg>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal"
        x-data="{ email: '', submitted: false }">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold mb-6 text-teal-100" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2)">
            <span class="relative flex h-1.5 w-1.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-orange-400"></span>
            </span>
            Get Started Today — It's Free
        </div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
            Ready to transform your<br><span class="text-teal-200">property management?</span>
        </h2>
        <p class="text-teal-100 text-lg sm:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
            Join thousands of landlords, agents, and tenants across Kenya already using Rentify. No credit card required.
        </p>

        <div x-show="!submitted">
            <form @submit.prevent="submitted = email.length > 0" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
                <input type="email" x-model="email" placeholder="Enter your email address" required
                    class="flex-1 px-5 py-3.5 rounded-xl text-slate-900 text-base font-medium placeholder-slate-400 focus:outline-none focus:ring-2 bg-white/95 shadow-lg border-0">
                <button type="submit"
                    class="px-6 py-3.5 rounded-xl bg-orange-500 hover:bg-orange-400 text-white font-bold text-base transition-all duration-200 shadow-lg whitespace-nowrap hover:-translate-y-0.5">
                    Get Started Free →
                </button>
            </form>
        </div>

        <div x-show="submitted" x-cloak class="inline-flex items-center gap-3 px-8 py-4 rounded-xl text-white font-semibold text-lg" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2)">
            <svg viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-teal-200">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            You're on the list! We'll be in touch shortly.
        </div>

        <p class="mt-5 text-sm" style="color:rgba(167,243,208,.7)">
            No spam, ever. By signing up you agree to our <a href="#" class="underline hover:text-white">Terms</a> &amp; <a href="#" class="underline hover:text-white">Privacy Policy</a>.
        </p>
    </div>
</section>

</main>


{{-- ═══════════════════════════════════════════ FOOTER ══ --}}
<footer class="bg-slate-900 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-8 mb-12">
            {{-- Brand --}}
            <div class="col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg viewBox="0 0 20 20" fill="white" class="w-5 h-5"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-white">Renti<span class="text-teal-400">fy</span></span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-xs">The smart property management platform built for Kenya's growing rental market.</p>
                {{-- Socials --}}
                <div class="flex gap-3">
                    @foreach([
                        ['Twitter/X','M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
                        ['LinkedIn','M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z'],
                        ['Facebook','M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                    ] as $social)
                    <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-teal-600 flex items-center justify-center transition-colors duration-200 group">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-slate-400 group-hover:text-white transition-colors">
                            <path d="{{ $social[1] }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Link columns --}}
            @foreach(['Product'=>['Features','Pricing','Changelog','Roadmap'],'Company'=>['About','Blog','Careers','Press'],'Support'=>['Help Center','Contact','Status','Community'],'Legal'=>['Privacy Policy','Terms of Service','Cookie Policy']] as $section => $items)
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">{{ $section }}</h4>
                <ul class="space-y-2.5">
                    @foreach($items as $item)
                    <li><a href="#" class="text-sm text-slate-500 hover:text-white transition-colors duration-200">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        {{-- Bottom bar --}}
        <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-600">© {{ date('Y') }} Rentify Technologies Ltd. All rights reserved.</p>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Made with</span>
                <span class="text-red-400">♥</span>
                <span>in Kenya</span>
                <span class="text-lg">🇰🇪</span>
            </div>
        </div>
    </div>
</footer>


<script>
(function () {
    const threshold = 0.12;
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('revealed');
                io.unobserve(e.target);
            }
        });
    }, { threshold });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
})();
</script>

</body>
</html>
