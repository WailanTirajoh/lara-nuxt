<?php

namespace App\Policies;

use App\Models\User;

class AppPolicy
{
    protected $model;

    /**
     * Determine whether the user can view any models.
     */
    protected function viewAny(User $user): bool
    {
        return $user->can("{$this->model}-viewAny");
    }

    /**
     * Determine whether the user can view the model.
     */
    protected function view(User $user): bool
    {
        return $user->can("{$this->model}-view");
    }

    /**
     * Determine whether the user can create models.
     */
    protected function create(User $user): bool
    {
        return $user->can("{$this->model}-create");
    }

    /**
     * Determine whether the user can update the model.
     */
    protected function update(User $user): bool
    {
        return $user->can("{$this->model}-update");
    }

    /**
     * Determine whether the user can delete the model.
     */
    protected function delete(User $user): bool
    {
        return $user->can("{$this->model}-delete");
    }

    /**
     * Determine whether the user can restore the model.
     */
    protected function restore(User $user): bool
    {
        return $user->can("{$this->model}-restore");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    protected function forceDelete(User $user): bool
    {
        return $user->can("{$this->model}-forceDelete");
    }
}
