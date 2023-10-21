<?php

namespace App\Notifications;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\ThreadResource;
use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThreadReplied extends Notification
{
    use Queueable;
    private $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
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
        return [
            'info' => "{$this->reply->user->name} reply a thread.",
            'data' => [
                "thread" => ThreadResource::make($this->reply->replyable),
                "reply" => ReplyResource::make($this->reply)
            ]
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'info' => "{$this->reply->user->name} reply a thread.",
            'data' => [
                "thread" => $this->reply->replyable,
                "reply" => $this->reply
            ]
        ]);
    }
}
