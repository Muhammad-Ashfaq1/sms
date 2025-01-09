<?php

use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Public routes (if any)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Super Admin routes
    Route::middleware('super_admin')->group(function () {
        Route::resource('schools', SchoolController::class);
    });

    Route::controller(OrganizationController::class)->middleware('super_admin')->prefix('organization')->group(function () {
        Route::get('/', 'index')->name('organization.index');
        Route::get('/create', 'create')->name('organization.create');
        Route::post('/store', 'storeOrganization')->name('organization.store');
    });
});
