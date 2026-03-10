<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Rentify Admin — {{ $title ?? 'Dashboard' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <!-- Top navigation -->
            <header class="bg-gray-900 border-b border-white/10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                            </div>
                            <span class="text-white font-bold text-lg">Rentify</span>
                            <span class="rounded-full bg-indigo-500/20 px-2 py-0.5 text-xs font-semibold text-indigo-400 ring-1 ring-indigo-500/30">Super Admin</span>
                        </div>

                        <nav class="flex items-center gap-x-1">
                            <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.organizations.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white transition {{ request()->routeIs('admin.organizations.*') ? 'bg-white/10 text-white' : '' }}">Organizations</a>
                        </nav>

                        <div class="flex items-center gap-x-4">
                            @auth
                                <span class="text-sm text-gray-400">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/10 transition">Sign out</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border-b border-emerald-200 px-4 py-3 text-center text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Page content -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
