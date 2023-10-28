<?php

namespace App\Listeners;

use App\Events\ThreadReplied;
use App\Models\User;
use App\Notifications\ThreadReplied as NotificationsThreadReplied;
use Illuminate\Support\Collection;
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

    private function getAssociatedUsers(ThreadReplied $event): Collection
    {
        $thread = $event->reply->replyable;
        $userIds = $thread->replies()
            ->whereNot('user_id', Auth::id())
            ->groupBy('user_id')
            ->pluck('user_id')
            ->when(
                Auth::id() !== $thread->user_id,
                function (Collection $userIds) use ($thread) {
                    if (! $userIds->contains($thread->user_id)) {
                        $userIds->push($thread->user_id);
                    }
                }
            );

        return User::whereIn('id', $userIds)
            ->get();
    }
}
