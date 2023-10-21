<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'body' => $this->body,
            'replies_count' => $this->replies()->count(),
            'user' => ThreadUserResource::make($this->user),
            'created_at' => $this->created_at,
        ];
    }
}
