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
            'replies_count' => $this->whenCounted('replies'),
            'user' => ThreadUserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'histories' => ThreadUpdateHistoryResource::collection($this->whenLoaded('activities')),
        ];
    }
}
