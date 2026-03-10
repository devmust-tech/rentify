<?php

namespace App\Providers;

use App\Services\FileUploadService;
use App\Services\InvoiceService;
use App\Services\MpesaService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\View;
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
        // Provide a null-safe fallback $currentOrganization for views
        // when no org subdomain is resolved (admin panel, artisan, tests).
        // Use app()->bound() — View::offsetExists() only checks the view's
        // own data, not factory shared data, so it would always override the
        // Eloquent model already shared by ResolveOrganization middleware.
        View::composer('*', function ($view) {
            if (!app()->bound('currentOrganization')) {
                $view->with('currentOrganization', new class {
                    public ?string $id            = null;
                    public string  $name          = 'Rentify';
                    public ?string $slug          = null;
                    public ?string $logo          = null;
                    public string  $primary_color = '#4f46e5';
                    public string  $accent_color  = '#6366f1';
                    public ?string $owner_id      = null;
                    public array   $settings      = [];
                    public ?array  $features      = null;

                    public function hasFeature(string $feature): bool
                    {
                        return true;
                    }

                    public function isOnTrial(): bool
                    {
                        return false;
                    }

                    public function subdomainUrl(): string
                    {
                        return '/';
                    }
                });
            }
        });
    }
}
