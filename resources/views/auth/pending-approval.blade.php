<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending Approval — Rentify</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --brand: {{ $currentOrganization->primary_color ?? '#4f46e5' }}; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md text-center">
            <!-- Icon -->
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-amber-100">
                <svg class="h-10 w-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900">Your Workspace is Under Review</h1>
            <p class="mt-3 text-gray-500 leading-relaxed">
                Thank you for signing up with <strong>{{ $currentOrganization->name ?? 'Rentify' }}</strong>.
                Our team is reviewing your workspace and will activate it shortly.
            </p>
            <p class="mt-2 text-sm text-gray-400">You'll receive an email once you're approved.</p>

            <div class="mt-8">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
