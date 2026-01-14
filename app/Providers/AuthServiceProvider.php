<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability, $arguments) {
            // Determine the model (instance or class name)
            $model = $arguments[0] ?? null;

            if (! $model) {
                return null;
            }

            // Support both: Assessment::class and new Assessment()
            if (is_string($model)) {
                $prefix = Str::camel(class_basename($model));
            } else {
                $prefix = Str::camel(class_basename($model::class));
            }

            // Build the required permission
            $requiredPermission = "{$prefix}:{$ability}";

            // If user has the permission return NULL to continue to normal policy (\App\Policies\*) checks
            return $user->can($requiredPermission) ? null : false;
        });
    }
}
