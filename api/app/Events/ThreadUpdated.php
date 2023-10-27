<?php

namespace App\Events;

use App\Http\Resources\ThreadResource;
use App\Models\Thread as ThreadModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ThreadModel $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("thread.{$this->thread->id}"),
        ];
    }

    public function broadcastAs()
    {
        return 'updated';
    }

    public function broadcastWith()
    {
        return [
            'thread' => ThreadResource::make($this->thread),
        ];
    }
}
