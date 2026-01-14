<?php

namespace App\Policies;

use App\Models\Rater;
use App\Models\User;

class RaterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('rater:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rater $rater): bool
    {
        return $user->can('rater:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('rater:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rater $rater): bool
    {
        return $user->can('rater:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rater $rater): bool
    {
        return $user->can('rater:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rater $rater): bool
    {
        return $user->can('rater:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rater $rater): bool
    {
        return $user->can('rater:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('rater:reorder');
    }
}
