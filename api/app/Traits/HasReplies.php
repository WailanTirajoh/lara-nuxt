<?php

namespace App\Traits;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReplies
{
    /**
     * Get all of the post's comments.
     */
    public function replies(): MorphMany
    {
        return $this->morphMany(Reply::class, 'replyable');
    }
}
