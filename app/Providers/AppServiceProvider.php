<?php

namespace App\Providers;

use App\Services\FileUploadService;
use App\Services\InvoiceService;
use App\Services\MpesaService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MpesaService::class);
        $this->app->singleton(InvoiceService::class);
        $this->app->singleton(PaymentService::class);
        $this->app->singleton(NotificationService::class);
        $this->app->singleton(FileUploadService::class);
    }

    public function boot(): void
    {
        //
    }
}
