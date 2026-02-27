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

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Inter', sans-serif; }
            .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
            .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 4px; }
            .topbar-search:focus { outline: none; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
            .fade-in-up { animation: fadeInUp 0.4s ease both; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50/80">
        <div class="min-h-screen" x-data="{ sidebarOpen: false }">
            <!-- Mobile sidebar overlay -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 md:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-gray-950/75 backdrop-blur-sm" @click="sidebarOpen = false"></div>
                <div class="fixed inset-y-0 left-0 flex w-72 flex-col" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    <div class="flex grow flex-col overflow-y-auto bg-gray-950 sidebar-scrollbar">
                        <div class="flex h-16 shrink-0 items-center justify-between px-5 border-b border-white/[0.06]">
                            <div class="flex items-center gap-x-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 shadow-lg shadow-indigo-600/30">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                </div>
                                <span class="text-lg font-bold text-white tracking-tight">Rentify</span>
                            </div>
                            <button @click="sidebarOpen = false" class="rounded-md p-1.5 text-gray-500 hover:text-white hover:bg-white/10 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <nav class="flex flex-1 flex-col px-3 pb-4">
                            @include('layouts.sidebar')
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden md:fixed md:inset-y-0 md:z-40 md:flex md:w-72 md:flex-col">
                <div class="flex grow flex-col overflow-y-auto bg-gray-950 sidebar-scrollbar border-r border-white/[0.04]">
                    <div class="flex h-16 shrink-0 items-center px-5 border-b border-white/[0.06]">
                        <div class="flex items-center gap-x-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 shadow-lg shadow-indigo-600/30">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                            </div>
                            <div>
                                <span class="text-base font-bold text-white tracking-tight">Rentify</span>
                                <span class="ml-1.5 rounded-full bg-indigo-600/20 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-400 ring-1 ring-indigo-500/30">Pro</span>
                            </div>
                        </div>
                    </div>
                    <nav class="flex flex-1 flex-col px-3 pb-4">
                        @include('layouts.sidebar')
                    </nav>
                </div>
            </div>

            <!-- Main content -->
            <div class="md:pl-72">
                <!-- Top bar -->
                <div class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200/80 bg-white/95 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" class="rounded-lg p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 md:hidden transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="h-5 w-px bg-gray-200 md:hidden"></div>

                    <div class="flex flex-1 items-center justify-between gap-x-4">
                        @isset($header)
                            <div id="turbo-header" class="flex-1">{{ $header }}</div>
                        @endisset

                        <div class="flex items-center gap-x-2 shrink-0">
                            <!-- Search bar (desktop) -->
                            <div class="hidden lg:flex items-center relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                                </div>
                                <input
                                    type="search"
                                    placeholder="Search..."
                                    class="topbar-search w-48 xl:w-56 rounded-lg border border-gray-200 bg-gray-50 py-1.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                                >
                            </div>

                            <div class="h-5 w-px bg-gray-200 hidden lg:block"></div>

                            <!-- Notifications bell -->
                            @php $unreadCount = auth()->user()->notifications()->unread()->count(); @endphp
                            <a href="{{ route(auth()->user()->role->value . '.notifications.index') }}" class="relative rounded-lg p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition group">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white animate-pulse">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                @endif
                            </a>

                            <!-- Divider -->
                            <div class="h-5 w-px bg-gray-200"></div>

                            <!-- User dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-x-2 rounded-lg px-2 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition group">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 font-bold text-sm text-white shadow-md">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden sm:block max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                    <svg class="h-4 w-4 text-gray-400 transition-transform duration-200 group-hover:text-gray-600" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-60 origin-top-right rounded-xl bg-white py-1.5 shadow-xl ring-1 ring-black/5 focus:outline-none">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 font-bold text-white shadow">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition group">
                                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gray-100 text-gray-500 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            Profile Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-x-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition group">
                                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 transition">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                </div>
                                                Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flash messages -->
                <div id="turbo-flash">
                    <x-flash-message />
                </div>

                <!-- Page Content -->
                <main id="turbo-main" class="py-6 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
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
        @stack('scripts')
    </body>
</html>
