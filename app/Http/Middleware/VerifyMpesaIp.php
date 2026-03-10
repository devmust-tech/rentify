<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyMpesaIp
{
    /**
     * Safaricom's published IP ranges for M-Pesa callbacks.
     * Source: https://developer.safaricom.co.ke/docs#ip-whitelist
     */
    private const ALLOWED_IPS = [
        '196.201.214.200',
        '196.201.214.206',
        '196.201.213.100',
        '196.201.214.207',
        '196.201.214.208',
        '196.201.213.109',
        '196.201.214.214',
        '196.201.214.215',
        '196.201.214.216',
        '196.201.214.217',
        '196.201.214.218',
        '196.201.214.219',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Skip IP check in local/testing environments
        if (app()->isLocal() || app()->runningUnitTests()) {
            return $next($request);
        }

        $ip = $request->ip();

        if (!in_array($ip, self::ALLOWED_IPS, true)) {
            Log::warning('M-Pesa callback blocked — IP not whitelisted', ['ip' => $ip]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
