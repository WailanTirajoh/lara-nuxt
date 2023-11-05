<?php

namespace App\Models;

use App\Traits\HasTags;
use App\Traits\WithResources;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, HasTags, SoftDeletes, WithResources;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'author_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected function associatedResources(): array
    {
        return [
            'author'
        ];
    }
}
