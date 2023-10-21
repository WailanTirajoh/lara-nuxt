<?php

namespace App\Models;

use App\Events\Thread as EventsThread;
use App\Traits\HasReacts;
use App\Traits\HasReplies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    use HasFactory, HasReacts, HasReplies;

    protected $fillable = [
        'body',
        'user_id',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
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
        static::updated(function (Thread $thread) {
            broadcast(new EventsThread($thread));
        });
    }
}
