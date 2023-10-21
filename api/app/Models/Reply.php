<?php

namespace App\Models;

use App\Events\Thread;
use App\Events\ThreadReplied;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
    ];

    protected $dispatchesEvents = [
        'created' => ThreadReplied::class,
    ];

    public function replyable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        /**
         * To update frontend Thread Detail.
         */
        static::created(function (Reply $reply) {
            broadcast(new Thread($reply->replyable));
        });
    }
}
