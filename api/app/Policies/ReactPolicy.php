<?php

namespace App\Policies;

use App\Models\React;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class ReactPolicy extends AppPolicy
{
    protected $model = 'react';
}
