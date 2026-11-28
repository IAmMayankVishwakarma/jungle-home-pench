<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUserHasPermission;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin routes are grouped and named inside routes/admin.php.
            Route::middleware(['web'])
                ->group(base_path('routes/admin.php'));

            // Booking and availability routes are grouped and named inside routes/booking.php.
            Route::middleware(['web', 'auth', 'verified'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/booking.php'));
            
            // Google OAuth routes are fully defined in routes/google.php.
            Route::middleware(['web'])
                ->group(base_path('routes/google.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => EnsureUserHasPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
