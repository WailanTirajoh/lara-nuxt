<?php

namespace App\Events;

use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadReplied implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Reply $reply)
    {
        $this->reply = $reply;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channel = '';

        if ($this->reply->replyable_type === "App\Models\Thread") {
            $channel = "thread";
        }

        return [
            new PrivateChannel("{$channel}.{$this->reply->replyable->id}"),
        ];
    }

    public function broadcastAs()
    {
        return 'replied';
    }

    public function broadcastWith()
    {
        return [
            'reply' => ReplyResource::make($this->reply),
        ];
    }
}
