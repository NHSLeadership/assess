<?php

namespace App\Policies;

use App\Models\AssessmentRater;
use App\Models\User;

class AssessmentRaterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('assessmentRater:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AssessmentRater $assessmentRater): bool
    {
        return $user->can('assessmentRater:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('assessmentRater:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssessmentRater $assessmentRater): bool
    {
        return $user->can('assessmentRater:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssessmentRater $assessmentRater): bool
    {
        return $user->can('assessmentRater:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssessmentRater $assessmentRater): bool
    {
        return $user->can('assessmentRater:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssessmentRater $assessmentRater): bool
    {
        return $user->can('assessmentRater:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('assessmentRater:reorder');
    }
}
