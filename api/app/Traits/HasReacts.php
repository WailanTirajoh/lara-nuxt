<?php

namespace App\Traits;

use App\Models\React;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReacts
{
    /**
     * Get all of the post's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(React::class, 'reactable');
    }
}
