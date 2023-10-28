<?php

namespace App\Listeners;

use App\Events\ThreadReplied;
use App\Models\User;
use App\Notifications\ThreadReplied as NotificationsThreadReplied;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SendThreadRepliedNotifications
{
    public function handle(ThreadReplied $event): void
    {
        Notification::send(
            $this->getAssociatedUsers($event),
            new NotificationsThreadReplied($event->reply)
        );
    }

    private function getAssociatedUsers(ThreadReplied $event)
    {
        $userIds = $event->reply->replyable->replies()
            ->whereNot('user_id', Auth::id())
            ->distinct('user_id')
            ->pluck('user_id');

        return User::whereIn('id', $userIds)
            ->get();
    }
}
