<?php

namespace App\Policies;

use App\Models\RetentionEvent;
use App\Models\User;

class RetentionEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('retentionEvent:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RetentionEvent $retentionEvent): bool
    {
        return $user->can('retentionEvent:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RetentionEvent $retentionEvent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RetentionEvent $retentionEvent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RetentionEvent $retentionEvent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RetentionEvent $retentionEvent): bool
    {
        return false;
    }
}
