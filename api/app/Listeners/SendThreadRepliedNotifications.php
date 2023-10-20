<?php

namespace App\Listeners;

use App\Events\ThreadReplied;
use App\Notifications\ThreadReplied as NotificationsThreadReplied;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SendThreadRepliedNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ThreadReplied $event): void
    {
        $replies = $event->reply->replyable()->replies()
            ->whereNot('user_id', Auth::id())
            ->get();
        foreach ($replies as $reply) {
            $reply->user->notify(new NotificationsThreadReplied($event->reply));
        }
    }
}
