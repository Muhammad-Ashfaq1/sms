<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Tenant\ClassRoomController;
use App\Http\Controllers\Tenant\StudentController;
use App\Http\Controllers\Tenant\TeacherController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return auth()->check()
            ? redirect()->route('tenant.dashboard')
            : redirect()->route('login');
    })->name('tenant.home');
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);
    });
    Route::post('logout', [LoginController::class, 'logout'])
        ->name('logout')
        ->middleware('auth');

    // Protected routes
    Route::middleware(['tenant.auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('tenant.dashboard');
        })->name('tenant.dashboard');

        // Teacher routes
        Route::resource('teachers', TeacherController::class)->names('tenant.teachers');

        // Student routes
        Route::resource('students', StudentController::class)->names('tenant.students');

        // Class routes
        Route::resource('classes', ClassRoomController::class)->names('tenant.classes');
    });
});
