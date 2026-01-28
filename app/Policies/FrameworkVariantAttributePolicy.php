<?php

namespace App\Policies;

use App\Models\FrameworkVariantAttribute;
use App\Models\User;

class FrameworkVariantAttributePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('frameworkVariantAttribute:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FrameworkVariantAttribute $frameworkVariantAttribute): bool
    {
        return $user->can('frameworkVariantAttribute:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('frameworkVariantAttribute:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FrameworkVariantAttribute $frameworkVariantAttribute): bool
    {
        return $user->can('frameworkVariantAttribute:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FrameworkVariantAttribute $frameworkVariantAttribute): bool
    {
        return $user->can('frameworkVariantAttribute:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FrameworkVariantAttribute $frameworkVariantAttribute): bool
    {
        return $user->can('frameworkVariantAttribute:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FrameworkVariantAttribute $frameworkVariantAttribute): bool
    {
        return $user->can('frameworkVariantAttribute:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('frameworkVariantAttribute:reorder');
    }
}
