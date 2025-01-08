<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\TeacherController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id');
    });

    // Teacher routes
    Route::resource('teachers', TeacherController::class)->names('tenant.teachers');

    Route::get('/dashboard', function () {
        // This will show the current tenant and logged in user
        dd([
            'tenant' => tenant()->id,
            'user' => auth()->user(),
            'roles' => auth()->user()->roles->pluck('name')
        ]);
    })->middleware('auth')->name('tenant.dashboard');
});
