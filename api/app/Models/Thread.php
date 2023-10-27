<?php

namespace App\Models;

use App\Events\ThreadCreated;
use App\Events\ThreadDeleting;
use App\Events\ThreadUpdated;
use App\Traits\HasReacts;
use App\Traits\HasReplies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Thread extends Model
{
    use HasFactory, HasReacts, HasReplies, LogsActivity;

    protected $fillable = [
        'body',
        'user_id',
    ];

    protected $dispatchesEvents = [
        'created' => ThreadCreated::class,
        'updated' => ThreadUpdated::class,
        'deleting' => ThreadDeleting::class,
    ];

    // Only log update, to show histories of edited thread.
    protected static $recordEvents = ['updated'];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")
            ->logOnly(['body'])
            ->dontLogIfAttributesChangedOnly(['body'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
