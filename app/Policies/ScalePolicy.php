<?php

namespace App\Policies;

use App\Models\Scale;
use App\Models\User;

class ScalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('scale:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Scale $scale): bool
    {
        return $user->can('scale:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('scale:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Scale $scale): bool
    {
        return $user->can('scale:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Scale $scale): bool
    {
        return $user->can('scale:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Scale $scale): bool
    {
        return $user->can('scale:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Scale $scale): bool
    {
        return $user->can('scale:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('scale:reorder');
    }
}
