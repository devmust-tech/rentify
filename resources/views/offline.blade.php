<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offline - Rentify</title>
    <meta name="theme-color" content="#4f46e5">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        @import url('https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f9fafb;
            color: #111827;
        }

        /* Header bar mimicking the sidebar dark feel */
        .header {
            background: linear-gradient(135deg, #111827 0%, #1e1b4b 100%);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .header-icon {
            width: 2rem;
            height: 2rem;
            background: #4f46e5;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .header-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: white;
        }

        .header-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.025em;
        }

        /* Main content area */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        /* Offline illustration */
        .illustration {
            width: 10rem;
            height: 10rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .illustration .circle {
            width: 10rem;
            height: 10rem;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration .circle svg {
            width: 4.5rem;
            height: 4.5rem;
            color: #6366f1;
        }

        /* Decorative pulse ring */
        .illustration::before {
            content: '';
            position: absolute;
            inset: -0.5rem;
            border-radius: 50%;
            border: 2px dashed #c7d2fe;
            animation: spin 20s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .subtitle {
            font-size: 0.9375rem;
            color: #6b7280;
            max-width: 24rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .retry-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            background: #4f46e5;
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px 0 rgba(79, 70, 229, 0.3), 0 1px 2px -1px rgba(79, 70, 229, 0.3);
        }

        .retry-btn:hover {
            background: #4338ca;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3), 0 2px 4px -2px rgba(79, 70, 229, 0.3);
            transform: translateY(-1px);
        }

        .retry-btn:active {
            transform: translateY(0);
        }

        .retry-btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* Tips section */
        .tips {
            margin-top: 2.5rem;
            padding: 1.25rem 1.5rem;
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            max-width: 24rem;
            text-align: left;
        }

        .tips-title {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.625rem;
        }

        .tips ul {
            list-style: none;
            padding: 0;
        }

        .tips li {
            font-size: 0.8125rem;
            color: #6b7280;
            padding: 0.25rem 0;
            padding-left: 1.25rem;
            position: relative;
        }

        .tips li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.625rem;
            width: 0.375rem;
            height: 0.375rem;
            background: #c7d2fe;
            border-radius: 50%;
        }

        /* Footer */
        .footer {
            padding: 1rem 1.5rem;
            text-align: center;
            border-top: 1px solid #f3f4f6;
        }

        .footer p {
            font-size: 0.75rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
            </svg>
        </div>
        <span class="header-title">Rentify</span>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="illustration">
            <div class="circle">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636a9 9 0 11-12.728 0M12 9v4m0 4h.01"/>
                </svg>
            </div>
        </div>

        <h1 class="title">You're offline</h1>
        <p class="subtitle">
            It looks like you've lost your internet connection. Rentify needs an active connection to load your property data.
        </p>

        <button class="retry-btn" onclick="window.location.reload()">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Try again
        </button>

        <div class="tips">
            <p class="tips-title">Troubleshooting tips</p>
            <ul>
                <li>Check that Wi-Fi or mobile data is turned on</li>
                <li>Try moving closer to your router</li>
                <li>Switch between Wi-Fi and mobile data</li>
                <li>Restart your browser or device</li>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 Rentify. All rights reserved.</p>
    </div>
</body>
</html>
