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

                {{-- Hero text --}}
                <div class="relative z-10 max-w-sm">
                    <h1 class="text-3xl xl:text-[2.125rem] font-extrabold leading-[1.2] tracking-tight" style="color: #fff;">
                        Property management,
                        <span style="color: #a5b4fc;">simplified.</span>
                    </h1>
                    <p class="mt-4 text-[15px] leading-relaxed" style="color: #94a3b8;">
                        Track leases, collect rent via M-Pesa, handle maintenance requests, and grow your portfolio — all from one dashboard.
                    </p>

                    {{-- Feature pills --}}
                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/[0.07] rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" style="color: #818cf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-sm" style="color: #cbd5e1;">Lease & tenant management</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/[0.07] rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" style="color: #818cf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                            </div>
                            <p class="text-sm" style="color: #cbd5e1;">Automated invoicing & M-Pesa</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-white/[0.07] rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" style="color: #818cf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            </div>
                            <p class="text-sm" style="color: #cbd5e1;">Real-time analytics & reports</p>
                        </div>
                    </div>
                </div>

                {{-- Testimonial --}}
                <div class="relative z-10">
                    <div class="rounded-2xl p-5" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                        <div class="flex items-center gap-1 mb-2.5">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5" style="color: #fbbf24;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            @endfor
                        </div>
                        <p style="color: #cbd5e1; font-size: 13px; line-height: 1.6;">
                            "Rentify transformed how we manage our 50+ properties. Rent collection that used to take days now happens automatically."
                        </p>
                        <div class="mt-3.5 flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-400 flex items-center justify-center text-xs font-bold" style="color: #fff;">JK</div>
                            <div>
                                <p style="color: #ffffff; font-size: 14px; font-weight: 500;">James Kamau</p>
                                <p style="color: #64748b; font-size: 12px;">Property Agent, Nairobi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Form Area --}}
            <div class="flex-1 flex flex-col min-h-screen bg-white">

                {{-- Mobile header --}}
                <div class="lg:hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-6 py-6 text-center">
                    <a href="/" class="inline-flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/25">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight" style="color: #fff;">Rentify</span>
                    </a>
                </div>

                {{-- Form --}}
                <div class="flex-1 flex items-center justify-center px-6 py-10 sm:px-8 lg:px-12">
                    <div class="w-full max-w-[420px]">
                        {{ $slot }}
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 text-center border-t border-gray-100">
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
