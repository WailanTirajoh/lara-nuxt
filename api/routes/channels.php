<?php

use App\Http\Resources\ChannelUserResource;
use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel.{id}', function ($user, $id) {
    $channel = Channel::find($id);

    return in_array($user->id, $channel->users->pluck('id')->toArray());
});

Broadcast::channel('thread.{id}', function ($user, $id) {
    $thread = Thread::find($id);

    return in_array($user->id, $thread->channel->users->pluck('id')->toArray());
});

Broadcast::channel('thread-presence.{id}', function ($user, $id) {
    return ChannelUserResource::make($user);
});
