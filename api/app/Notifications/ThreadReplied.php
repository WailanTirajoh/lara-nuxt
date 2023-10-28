<?php

namespace App\Notifications;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\ThreadResource;
use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ThreadReplied extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Reply $reply)
    {
        $this->reply = $reply;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->broadcastData();
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->broadcastData());
    }

    private function broadcastData(): array
    {
        return [
            'info' => "{$this->reply->user->name} reply a thread.",
            'data' => [
                "thread" => ThreadResource::make($this->reply->replyable),
                "reply" => ReplyResource::make($this->reply)
            ]
        ];
    }
}
