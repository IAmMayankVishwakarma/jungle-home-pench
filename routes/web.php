<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {return view('pages.index');})->name('home');
Route::get('/about', function () {return view('pages.about');})->name('about');
Route::get('/rooms', function () {return view('pages.rooms');})->name('rooms');
Route::get('/room-details', function () {return view('pages.room-details');})->name('room-details');
Route::get('/services', function () {return view('pages.services');})->name('services');
Route::get('/restaurant', function () {return view('pages.restaurant');})->name('restaurant');
Route::get('/image-gallery', function () {return view('pages.image-gallery');})->name('image-gallery');
Route::get('/faq', function () {return view('pages.faq');})->name('faq');
Route::get('/cart', function () {return view('pages.cart');})->name('cart');
Route::get('/coming-soon', function () {return view('pages.coming-soon');})->name('coming-soon');
Route::get('/blog-grid', function () {return view('pages.blog-grid');})->name('blog-grid');
Route::get('/contact', function () {return view('pages.contact');})->name('contact');

// Google OAuth routes
Route::middleware('guest')->group(function () {

    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');

});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

     Route::get('/auth/google/link', [GoogleController::class, 'link'])
        ->name('google.link');

    Route::get('/auth/google/link/callback', [GoogleController::class, 'linkCallback'])
        ->name('google.link.callback');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])
            ->middleware('permission:roles.view,roles.manage')
            ->name('roles.index');
        Route::get('roles/create', [RoleController::class, 'create'])
            ->middleware('permission:roles.manage')
            ->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])
            ->middleware('permission:roles.manage')
            ->name('roles.store');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:roles.manage')
            ->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])
            ->middleware('permission:roles.manage')
            ->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:roles.manage')
            ->name('roles.destroy');

        Route::get('permissions', [PermissionController::class, 'index'])
            ->middleware('permission:permissions.view,permissions.manage')
            ->name('permissions.index');
        Route::get('permissions/create', [PermissionController::class, 'create'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.create');
        Route::post('permissions', [PermissionController::class, 'store'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.store');
        Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.edit');
        Route::put('permissions/{permission}', [PermissionController::class, 'update'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.update');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])
            ->middleware('permission:permissions.manage')
            ->name('permissions.destroy');

        Route::get('access/users', [UserRoleController::class, 'index'])
            ->middleware('permission:users.manage')
            ->name('access.users.index');
        Route::put('access/users/{user}/roles', [UserRoleController::class, 'update'])
            ->middleware('permission:users.manage')
            ->name('access.users.update');
    });

});

// fallback route for any undefined routes
Route::fallback(function () {
    return view('pages.404');
});