<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            $parts = explode(' ', trim($ability), 2);
            $permission = count($parts) === 2
                ? $parts[1].'.'.$parts[0]
                : $ability;

            return $user->hasPermission($permission) ? true : null;
        });
    }
}
