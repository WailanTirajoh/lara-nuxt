<?php

namespace App\Events;

use App\Http\Resources\ThreadResource;
use App\Models\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Thread implements ShouldBroadcast
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
        return [
            new PrivateChannel("thread.{$this->reply->replyable->id}"),
        ];
    }

    public function broadcastAs()
    {
        return 'detail';
    }

    public function broadcastWith()
    {
        return [
            'thread' => ThreadResource::make($this->reply->replyable),
        ];
    }
}
