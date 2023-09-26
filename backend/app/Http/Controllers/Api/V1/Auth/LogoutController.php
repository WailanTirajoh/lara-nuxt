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
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout",
     *     description="Logs out a user with valid token and destroy the token.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LogoutRequest")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success logout")
     *         )
     *     ),
     * )
     */
    public function __invoke(LogoutRequest $request)
    {
        Auth::user()->currentAccessToken()->delete();

        return ApiResponse::success(
            message: "Success Logout",
            statusCode: Response::HTTP_NO_CONTENT
        );
    }
}
