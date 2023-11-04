<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;

class ThreadPolicy extends AppPolicy
{
    protected $model = 'thread';
    private Channel $channel;

    public function __construct(Request $request)
    {
        $this->channel = $request->route('channel');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return parent::viewAny($user) && $this->isUserInChannel($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return parent::view($user) && $this->isUserInChannel($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return parent::create($user) && $this->isUserInChannel($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Thread $thread = null): bool
    {
        return parent::update($user)
            && $this->isUserInChannel($user)
            && $thread->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?Thread $thread = null): bool
    {
        return parent::delete($user)
            && $this->isUserInChannel($user)
            && $thread->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ?Thread $thread = null): bool
    {
        return parent::restore($user)
            && $this->isUserInChannel($user)
            && $thread->user_id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ?Thread $thread = null): bool
    {
        return parent::forceDelete($user)
            && $this->isUserInChannel($user)
            && $thread->user_id == $user->id;
    }

    /**
     * Determine whether the user is in the channel
     */
    private function isUserInChannel(User $user): bool
    {
        return $this->channel->users()->pluck('id')->contains($user->id);
    }
}
