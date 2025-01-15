<?php

use App\Http\Controllers\Admin\OrganizationController;
use Illuminate\Support\Facades\Route;


/*Organization management routes*/
Route::middleware(['auth', 'super_admin'])->prefix('organization')->as('organization.')->controller(OrganizationController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'storeOrganization')->name('store');
    Route::post('/update/{tenant}', [OrganizationController::class, 'update'])->name('update');
    Route::get('/edit/{org}', [OrganizationController::class , 'edit'])->name('edit');
    Route::delete('/destroy/{tenant}', 'destroy')->name('destroy'); // Delete organization
});
