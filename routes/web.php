<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\SchoolController;
use Illuminate\Support\Facades\Route;

// Central domain routes
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // Include shared authentication routes
        require base_path('routes/auth.php');
        // Central home route
        Route::get('/', function () {
            return auth()->check()
                ? redirect()->route('central.dashboard')
                : redirect()->route('login');
        })->name('central.home');
        // Authenticated routes for central domains
        Route::middleware(['auth', 'super_admin'])->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])->name('central.dashboard');
            // Super Admin routes
                Route::resource('schools', SchoolController::class);
                // Organization management routes
                Route::prefix('organization')->as('organization.')->controller(OrganizationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::post('/store', 'storeOrganization')->name('store');
                });
        });
    });
}
