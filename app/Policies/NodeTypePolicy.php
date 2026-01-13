<?php

namespace App\Policies;

use App\Models\NodeType;
use App\Models\User;

class NodeTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('nodeType:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NodeType $nodeType): bool
    {
        return $user->can('nodeType:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('nodeType:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NodeType $nodeType): bool
    {
        return $user->can('nodeType:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NodeType $nodeType): bool
    {
        return $user->can('nodeType:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NodeType $nodeType): bool
    {
        return $user->can('nodeType:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NodeType $nodeType): bool
    {
        return $user->can('nodeType:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('nodeType:reorder');
    }
}
