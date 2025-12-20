<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if (
                array_any(
                    $user->permissions() ?? [],
                    fn($permission) => ($permission['permission_name'] ?? null) === $ability)) {
                return true;
            }

            return null;
        });
    }
}
