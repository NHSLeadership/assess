<?php

namespace App\Policies;

use App\Models\QuestionVariant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionVariantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('questionVariant:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuestionVariant $questionVariant): bool
    {
        return $user->can('questionVariant:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('questionVariant:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuestionVariant $questionVariant): bool
    {
        return $user->can('questionVariant:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuestionVariant $questionVariant): bool
    {
        return $user->can('questionVariant:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QuestionVariant $questionVariant): bool
    {
        return $user->can('questionVariant:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QuestionVariant $questionVariant): bool
    {
        return $user->can('questionVariant:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('questionVariant:reorder');
    }
}
