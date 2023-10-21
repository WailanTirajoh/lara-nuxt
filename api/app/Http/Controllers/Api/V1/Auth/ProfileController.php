<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return ApiResponse::success(
            data: ProfileResource::make(Auth::user())
        );
    }
}
