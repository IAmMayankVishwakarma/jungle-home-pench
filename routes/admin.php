<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Auth\VerifyEmailLinkController;

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/admin/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('room-types', [RoomTypeController::class, 'index'])
            ->middleware('permission:rooms.view,rooms.manage')
            ->name('room-types.index');
        Route::get('room-types/create', [RoomTypeController::class, 'create'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.create');
        Route::post('room-types', [RoomTypeController::class, 'store'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.store');
        Route::get('room-types/{roomType}', [RoomTypeController::class, 'show'])
            ->middleware('permission:rooms.view,rooms.manage')
            ->name('room-types.show');
        Route::get('room-types/{roomType}/edit', [RoomTypeController::class, 'edit'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.edit');
        Route::put('room-types/{roomType}', [RoomTypeController::class, 'update'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.update');
        Route::delete('room-types/{roomType}', [RoomTypeController::class, 'destroy'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.destroy');
        Route::patch('room-types/{roomType}/toggle-active', [RoomTypeController::class, 'toggleActive'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.toggle-active');

        Route::post('room-types/{roomType}/generate', [AccommodationController::class, 'generate'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.generate');
        Route::post('room-types/{roomType}/images', [RoomImageController::class, 'store'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.images.store');
        Route::delete('room-types/{roomType}/images/{image}', [RoomImageController::class, 'destroy'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.images.destroy');
        Route::post('room-types/{roomType}/images/reorder', [RoomImageController::class, 'reorder'])
            ->middleware('permission:rooms.manage')
            ->name('room-types.images.reorder');

        Route::get('accommodations', [AccommodationController::class, 'index'])
            ->middleware('permission:rooms.view,rooms.manage,housekeeping.manage')
            ->name('accommodations.index');
        Route::patch('accommodations/{room}/status', [AccommodationController::class, 'updateStatus'])
            ->middleware('permission:rooms.manage,housekeeping.manage')
            ->name('accommodations.status.update');

        Route::get('room-catalog', [AmenityController::class, 'index'])
            ->middleware('permission:rooms.view,rooms.manage')
            ->name('room-catalog.index');
        Route::post('amenities', [AmenityController::class, 'store'])
            ->middleware('permission:rooms.manage')
            ->name('amenities.store');
        Route::put('amenities/{amenity}', [AmenityController::class, 'update'])
            ->middleware('permission:rooms.manage')
            ->name('amenities.update');
        Route::delete('amenities/{amenity}', [AmenityController::class, 'destroy'])
            ->middleware('permission:rooms.manage')
            ->name('amenities.destroy');
        Route::post('features', [FeatureController::class, 'store'])
            ->middleware('permission:rooms.manage')
            ->name('features.store');
        Route::put('features/{feature}', [FeatureController::class, 'update'])
            ->middleware('permission:rooms.manage')
            ->name('features.update');
        Route::delete('features/{feature}', [FeatureController::class, 'destroy'])
            ->middleware('permission:rooms.manage')
            ->name('features.destroy');

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

Route::middleware(['auth', 'verified', 'password.confirm'])->group(function () {

    Route::view(
        '/admin/settings/two-factor',
        'Auth.two-factor-settings'
    )->name('two-factor.settings');

});

Route::get('/email/verify/{id}/{hash}', VerifyEmailLinkController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');