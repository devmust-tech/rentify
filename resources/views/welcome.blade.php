<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rentify | Premium Property Management Platform</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --navy-900: #081127;
                --navy-800: #101f3d;
                --navy-700: #1c2f57;
                --gold-400: #f0c76e;
                --gold-300: #f7d892;
                --violet-500: #6f52ff;
                --cyan-400: #39d5ff;
                --ink: #dfe8ff;
                --muted: #9fb0d6;
                --glass: rgba(255, 255, 255, 0.09);
                --glass-line: rgba(255, 255, 255, 0.22);
            }

            html { scroll-behavior: smooth; }

            body {
                font-family: "Manrope", sans-serif;
                color: var(--ink);
                background:
                    radial-gradient(60rem 50rem at 95% -5%, rgba(111, 82, 255, 0.40), transparent 60%),
                    radial-gradient(50rem 45rem at 5% 15%, rgba(57, 213, 255, 0.20), transparent 55%),
                    linear-gradient(135deg, var(--navy-900) 0%, var(--navy-800) 52%, #0f1e45 100%);
                min-height: 100vh;
            }

            h1, h2, h3, .title-face {
                font-family: "Plus Jakarta Sans", sans-serif;
            }

            .glow-gold {
                box-shadow: 0 18px 40px rgba(240, 199, 110, 0.22);
            }

            .glass {
                background: var(--glass);
                border: 1px solid var(--glass-line);
                backdrop-filter: blur(14px);
            }

            .hero-shimmer {
                position: relative;
                overflow: hidden;
            }

            .hero-shimmer::after {
                content: "";
                position: absolute;
                top: -120%;
                left: -35%;
                width: 50%;
                height: 300%;
                transform: rotate(20deg);
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.22), transparent);
                animation: sweep 5.8s ease-in-out infinite;
                pointer-events: none;
            }

            @keyframes sweep {
                0%, 100% { left: -50%; opacity: 0; }
                12% { opacity: 1; }
                55% { left: 120%; opacity: 0; }
            }

            .reveal {
                opacity: 0;
                transform: translateY(16px);
                animation: rise 0.8s cubic-bezier(0.2, 0.65, 0.25, 1) forwards;
            }

            .r1 { animation-delay: 0.1s; }
            .r2 { animation-delay: 0.2s; }
            .r3 { animation-delay: 0.3s; }

            @keyframes rise {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body class="antialiased">
        <nav class="sticky top-0 z-50 border-b border-white/10 bg-slate-900/40 backdrop-blur-xl">
            <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="/" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-content-center rounded-lg bg-gradient-to-br from-[color:var(--gold-300)] to-[color:var(--gold-400)] text-sm font-black text-[color:var(--navy-900)] shadow-lg">R</span>
                    <span class="title-face text-xl font-extrabold tracking-tight text-white">Rentify</span>
                </a>
                <div class="hidden items-center gap-8 text-sm font-semibold text-[color:var(--muted)] md:flex">
                    <a href="#features" class="transition hover:text-white">Features</a>
                    <a href="#workflow" class="transition hover:text-white">Process</a>
                    <a href="#cta" class="transition hover:text-white">Launch</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-sm font-bold text-[color:var(--navy-900)] transition hover:bg-[color:var(--gold-300)]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[color:var(--muted)] transition hover:text-white">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-gradient-to-r from-[color:var(--gold-300)] to-[color:var(--gold-400)] px-4 py-2 text-sm font-bold text-[color:var(--navy-900)] transition hover:brightness-105">Start Free</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            <section class="px-4 pb-14 pt-10 sm:px-6 sm:pt-14 lg:px-8">
                <div class="mx-auto grid w-full max-w-7xl items-center gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="reveal">
                        <span class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-[color:var(--gold-300)]">Premium Property Management</span>
                        <h1 class="mt-5 max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                            Luxury-grade property management for modern portfolios.
                        </h1>
                        <p class="mt-5 max-w-xl text-lg leading-relaxed text-[color:var(--muted)]">
                            Rentify gives agents, landlords, and tenants one elegant platform for leases, invoicing, payments, maintenance, and reporting.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="glow-gold inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[color:var(--gold-300)] to-[color:var(--gold-400)] px-6 py-3.5 text-base font-extrabold text-[color:var(--navy-900)] transition hover:brightness-105">Open Dashboard</a>
                            @else
                                <a href="{{ route('register') }}" class="glow-gold inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[color:var(--gold-300)] to-[color:var(--gold-400)] px-6 py-3.5 text-base font-extrabold text-[color:var(--navy-900)] transition hover:brightness-105">Create Account</a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/30 bg-white/10 px-6 py-3.5 text-base font-bold text-white transition hover:bg-white/20">Log In</a>
                            @endauth
                        </div>
                        <div class="mt-8 grid max-w-2xl grid-cols-3 gap-3">
                            <div class="glass rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-[color:var(--muted)]">Managed</p>
                                <p class="mt-1 text-2xl font-extrabold text-white">500+</p>
                            </div>
                            <div class="glass rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-[color:var(--muted)]">Collected</p>
                                <p class="mt-1 text-2xl font-extrabold text-white">KSh 50M+</p>
                            </div>
                            <div class="glass rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-[color:var(--muted)]">Success</p>
                                <p class="mt-1 text-2xl font-extrabold text-white">98%</p>
                            </div>
                        </div>
                    </div>

                    <div class="reveal r1">
                        <div class="hero-shimmer glass rounded-3xl p-4 shadow-[0_22px_55px_rgba(3,8,25,0.45)] sm:p-5">
                            <div class="rounded-2xl border border-white/15 bg-[color:var(--navy-700)]/65 p-5">
                                <div class="mb-4 flex items-center justify-between">
                                    <p class="text-sm font-bold text-white">Executive Snapshot</p>
                                    <span class="rounded-full bg-emerald-300/20 px-3 py-1 text-xs font-bold text-emerald-200">Live</span>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-white/10 p-3">
                                        <p class="text-xs text-[color:var(--muted)]">Occupancy</p>
                                        <p class="mt-1 text-xl font-extrabold text-white">89%</p>
                                    </div>
                                    <div class="rounded-xl bg-white/10 p-3">
                                        <p class="text-xs text-[color:var(--muted)]">Net Inflow</p>
                                        <p class="mt-1 text-xl font-extrabold text-white">KSh 2.4M</p>
                                    </div>
                                    <div class="rounded-xl bg-white/10 p-3">
                                        <p class="text-xs text-[color:var(--muted)]">Open Tickets</p>
                                        <p class="mt-1 text-xl font-extrabold text-white">12</p>
                                    </div>
                                    <div class="rounded-xl bg-white/10 p-3">
                                        <p class="text-xs text-[color:var(--muted)]">Renewals</p>
                                        <p class="mt-1 text-xl font-extrabold text-white">36</p>
                                    </div>
                                </div>
                                <div class="mt-4 rounded-xl border border-white/15 bg-black/15 p-4">
                                    <p class="text-xs font-bold uppercase tracking-wide text-[color:var(--gold-300)]">Activity Queue</p>
                                    <div class="mt-3 space-y-2 text-sm text-white">
                                        <div class="flex items-center justify-between"><span>Invoice Run: Kilimani Towers</span><span class="font-bold text-emerald-300">Done</span></div>
                                        <div class="flex items-center justify-between"><span>Lease Renewals Pending</span><span class="font-bold text-amber-300">5 Items</span></div>
                                        <div class="flex items-center justify-between"><span>Escalated Maintenance</span><span class="font-bold text-rose-300">2 Cases</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="px-4 pb-8 sm:px-6 lg:px-8">
                <div class="mx-auto flex w-full max-w-7xl flex-wrap items-center justify-center gap-3 rounded-2xl border border-white/15 bg-white/5 px-4 py-4 text-xs font-bold uppercase tracking-[0.14em] text-[color:var(--muted)] sm:text-sm">
                    <span class="rounded-md bg-white/10 px-3 py-1">Agents</span>
                    <span class="rounded-md bg-white/10 px-3 py-1">Landlords</span>
                    <span class="rounded-md bg-white/10 px-3 py-1">Tenants</span>
                    <span class="rounded-md bg-white/10 px-3 py-1">M-Pesa Integrated</span>
                    <span class="rounded-md bg-white/10 px-3 py-1">Role-Based Security</span>
                </div>
            </section>

            <section class="px-4 pb-10 sm:px-6 lg:px-8">
                <div class="mx-auto grid w-full max-w-7xl gap-4 md:grid-cols-3">
                    <article class="glass rounded-2xl p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[color:var(--gold-300)]">Collections</p>
                        <p class="mt-2 text-sm text-[color:var(--muted)]">Automated rent billing with polished tenant payment experience.</p>
                    </article>
                    <article class="glass rounded-2xl p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[color:var(--gold-300)]">Operations</p>
                        <p class="mt-2 text-sm text-[color:var(--muted)]">Centralized maintenance workflows with real-time status updates.</p>
                    </article>
                    <article class="glass rounded-2xl p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[color:var(--gold-300)]">Visibility</p>
                        <p class="mt-2 text-sm text-[color:var(--muted)]">Executive dashboards and reports for occupancy, arrears, and income.</p>
                    </article>
                </div>
            </section>

            <section id="features" class="px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <div class="reveal r2 mb-8">
                        <p class="text-sm font-bold uppercase tracking-[0.15em] text-[color:var(--gold-300)]">Platform Advantages</p>
                        <h2 class="mt-3 max-w-2xl text-3xl font-extrabold text-white sm:text-4xl">High-end experience, operational depth</h2>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <article class="glass rounded-2xl p-6">
                            <h3 class="text-xl font-extrabold text-white">Portfolio Command</h3>
                            <p class="mt-3 text-[color:var(--muted)]">Control every property and unit with clean occupancy and tenancy visibility.</p>
                        </article>
                        <article class="glass rounded-2xl p-6">
                            <h3 class="text-xl font-extrabold text-white">Lease Precision</h3>
                            <p class="mt-3 text-[color:var(--muted)]">Digitize lifecycle events from drafting to renewals and expiration tracking.</p>
                        </article>
                        <article class="glass rounded-2xl p-6">
                            <h3 class="text-xl font-extrabold text-white">Automated Billing</h3>
                            <p class="mt-3 text-[color:var(--muted)]">Issue invoices and reconcile payments through integrated M-Pesa workflows.</p>
                        </article>
                        <article class="glass rounded-2xl p-6">
                            <h3 class="text-xl font-extrabold text-white">Maintenance Pipeline</h3>
                            <p class="mt-3 text-[color:var(--muted)]">Route tickets from tenant submission to contractor completion with updates.</p>
                        </article>
                        <article class="glass rounded-2xl p-6">
                            <h3 class="text-xl font-extrabold text-white">Role-Tuned UX</h3>
                            <p class="mt-3 text-[color:var(--muted)]">Each persona sees the right data and actions, nothing noisy or irrelevant.</p>
                        </article>
                        <article class="glass rounded-2xl p-6">
                            <h3 class="text-xl font-extrabold text-white">Decision Reports</h3>
                            <p class="mt-3 text-[color:var(--muted)]">Arrears, occupancy, and landlord statements that drive clear business moves.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section id="workflow" class="px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
                <div class="mx-auto grid w-full max-w-7xl gap-4 lg:grid-cols-3">
                    <div class="glass rounded-2xl p-6">
                        <p class="text-sm font-bold uppercase tracking-wide text-[color:var(--gold-300)]">01</p>
                        <h3 class="mt-2 text-2xl font-extrabold text-white">Structure Assets</h3>
                        <p class="mt-3 text-[color:var(--muted)]">Define properties, units, rent baselines, and tenancy assignments.</p>
                    </div>
                    <div class="glass rounded-2xl p-6">
                        <p class="text-sm font-bold uppercase tracking-wide text-[color:var(--gold-300)]">02</p>
                        <h3 class="mt-2 text-2xl font-extrabold text-white">Run Revenue Ops</h3>
                        <p class="mt-3 text-[color:var(--muted)]">Launch invoices, capture payments, and monitor collection performance.</p>
                    </div>
                    <div class="glass rounded-2xl p-6">
                        <p class="text-sm font-bold uppercase tracking-wide text-[color:var(--gold-300)]">03</p>
                        <h3 class="mt-2 text-2xl font-extrabold text-white">Optimize Outcomes</h3>
                        <p class="mt-3 text-[color:var(--muted)]">Use dashboard metrics and reports to sharpen portfolio profitability.</p>
                    </div>
                </div>
            </section>

            <section id="cta" class="px-4 pb-16 pt-4 sm:px-6 sm:pb-20 lg:px-8">
                <div class="mx-auto w-full max-w-4xl rounded-3xl border border-white/20 bg-gradient-to-r from-[color:var(--violet-500)]/35 via-[color:var(--cyan-400)]/20 to-[color:var(--violet-500)]/35 px-6 py-10 text-center sm:px-10 sm:py-14">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Give your rental brand a premium operating core.</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-[color:var(--ink)]">From rent collection to maintenance workflows, make your experience feel first-class for every stakeholder.</p>
                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3.5 text-base font-extrabold text-[color:var(--navy-900)] transition hover:bg-[color:var(--gold-300)]">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3.5 text-base font-extrabold text-[color:var(--navy-900)] transition hover:bg-[color:var(--gold-300)]">Start Free</a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/35 bg-white/10 px-6 py-3.5 text-base font-bold text-white transition hover:bg-white/20">Log In</a>
                        @endauth
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/10 bg-black/15">
            <div class="mx-auto flex w-full max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-[color:var(--muted)] sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} Rentify. Premium property management.</p>
                <p>Laravel + Tailwind</p>
            </div>
        </footer>
    </body>
</html>
