<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\LogoutRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(LogoutRequest $request)
    {
        Auth::user()->currentAccessToken()->delete();

        return ApiResponse::success(
            message: 'Success Logout',
            statusCode: Response::HTTP_NO_CONTENT
        );
    }
}
