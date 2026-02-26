<?php

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PWA offline fallback
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');

    // ======= AGENT ROUTES =======
    Route::middleware('role:agent')->prefix('agent')->name('agent.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('properties', App\Http\Controllers\Agent\PropertyController::class);
        Route::delete('properties/{property}/remove-photo', [App\Http\Controllers\Agent\PropertyController::class, 'removePhoto'])->name('properties.remove-photo');
        Route::resource('properties.units', App\Http\Controllers\Agent\UnitController::class);
        Route::resource('landlords', App\Http\Controllers\Agent\LandlordController::class);
        Route::resource('tenants', App\Http\Controllers\Agent\TenantController::class);
        Route::resource('leases', App\Http\Controllers\Agent\LeaseController::class);
        Route::post('/leases/{lease}/negotiations/{negotiation}/respond', [App\Http\Controllers\Agent\LeaseController::class, 'respondToNegotiation'])->name('leases.negotiations.respond');
        Route::resource('invoices', App\Http\Controllers\Agent\InvoiceController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('payments', App\Http\Controllers\Agent\PaymentController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('maintenance', App\Http\Controllers\Agent\MaintenanceController::class)->only(['index', 'show', 'edit', 'update']);
        Route::post('/maintenance/{maintenance}/note', [App\Http\Controllers\Agent\MaintenanceController::class, 'addNote'])->name('maintenance.add-note');

        Route::get('/reports', [App\Http\Controllers\Agent\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/rent-roll', [App\Http\Controllers\Agent\ReportController::class, 'rentRoll'])->name('reports.rent-roll');
        Route::get('/reports/arrears', [App\Http\Controllers\Agent\ReportController::class, 'arrears'])->name('reports.arrears');
        Route::get('/reports/occupancy', [App\Http\Controllers\Agent\ReportController::class, 'occupancy'])->name('reports.occupancy');
        Route::get('/reports/landlord-statement/{landlord}', [App\Http\Controllers\Agent\ReportController::class, 'landlordStatement'])->name('reports.landlord-statement');

        Route::resource('notifications', App\Http\Controllers\Agent\NotificationController::class)->only(['index', 'show', 'create', 'store']);

        Route::resource('agreements', App\Http\Controllers\Agent\AgreementController::class)->only(['index', 'create', 'store', 'show']);
    });

    // ======= LANDLORD ROUTES =======
    Route::middleware('role:landlord')->prefix('landlord')->name('landlord.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Landlord\DashboardController::class, 'index'])->name('dashboard');

        // Properties (full CRUD for self-managed)
        Route::resource('properties', App\Http\Controllers\Landlord\PropertyController::class);
        Route::delete('properties/{property}/remove-photo', [App\Http\Controllers\Landlord\PropertyController::class, 'removePhoto'])->name('properties.remove-photo');

        // Units (nested under properties)
        Route::resource('properties.units', App\Http\Controllers\Landlord\UnitController::class);

        // Tenants (full CRUD)
        Route::resource('tenants', App\Http\Controllers\Landlord\TenantController::class);

        // Leases (full CRUD + approve)
        Route::resource('leases', App\Http\Controllers\Landlord\LeaseController::class);
        Route::post('/leases/{lease}/approve', [App\Http\Controllers\Landlord\LeaseController::class, 'approve'])->name('leases.approve');

        // Invoices
        Route::resource('invoices', App\Http\Controllers\Landlord\InvoiceController::class)->only(['index', 'create', 'store', 'show']);

        // Payments
        Route::resource('payments', App\Http\Controllers\Landlord\PaymentController::class)->only(['index', 'create', 'store', 'show']);

        // Maintenance
        Route::resource('maintenance', App\Http\Controllers\Landlord\MaintenanceController::class)->only(['index', 'show', 'edit', 'update']);
        Route::post('/maintenance/{maintenance}/note', [App\Http\Controllers\Landlord\MaintenanceController::class, 'addNote'])->name('maintenance.add-note');

        // Financials (existing read-only reports)
        Route::get('/financials', [App\Http\Controllers\Landlord\FinancialController::class, 'index'])->name('financials.index');
        Route::get('/financials/statement', [App\Http\Controllers\Landlord\FinancialController::class, 'statement'])->name('financials.statement');

        // Agreements
        Route::get('/agreements', [App\Http\Controllers\Landlord\AgreementController::class, 'index'])->name('agreements.index');
        Route::get('/agreements/{agreement}', [App\Http\Controllers\Landlord\AgreementController::class, 'show'])->name('agreements.show');
        Route::post('/agreements/{agreement}/sign', [App\Http\Controllers\Landlord\AgreementController::class, 'sign'])->name('agreements.sign');

        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Landlord\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{notification}', [App\Http\Controllers\Landlord\NotificationController::class, 'show'])->name('notifications.show');
    });

    // ======= TENANT ROUTES =======
    Route::middleware('role:tenant')->prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/lease', [App\Http\Controllers\Tenant\LeaseController::class, 'index'])->name('lease.index');
        Route::post('/lease/{lease}/sign', [App\Http\Controllers\Tenant\LeaseController::class, 'sign'])->name('lease.sign');
        Route::post('/lease/{lease}/negotiate', [App\Http\Controllers\Tenant\LeaseController::class, 'negotiate'])->name('lease.negotiate');
        Route::get('/invoices', [App\Http\Controllers\Tenant\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [App\Http\Controllers\Tenant\InvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/invoices/{invoice}/pay', [App\Http\Controllers\Tenant\PaymentController::class, 'initiate'])->name('payments.initiate');
        Route::get('/payments', [App\Http\Controllers\Tenant\PaymentController::class, 'index'])->name('payments.index');
        Route::resource('maintenance', App\Http\Controllers\Tenant\MaintenanceController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/notifications', [App\Http\Controllers\Tenant\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{notification}', [App\Http\Controllers\Tenant\NotificationController::class, 'show'])->name('notifications.show');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
