<?php

namespace App\Policies;

use App\Models\Response;
use App\Models\User;

class ResponsePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('response:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Response $response): bool
    {
        return $user->can('response:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('response:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Response $response): bool
    {
        return $user->can('response:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Response $response): bool
    {
        return $user->can('response:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Response $response): bool
    {
        return $user->can('response:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Response $response): bool
    {
        return $user->can('response:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('response:reorder');
    }
}
