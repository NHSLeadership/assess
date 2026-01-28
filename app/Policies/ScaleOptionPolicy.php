<?php

namespace App\Policies;

use App\Models\ScaleOption;
use App\Models\User;

class ScaleOptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('scaleOption:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ScaleOption $scaleOption): bool
    {
        return $user->can('scaleOption:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('scaleOption:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ScaleOption $scaleOption): bool
    {
        return $user->can('scaleOption:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ScaleOption $scaleOption): bool
    {
        return $user->can('scaleOption:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ScaleOption $scaleOption): bool
    {
        return $user->can('scaleOption:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ScaleOption $scaleOption): bool
    {
        return $user->can('scaleOption:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('scaleOption:reorder');
    }
}
