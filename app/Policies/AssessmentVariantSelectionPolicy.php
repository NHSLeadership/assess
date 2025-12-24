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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssessmentVariantSelection $assessmentVariantSelection): bool
    {
        return true;
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return true;
    }
}
