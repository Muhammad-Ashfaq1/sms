<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\SchoolController;
use Illuminate\Support\Facades\Route;


Route::get('/send-test-email', function () {
    $to = 'recipient@example.com'; // Change this to the recipient's email

    \Illuminate\Support\Facades\Mail::to($to)->send(new \App\Mail\TestEmail());

    return "Test email sent to {$to}";
});


// Central domain routes
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        // Include shared authentication routes
        require base_path('routes/auth.php');

        /*routes for super admin*/
        require base_path('routes/super-admin.php');


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
        });
    });
}
