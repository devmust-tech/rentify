<?php

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────
// Public routes (bare domain — marketing + org self-registration)
// ─────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::middleware('guest')->group(function () {
    Route::get('/register-org', [App\Http\Controllers\Auth\OrganizationRegistrationController::class, 'create'])->name('org.register');
    Route::post('/register-org', [App\Http\Controllers\Auth\OrganizationRegistrationController::class, 'store']);
});

// Stripe webhook (no CSRF — verified by Stripe signature)
Route::post('/stripe/webhook', [App\Http\Controllers\Agent\BillingController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('stripe.webhook');

// ─────────────────────────────────────────────────────────────────
// Admin panel — admin.{domain}
// ─────────────────────────────────────────────────────────────────
Route::domain('admin.'.config('app.domain'))
    ->name('admin.')
    ->group(function () {

        Route::middleware('guest')->group(function () {
            Route::get('/login', [App\Http\Controllers\Admin\AdminLoginController::class, 'create'])->name('login');
            Route::post('/login', [App\Http\Controllers\Admin\AdminLoginController::class, 'store']);
        });

        Route::middleware(['auth', 'role:admin'])->group(function () {
            Route::post('/logout', [App\Http\Controllers\Admin\AdminLoginController::class, 'destroy'])->name('logout');
            Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/organizations', [App\Http\Controllers\Admin\OrganizationController::class, 'index'])->name('organizations.index');
            Route::get('/organizations/{organization}', [App\Http\Controllers\Admin\OrganizationController::class, 'show'])->name('organizations.show');
            Route::post('/organizations/{organization}/approve', [App\Http\Controllers\Admin\OrganizationController::class, 'approve'])->name('organizations.approve');
            Route::post('/organizations/{organization}/suspend', [App\Http\Controllers\Admin\OrganizationController::class, 'suspend'])->name('organizations.suspend');
            Route::delete('/organizations/{organization}', [App\Http\Controllers\Admin\OrganizationController::class, 'destroy'])->name('organizations.destroy');
            Route::patch('/organizations/{organization}/plan', [App\Http\Controllers\Admin\OrganizationController::class, 'updatePlan'])->name('organizations.plan');
            Route::patch('/organizations/{organization}/users/{user}/role', [App\Http\Controllers\Admin\OrganizationController::class, 'updateUserRole'])->name('organizations.users.role');
            Route::post('/organizations/{organization}/users/{user}/impersonate', [App\Http\Controllers\Admin\ImpersonationController::class, 'start'])->name('organizations.impersonate');
        });
    });

// ─────────────────────────────────────────────────────────────────
// Organization subdomains — {org}.{domain}
// ─────────────────────────────────────────────────────────────────
Route::domain('{org}.'.config('app.domain'))
    ->middleware(['org.active'])
    ->group(function () {

        // Auth routes (login / logout / password reset per subdomain)
        require __DIR__.'/auth.php';

        // Pending approval waiting room
        Route::get('/pending-approval', function () {
            return view('auth.pending-approval');
        })->name('pending-approval');

        // Tenant invitation (signed URLs — no auth required)
        Route::get('/invitation/{tenant}', [App\Http\Controllers\Auth\TenantInvitationController::class, 'show'])
            ->name('tenant.invitation.show');
        Route::post('/invitation/{tenant}', [App\Http\Controllers\Auth\TenantInvitationController::class, 'accept'])
            ->name('tenant.invitation.accept');

        // Landlord invitation (signed URLs — no auth required)
        Route::get('/invitation/landlord/{landlord}', [App\Http\Controllers\Auth\LandlordInvitationController::class, 'show'])
            ->name('landlord.invitation.show');
        Route::post('/invitation/landlord/{landlord}', [App\Http\Controllers\Auth\LandlordInvitationController::class, 'accept'])
            ->name('landlord.invitation.accept');

        // All authenticated org routes
        Route::middleware(['auth', 'org.subdomain', 'sub.active'])->group(function () {

            Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');

            // Admin impersonation leave
            Route::post('/impersonation/leave', [App\Http\Controllers\Admin\ImpersonationController::class, 'leave'])->name('impersonation.leave');

            // Profile
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // ── AGENT ROUTES ──────────────────────────────────────────────
            Route::middleware('role:agent')->prefix('agent')->name('agent.')->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

                Route::resource('properties', App\Http\Controllers\Agent\PropertyController::class);
                Route::delete('properties/{property}/remove-photo', [App\Http\Controllers\Agent\PropertyController::class, 'removePhoto'])->name('properties.remove-photo');
                Route::resource('properties.units', App\Http\Controllers\Agent\UnitController::class);
                Route::resource('landlords', App\Http\Controllers\Agent\LandlordController::class);
                Route::post('/landlords/{landlord}/invite', [App\Http\Controllers\Agent\LandlordController::class, 'sendInvite'])->name('landlords.invite');
                Route::resource('tenants', App\Http\Controllers\Agent\TenantController::class);
                Route::post('/tenants/{tenant}/invite', [App\Http\Controllers\Agent\TenantController::class, 'sendInvite'])->name('tenants.invite');
                Route::resource('leases', App\Http\Controllers\Agent\LeaseController::class);
                Route::post('/leases/{lease}/negotiations/{negotiation}/respond', [App\Http\Controllers\Agent\LeaseController::class, 'respondToNegotiation'])->name('leases.negotiations.respond');
                Route::post('/leases/{lease}/deposit/paid', [App\Http\Controllers\Agent\LeaseController::class, 'markDepositPaid'])->name('leases.deposit.paid');
                Route::post('/leases/{lease}/deposit/refunded', [App\Http\Controllers\Agent\LeaseController::class, 'markDepositRefunded'])->name('leases.deposit.refunded');
                Route::delete('/bulk/properties', [App\Http\Controllers\Agent\PropertyController::class, 'bulkDelete'])->name('properties.bulk-delete');
                Route::delete('/bulk/tenants', [App\Http\Controllers\Agent\TenantController::class, 'bulkDelete'])->name('tenants.bulk-delete');
                Route::delete('/bulk/leases', [App\Http\Controllers\Agent\LeaseController::class, 'bulkDelete'])->name('leases.bulk-delete');
                Route::middleware('feature:invoices')->group(function () {
                    Route::get('/invoices/export', [App\Http\Controllers\Agent\InvoiceController::class, 'export'])->name('invoices.export');
                    Route::resource('invoices', App\Http\Controllers\Agent\InvoiceController::class)->only(['index', 'create', 'store', 'show']);
                    Route::get('/invoices/{invoice}/download', [App\Http\Controllers\Agent\InvoiceController::class, 'download'])->name('invoices.download');
                });
                Route::middleware('feature:payments')->group(function () {
                    Route::get('/payments/export', [App\Http\Controllers\Agent\PaymentController::class, 'export'])->name('payments.export');
                    Route::resource('payments', App\Http\Controllers\Agent\PaymentController::class)->only(['index', 'create', 'store', 'show']);
                });
                Route::middleware('feature:maintenance')->group(function () {
                    Route::resource('maintenance', App\Http\Controllers\Agent\MaintenanceController::class)->only(['index', 'show', 'edit', 'update']);
                    Route::post('/maintenance/{maintenance}/note', [App\Http\Controllers\Agent\MaintenanceController::class, 'addNote'])->name('maintenance.add-note');
                });
                Route::middleware('feature:reports')->group(function () {
                    Route::get('/reports', [App\Http\Controllers\Agent\ReportController::class, 'index'])->name('reports.index');
                    Route::get('/reports/rent-roll', [App\Http\Controllers\Agent\ReportController::class, 'rentRoll'])->name('reports.rent-roll');
                    Route::get('/reports/arrears', [App\Http\Controllers\Agent\ReportController::class, 'arrears'])->name('reports.arrears');
                    Route::get('/reports/occupancy', [App\Http\Controllers\Agent\ReportController::class, 'occupancy'])->name('reports.occupancy');
                    Route::get('/reports/landlord-statement/{landlord}', [App\Http\Controllers\Agent\ReportController::class, 'landlordStatement'])->name('reports.landlord-statement');
                });
                Route::middleware('feature:notifications')->group(function () {
                    Route::get('/notifications/poll', [App\Http\Controllers\Agent\NotificationController::class, 'poll'])->name('notifications.poll');
                    Route::resource('notifications', App\Http\Controllers\Agent\NotificationController::class)->only(['index', 'show', 'create', 'store']);
                });
                Route::middleware('feature:agreements')->group(function () {
                    Route::resource('agreements', App\Http\Controllers\Agent\AgreementController::class)->only(['index', 'create', 'store', 'show']);
                });

                Route::get('/settings', [App\Http\Controllers\Agent\OrganizationSettingsController::class, 'edit'])->name('settings');
                Route::patch('/settings', [App\Http\Controllers\Agent\OrganizationSettingsController::class, 'update'])->name('settings.update');
                Route::get('/billing', [App\Http\Controllers\Agent\BillingController::class, 'index'])->name('billing');
                Route::post('/billing/checkout', [App\Http\Controllers\Agent\BillingController::class, 'checkout'])->name('billing.checkout');
                Route::post('/billing/portal', [App\Http\Controllers\Agent\BillingController::class, 'portal'])->name('billing.portal');
            });

            // ── LANDLORD ROUTES ───────────────────────────────────────────
            Route::middleware('role:landlord')->prefix('landlord')->name('landlord.')->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\Landlord\DashboardController::class, 'index'])->name('dashboard');

                Route::resource('properties', App\Http\Controllers\Landlord\PropertyController::class);
                Route::delete('properties/{property}/remove-photo', [App\Http\Controllers\Landlord\PropertyController::class, 'removePhoto'])->name('properties.remove-photo');
                Route::resource('properties.units', App\Http\Controllers\Landlord\UnitController::class);
                Route::resource('tenants', App\Http\Controllers\Landlord\TenantController::class);
                Route::resource('leases', App\Http\Controllers\Landlord\LeaseController::class);
                Route::post('/leases/{lease}/approve', [App\Http\Controllers\Landlord\LeaseController::class, 'approve'])->name('leases.approve');
                Route::middleware('feature:invoices')->group(function () {
                    Route::resource('invoices', App\Http\Controllers\Landlord\InvoiceController::class)->only(['index', 'create', 'store', 'show']);
                });
                Route::middleware('feature:payments')->group(function () {
                    Route::resource('payments', App\Http\Controllers\Landlord\PaymentController::class)->only(['index', 'create', 'store', 'show']);
                });
                Route::middleware('feature:maintenance')->group(function () {
                    Route::resource('maintenance', App\Http\Controllers\Landlord\MaintenanceController::class)->only(['index', 'show', 'edit', 'update']);
                    Route::post('/maintenance/{maintenance}/note', [App\Http\Controllers\Landlord\MaintenanceController::class, 'addNote'])->name('maintenance.add-note');
                });
                Route::middleware('feature:financials')->group(function () {
                    Route::get('/financials', [App\Http\Controllers\Landlord\FinancialController::class, 'index'])->name('financials.index');
                    Route::get('/financials/statement', [App\Http\Controllers\Landlord\FinancialController::class, 'statement'])->name('financials.statement');
                });
                Route::middleware('feature:agreements')->group(function () {
                    Route::get('/agreements', [App\Http\Controllers\Landlord\AgreementController::class, 'index'])->name('agreements.index');
                    Route::get('/agreements/{agreement}', [App\Http\Controllers\Landlord\AgreementController::class, 'show'])->name('agreements.show');
                    Route::post('/agreements/{agreement}/sign', [App\Http\Controllers\Landlord\AgreementController::class, 'sign'])->name('agreements.sign');
                });
                Route::middleware('feature:notifications')->group(function () {
                    Route::get('/notifications/poll', [App\Http\Controllers\Landlord\NotificationController::class, 'poll'])->name('notifications.poll');
                    Route::get('/notifications', [App\Http\Controllers\Landlord\NotificationController::class, 'index'])->name('notifications.index');
                    Route::get('/notifications/{notification}', [App\Http\Controllers\Landlord\NotificationController::class, 'show'])->name('notifications.show');
                });

                Route::get('/settings', [App\Http\Controllers\Landlord\OrganizationSettingsController::class, 'edit'])->name('settings');
                Route::patch('/settings', [App\Http\Controllers\Landlord\OrganizationSettingsController::class, 'update'])->name('settings.update');
                Route::get('/billing', [App\Http\Controllers\Landlord\BillingController::class, 'index'])->name('billing');
                Route::post('/billing/checkout', [App\Http\Controllers\Landlord\BillingController::class, 'checkout'])->name('billing.checkout');
                Route::post('/billing/portal', [App\Http\Controllers\Landlord\BillingController::class, 'portal'])->name('billing.portal');
            });

            // ── TENANT ROUTES ─────────────────────────────────────────────
            Route::middleware('role:tenant')->prefix('tenant')->name('tenant.')->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
                Route::get('/property', [App\Http\Controllers\Tenant\PropertyController::class, 'show'])->name('property.show');
                Route::get('/lease', [App\Http\Controllers\Tenant\LeaseController::class, 'index'])->name('lease.index');
                Route::post('/lease/{lease}/sign', [App\Http\Controllers\Tenant\LeaseController::class, 'sign'])->name('lease.sign');
                Route::post('/lease/{lease}/negotiate', [App\Http\Controllers\Tenant\LeaseController::class, 'negotiate'])->name('lease.negotiate');
                Route::middleware('feature:invoices')->group(function () {
                    Route::get('/invoices', [App\Http\Controllers\Tenant\InvoiceController::class, 'index'])->name('invoices.index');
                    Route::get('/invoices/{invoice}', [App\Http\Controllers\Tenant\InvoiceController::class, 'show'])->name('invoices.show');
                    Route::get('/invoices/{invoice}/download', [App\Http\Controllers\Tenant\InvoiceController::class, 'download'])->name('invoices.download');
                    Route::post('/invoices/{invoice}/pay', [App\Http\Controllers\Tenant\PaymentController::class, 'initiate'])->name('payments.initiate');
                    Route::post('/invoices/{invoice}/pay/card', [App\Http\Controllers\Tenant\CardPaymentController::class, 'checkout'])->name('invoices.card.checkout');
                    Route::get('/invoices/{invoice}/pay/card/return', [App\Http\Controllers\Tenant\CardPaymentController::class, 'return'])->name('invoices.card.return');
                });
                Route::middleware('feature:payments')->group(function () {
                    Route::get('/payments', [App\Http\Controllers\Tenant\PaymentController::class, 'index'])->name('payments.index');
                    Route::get('/payments/{payment}/status', [App\Http\Controllers\Tenant\PaymentController::class, 'statusCheck'])->name('payments.status');
                });
                Route::middleware('feature:maintenance')->group(function () {
                    Route::resource('maintenance', App\Http\Controllers\Tenant\MaintenanceController::class)->only(['index', 'create', 'store', 'show']);
                });
                Route::middleware('feature:notifications')->group(function () {
                    Route::get('/notifications/poll', [App\Http\Controllers\Tenant\NotificationController::class, 'poll'])->name('notifications.poll');
                    Route::get('/notifications', [App\Http\Controllers\Tenant\NotificationController::class, 'index'])->name('notifications.index');
                    Route::get('/notifications/{notification}', [App\Http\Controllers\Tenant\NotificationController::class, 'show'])->name('notifications.show');
                });
            });
        });
    });
