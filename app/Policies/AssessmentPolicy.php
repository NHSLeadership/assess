<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('assessment:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Assessment $assessment): bool
    {
        return $user->can('assessment:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('assessment:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assessment $assessment): bool
    {
        return $user->can('assessment:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assessment $assessment): bool
    {
        return $user->can('assessment:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Assessment $assessment): bool
    {
        return $user->can('assessment:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Assessment $assessment): bool
    {
        return $user->can('assessment:forceDelete');
    }
}
