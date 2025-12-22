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
            $auth0Permission = "assessment:{$ability}";

            $permissions = $user->getAuth0Permissions();
            $hasPermission = array_any(
                $permissions,
                fn($permission) => ($permission['permission_name'] ?? null) === $auth0Permission
            );
            if (! $hasPermission) {
                return false;
            }

            return null;
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
