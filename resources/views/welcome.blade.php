<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rentify — Property Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #fff; -webkit-font-smoothing: antialiased; }
        .display { font-family: 'Plus Jakarta Sans', sans-serif; }

        .gt-indigo {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* Nav */
        .nav-glass {
            background: rgba(255,255,255,0.90);
            border: 1px solid #f0f0f0;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Buttons */
        .btn-dark {
            display: inline-flex; align-items: center; gap: 8px;
            background: #111827; color: #fff; font-weight: 600;
            border-radius: 10px; padding: 12px 24px; font-size: 14px;
            transition: background 0.18s, transform 0.18s;
        }
        .btn-dark:hover { background: #1f2937; transform: translateY(-1px); }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; border: 1.5px solid #e5e7eb; color: #374151;
            font-weight: 600; border-radius: 10px; padding: 12px 24px; font-size: 14px;
            transition: border-color 0.18s, background 0.18s;
        }
        .btn-outline:hover { border-color: #9ca3af; background: #f9fafb; }

        /* Feature card */
        .feat {
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            padding: 28px;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .feat:hover { border-color: #e0e7ff; box-shadow: 0 4px 24px rgba(79,70,229,0.08); }

        /* Reveal */
        .reveal { opacity: 0; transform: translateY(16px); }
        .revealed { animation: up 0.65s cubic-bezier(0.22,0.61,0.25,1) forwards; }
        @keyframes up { to { opacity: 1; transform: translateY(0); } }
        .d1{animation-delay:.05s} .d2{animation-delay:.12s} .d3{animation-delay:.20s} .d4{animation-delay:.28s}
    </style>
</head>
<body>

{{-- NAV --}}
<header class="fixed top-0 inset-x-0 z-50">
    <div class="mx-auto max-w-6xl px-6 pt-4">
        <nav class="nav-glass rounded-2xl flex items-center justify-between h-14 px-6">
            <a href="/" class="flex items-center gap-2.5">
                <div class="h-8 w-8 flex items-center justify-center rounded-xl bg-gray-900 text-white text-sm font-black">R</div>
                <span class="display text-sm font-extrabold text-gray-900 tracking-tight">Rentify</span>
            </a>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-dark !py-2 !px-4 !text-sm">Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition mr-1">Log in</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-dark !py-2 !px-4 !text-sm">Get started →</a>
                    @endif
                @endauth
            </div>
        </nav>
    </div>
</header>

<main>

{{-- HERO --}}
<section class="pt-40 pb-24 px-6 text-center">
    <div class="mx-auto max-w-3xl">
        <div class="reveal d1 inline-flex items-center gap-2 rounded-full bg-indigo-50 border border-indigo-100 px-4 py-1.5 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-8">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
            Property Management Platform
        </div>

        <h1 class="reveal d2 display text-5xl sm:text-6xl font-black leading-[1.08] tracking-tight text-gray-900 mb-6">
            Run your rental<br><span class="gt-indigo">portfolio smarter.</span>
        </h1>

        <p class="reveal d3 text-lg text-gray-500 max-w-xl mx-auto mb-10 leading-relaxed">
            Leases, invoices, M-Pesa payments, and maintenance — all in one clean platform built for agents, landlords, and tenants.
        </p>

        <div class="reveal d4 flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-dark">Open Dashboard →</a>
            @else
                <a href="{{ route('register') }}" class="btn-dark">Start for free →</a>
                <a href="{{ route('login') }}" class="btn-outline">Log in</a>
            @endauth
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="border-y border-gray-100 bg-gray-50 py-12 px-6">
    <div class="mx-auto max-w-4xl grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
        @php $stats = [
            ['500+', 'Properties managed'],
            ['KSh 50M+', 'Rent collected'],
            ['98%', 'Collection rate'],
            ['3', 'User roles'],
        ]; @endphp
        @foreach($stats as $i => $s)
        <div class="{{ $i < 3 ? 'lg:border-r lg:border-gray-200' : '' }}">
            <p class="display text-3xl font-black text-gray-900" data-stat>{{ $s[0] }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ $s[1] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-24 px-6">
    <div class="mx-auto max-w-5xl">
        <div class="text-center mb-14 reveal">
            <h2 class="display text-3xl sm:text-4xl font-black text-gray-900 mb-4">Everything you need to manage properties.</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Built for the full lifecycle — from onboarding tenants to collecting rent and resolving maintenance.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php $features = [
                ['Portfolio Dashboard',   'Real-time occupancy, revenue, and property performance across your entire portfolio.',          'bg-indigo-50',  'text-indigo-600',  'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4'],
                ['Automated Invoicing',   'Issue rent invoices automatically each month. No manual work, no missed billing.',              'bg-amber-50',   'text-amber-600',   'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
                ['M-Pesa Payments',       'Tenants pay via M-Pesa. Payments reconcile instantly with no manual entry required.',          'bg-emerald-50', 'text-emerald-600', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                ['Lease Management',      'Full lifecycle from drafting to expiry. Renewals, statements, and negotiation built in.',      'bg-violet-50',  'text-violet-600',  'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['Maintenance Tracking',  'Tenants log tickets. Agents action them. Every request is tracked from open to resolved.',    'bg-rose-50',    'text-rose-600',    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['Reports & Insights',    'Arrears summaries, occupancy reports, and landlord statements to drive better decisions.',    'bg-sky-50',     'text-sky-600',     'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ]; @endphp
            @foreach($features as $f)
            <div class="reveal feat">
                <div class="h-10 w-10 rounded-xl {{ $f[2] }} flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 {{ $f[3] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $f[4] }}"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1.5">{{ $f[0] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $f[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ROLES --}}
<section class="bg-gray-50 border-y border-gray-100 py-20 px-6">
    <div class="mx-auto max-w-4xl">
        <div class="text-center mb-12 reveal">
            <h2 class="display text-3xl font-black text-gray-900 mb-3">Built for three roles.</h2>
            <p class="text-gray-500">One platform. Tailored experience for every user type.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach([
                ['Agent',    'Manage properties, landlords, tenants, leases, and billing across your full portfolio.',       'from-indigo-500 to-violet-600'],
                ['Landlord', 'See your properties, income, active leases, and maintenance — all in one clean view.',         'from-emerald-500 to-teal-500'],
                ['Tenant',   'Pay rent, view your lease, and submit maintenance requests directly from your portal.',        'from-amber-400 to-orange-500'],
            ] as $role)
            <div class="reveal bg-white border border-gray-200 rounded-2xl p-6">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $role[2] }} flex items-center justify-center text-sm font-black text-white mb-4">{{ substr($role[0],0,1) }}</div>
                <p class="text-sm font-bold text-gray-900 mb-1.5">{{ $role[0] }}</p>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $role[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-gray-900 py-24 px-6 text-center">
    <div class="mx-auto max-w-xl reveal">
        <h2 class="display text-3xl sm:text-4xl font-black text-white mb-5">Ready to get started?</h2>
        <p class="text-gray-400 mb-10">No credit card required. Set up your portfolio in minutes.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-dark !bg-white !text-gray-900 hover:!bg-gray-100">Go to Dashboard →</a>
            @else
                <a href="{{ route('register') }}" class="btn-dark !bg-white !text-gray-900 hover:!bg-gray-100">Create free account →</a>
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">Log in instead</a>
            @endauth
        </div>
    </div>
</section>

</main>

{{-- FOOTER --}}
<footer class="border-t border-gray-100 py-10 px-6">
    <div class="mx-auto max-w-5xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="h-7 w-7 flex items-center justify-center rounded-lg bg-gray-900 text-white text-xs font-black">R</div>
            <span class="display text-sm font-extrabold text-gray-900">Rentify</span>
        </div>
        <p class="text-sm text-gray-400">&copy; {{ date('Y') }} Rentify. Premium property management.</p>
        <div class="flex items-center gap-5 text-sm text-gray-400">
            <a href="#features" class="hover:text-gray-900 transition">Features</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="hover:text-gray-900 transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hover:text-gray-900 transition">Log in</a>
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="hover:text-gray-900 transition">Sign up</a>
                @endif
            @endauth
        </div>
    </div>
</footer>

<script>
(function () {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); io.unobserve(e.target); } });
    }, { threshold: 0.10 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
})();
</script>

</body>
</html>
