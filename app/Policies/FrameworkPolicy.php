<?php

namespace App\Policies;

use App\Models\Framework;
use App\Models\User;

class FrameworkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('framework:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Framework $framework): bool
    {
        return $user->can('framework:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('framework:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Framework $framework): bool
    {
        return $user->can('framework:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Framework $framework): bool
    {
        return $user->can('framework:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Framework $framework): bool
    {
        return $user->can('framework:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Framework $framework): bool
    {
        return $user->can('framework:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('framework:reorder');
    }
}
