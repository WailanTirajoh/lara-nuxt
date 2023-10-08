<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/auth/profile",
     *     summary="Auth Profile",
     *     description="Logs out a user with valid token and destroy the token.",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function __invoke()
    {
        try {
            return ApiResponse::success(
                data: Auth::user()
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to get user profile: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
