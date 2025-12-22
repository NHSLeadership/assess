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

            // Fetch user permissions
            $permissions = $user->getAuth0Permissions();

            $hasPermission = array_any(
                $permissions,
                fn($p) => ($p['permission_name'] ?? null) === $requiredPermission
            );

            // If user has the permission return NULL to continue to normal policy (\App\Policies\*) checks
            return $hasPermission ? null : false;
        });
    }

    protected $policies = [
        \App\Models\Assessment::class => \App\Policies\AssessmentPolicy::class,
        \App\Models\AssessmentRater::class => \App\Policies\AssessmentRaterPolicy::class,
        \App\Models\AssessmentVariantSelection::class => \App\Policies\AssessmentVariantSelectionPolicy::class,
        \App\Models\Framework::class => \App\Policies\FrameworkPolicy::class,
        \App\Models\FrameworkVariantAttribute::class => \App\Policies\FrameworkVariantAttributePolicy::class,
        \App\Models\FrameworkVariantOption::class => \App\Policies\FrameworkVariantOptionPolicy::class,
        \App\Models\Node::class => \App\Policies\NodePolicy::class,
        \App\Models\NodeType::class => \App\Policies\NodeTypePolicy::class,
        \App\Models\Question::class => \App\Policies\QuestionPolicy::class,
        \App\Models\QuestionVariantMatch::class => \App\Policies\QuestionVariantMatchPolicy::class,
        \App\Models\QuestionVariant::class => \App\Policies\QuestionVariantPolicy::class,
        \App\Models\Rater::class => \App\Policies\RaterPolicy::class,
        \App\Models\Response::class => \App\Policies\ResponsePolicy::class,
        \App\Models\ScaleOption::class => \App\Policies\ScaleOptionPolicy::class,
        \App\Models\Scale::class => \App\Policies\ScalePolicy::class,
        \App\Models\Signpost::class => \App\Policies\SignpostPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];
}
