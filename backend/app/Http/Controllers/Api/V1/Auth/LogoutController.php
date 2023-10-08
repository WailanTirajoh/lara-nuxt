<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\LogoutRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/api/v1/auth/logout",
     *     summary="Logout",
     *     description="Logs out a user with valid token and destroy the token.",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=204,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success logout")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function __invoke(LogoutRequest $request)
    {
        try {
            Auth::user()->currentAccessToken()->delete();

            return ApiResponse::success(
                message: "Success Logout",
                statusCode: Response::HTTP_NO_CONTENT
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to logout: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
