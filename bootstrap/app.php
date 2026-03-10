<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\ResolveOrganization::class,
        ]);
        $middleware->redirectGuestsTo(function ($request) {
            if ($org = $request->route('org')) {
                return route('login', ['org' => $org]);
            }
            return route('login');
        });
        $middleware->alias([
            'role'          => \App\Http\Middleware\RoleMiddleware::class,
            'org.active'    => \App\Http\Middleware\EnsureOrganizationActive::class,
            'org.subdomain' => \App\Http\Middleware\EnsureCorrectSubdomain::class,
            'feature'       => \App\Http\Middleware\CheckFeature::class,
            'mpesa.ip'      => \App\Http\Middleware\VerifyMpesaIp::class,
            'sub.active'    => \App\Http\Middleware\EnsureSubscriptionActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
