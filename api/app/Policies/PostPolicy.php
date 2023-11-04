<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy extends AppPolicy
{
    protected $model = 'post';

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post = null): bool
    {
        return parent::update($user)
            && $post->author_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post = null): bool
    {
        return parent::delete($user)
            && $post->author_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post = null): bool
    {
        return parent::restore($user)
            && $post->author_id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post = null): bool
    {
        return parent::forceDelete($user)
            && $post->author_id == $user->id;
    }
}
