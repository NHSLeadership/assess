<?php

namespace App\Policies;

use App\Models\AssessmentVariantSelection;
use App\Models\User;

class AssessmentVariantSelectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('assessmentVariantSelection:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return $user->can('assessmentVariantSelection:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('assessmentVariantSelection:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return $user->can('assessmentVariantSelection:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return $user->can('assessmentVariantSelection:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return $user->can('assessmentVariantSelection:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return $user->can('assessmentVariantSelection:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('assessmentVariantSelection:reorder');
    }
}
