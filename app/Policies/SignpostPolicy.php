<?php

namespace App\Policies;

use App\Models\Signpost;
use App\Models\User;

class SignpostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('signpost:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Signpost $signpost): bool
    {
        return $user->can('signpost:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('signpost:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Signpost $signpost): bool
    {
        return $user->can('signpost:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Signpost $signpost): bool
    {
        return $user->can('signpost:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Signpost $signpost): bool
    {
        return $user->can('signpost:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Signpost $signpost): bool
    {
        return $user->can('signpost:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('signpost:reorder');
    }
}
