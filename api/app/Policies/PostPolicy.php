<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class PostPolicy extends AppPolicy
{
    protected $model = 'post';
}
