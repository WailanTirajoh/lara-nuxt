<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WithResources
{
    public function scopeWithResources(Builder $query): void
    {
        $query->with($this->associatedResources());
    }

    public function loadResources(): self
    {
        return $this->load($this->associatedResources());
    }

    protected function associatedResources(): array
    {
        return [];
    }
}
