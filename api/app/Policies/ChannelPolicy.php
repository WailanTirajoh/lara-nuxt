<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;

class ChannelPolicy extends AppPolicy
{
    protected $model = 'channel';

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Channel $channel = null): bool
    {
        return parent::update($user)
            && $channel->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Channel $channel = null): bool
    {
        return parent::delete($user)
            && $channel->created_by === $user->id;
    }
}
