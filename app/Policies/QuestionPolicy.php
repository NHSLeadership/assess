<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('question:viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Question $question): bool
    {
        return $user->can('question:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('question:create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
    {
        return $user->can('question:update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->can('question:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Question $question): bool
    {
        return $user->can('question:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        return $user->can('question:forceDelete');
    }

    /**
     * Determine whether records can be reordered in a table.
     */
    public function reorder(User $user): bool
    {
        return $user->can('question:reorder');
    }
}
