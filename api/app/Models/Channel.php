<?php

namespace App\Models;

use App\Traits\WithResources;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory, WithResources;

    protected $fillable = [
        'created_by',
        'name',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    protected function associatedResources(): array
    {
        return [
            'users',
        ];
    }
}
