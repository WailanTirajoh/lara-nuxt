<?php

use App\Http\Resources\ChannelUserResource;
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

Broadcast::channel('thread.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('public', function () {
    return true;
});

Broadcast::channel('thread-presence.{id}', function ($user, $id) {
    return ChannelUserResource::make($user);
});
