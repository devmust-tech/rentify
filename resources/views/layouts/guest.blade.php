<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rentify') }}</title>

        <!-- PWA -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Rentify">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.svg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Inter', sans-serif; }

            /* Animated gradient background for the brand panel */
            .auth-brand-bg {
                background:
                    radial-gradient(ellipse 80% 60% at 20% 10%, rgba(79, 70, 229, 0.35), transparent 55%),
                    radial-gradient(ellipse 60% 50% at 80% 90%, rgba(99, 102, 241, 0.25), transparent 50%),
                    radial-gradient(ellipse 50% 40% at 50% 50%, rgba(129, 140, 248, 0.1), transparent 60%),
                    linear-gradient(160deg, #0f172a 0%, #1e1b4b 40%, #1e293b 100%);
            }

            /* Floating dots pattern */
            .auth-pattern {
                background-image:
                    radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.08) 1px, transparent 0);
                background-size: 32px 32px;
            }

            /* Subtle float animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-12px); }
            }
            .float-slow { animation: float 6s ease-in-out infinite; }
            .float-medium { animation: float 4s ease-in-out infinite 1s; }
            .float-fast { animation: float 5s ease-in-out infinite 2s; }

            /* Glow ring */
            @keyframes glow-pulse {
                0%, 100% { opacity: 0.4; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.05); }
            }
            .glow-ring { animation: glow-pulse 4s ease-in-out infinite; }

            /* Fade in animation */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.6s ease-out forwards;
            }
            .animate-delay-100 { animation-delay: 0.1s; opacity: 0; }
            .animate-delay-200 { animation-delay: 0.2s; opacity: 0; }
            .animate-delay-300 { animation-delay: 0.3s; opacity: 0; }

            /* Premium input styling */
            .auth-input {
                transition: all 0.2s ease;
            }
            .auth-input:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            }

            /* Smooth transitions */
            .auth-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <div class="min-h-screen flex">

            {{-- LEFT SIDE: Brand Panel (hidden on mobile, shown on lg+) --}}
            <div class="hidden lg:flex lg:w-1/2 xl:w-[45%] auth-brand-bg auth-pattern relative overflow-hidden flex-col justify-between p-12">

                {{-- Decorative orbs --}}
                <div class="absolute top-20 left-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl float-slow"></div>
                <div class="absolute bottom-32 right-12 w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl float-medium"></div>
                <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-violet-500/10 rounded-full blur-2xl float-fast"></div>

                {{-- Logo & Brand --}}
                <div class="relative z-10">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-500/40 transition-shadow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">Rentify</span>
                    </a>
                </div>

                {{-- Main messaging --}}
                <div class="relative z-10 flex-1 flex flex-col justify-center max-w-md">
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight">
                        Manage your properties
                        <span class="block mt-1 bg-gradient-to-r from-indigo-300 to-violet-300 bg-clip-text text-transparent">
                            with confidence.
                        </span>
                    </h1>
                    <p class="mt-5 text-base text-slate-300 leading-relaxed">
                        Rentify streamlines property management for agents and landlords. Track leases, collect rent, handle maintenance, and grow your portfolio -- all in one place.
                    </p>

                    {{-- Feature highlights --}}
                    <div class="mt-10 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex-shrink-0 w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Lease & Tenant Management</p>
                                <p class="text-sm text-slate-400 mt-0.5">Create leases, onboard tenants, and track occupancy effortlessly.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex-shrink-0 w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Automated Rent Collection</p>
                                <p class="text-sm text-slate-400 mt-0.5">Generate invoices, track payments, and send reminders automatically.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex-shrink-0 w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.384-3.19A2.625 2.625 0 015.25 9.75V4.5m6.17 10.67l5.384-3.19A2.625 2.625 0 0018.75 9.75V4.5M12 2.25v.75m0 0l-3 3m3-3l3 3m-6 7.5l3 3 3-3" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Maintenance Tracking</p>
                                <p class="text-sm text-slate-400 mt-0.5">Handle repair requests from submission to completion in real time.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial / Social proof --}}
                <div class="relative z-10">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                        <div class="flex items-center gap-1 mb-3">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed italic">
                            "Rentify transformed how we manage our 50+ properties. Rent collection that used to take days now happens automatically."
                        </p>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-violet-400 flex items-center justify-center text-sm font-bold text-white">JK</div>
                            <div>
                                <p class="text-sm font-semibold text-white">James Kamau</p>
                                <p class="text-xs text-slate-400">Property Agent, Nairobi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE: Form Area --}}
            <div class="flex-1 flex flex-col min-h-screen">

                {{-- Mobile header (shown below lg) --}}
                <div class="lg:hidden auth-brand-bg px-6 py-8 text-center">
                    <a href="/" class="inline-flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">Rentify</span>
                    </a>
                    <p class="mt-2 text-sm text-slate-300">Premium Property Management</p>
                </div>

                {{-- Form container --}}
                <div class="flex-1 flex items-center justify-center px-6 py-10 sm:px-8 lg:px-12 xl:px-16 bg-white">
                    <div class="w-full max-w-md animate-fade-in-up">
                        {{ $slot }}
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-white border-t border-gray-100 px-6 py-4 text-center">
                    <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Rentify. All rights reserved.</p>
                </div>
            </div>

        </div>

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('[SW] Registered with scope:', registration.scope);
                        })
                        .catch(function(error) {
                            console.log('[SW] Registration failed:', error);
                        });
                });
            }
        </script>
    </body>
</html>
