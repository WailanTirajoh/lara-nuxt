<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class React extends Model
{
    use HasFactory;

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}
