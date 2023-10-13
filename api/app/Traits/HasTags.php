<?php

namespace App\Traits;

use Spatie\Tags\HasTags as Spatie;
use Illuminate\Database\Eloquent\Model;

trait HasTags
{
    use Spatie;

    public static function bootHasTags()
    {
        static::deleted(function (Model $deletedModel) {
            if (method_exists($deletedModel, 'isForceDeleting') && !$deletedModel->isForceDeleting()) {
                return;
            }

            $tags = $deletedModel->tags()->get();
            $deletedModel->detachTags($tags);
        });
    }
}
