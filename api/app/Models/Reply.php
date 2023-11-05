<?php

namespace App\Models;

use App\Events\ThreadReplied;
use App\Events\ThreadUpdated;
use App\Traits\WithResources;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reply extends Model
{
    use HasFactory, WithResources;

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
        static::created(function (Reply $reply) {
            // We're updating thread whenever reply is added.
            broadcast(new ThreadUpdated($reply->replyable));
        });
    }

    protected function associatedResources(): array
    {
        return [
            'user',
            'user.media'
        ];
    }
}
