<?php

namespace App\Policies;

use App\Models\QuestionVariantMatch;
use App\Models\User;

class QuestionVariantMatchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('questionVariantMatch:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuestionVariantMatch $questionVariantMatch): bool
    {
        return $user->can('questionVariantMatch:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('questionVariantMatch:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuestionVariantMatch $questionVariantMatch): bool
    {
        return $user->can('questionVariantMatch:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuestionVariantMatch $questionVariantMatch): bool
    {
        return $user->can('questionVariantMatch:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QuestionVariantMatch $questionVariantMatch): bool
    {
        return $user->can('questionVariantMatch:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QuestionVariantMatch $questionVariantMatch): bool
    {
        return $user->can('questionVariantMatch:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('questionVariantMatch:reorder');
    }
}
