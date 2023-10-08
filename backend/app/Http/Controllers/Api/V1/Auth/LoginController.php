<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints"
 * )
 */
class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login",
     *     description="Logs in a user with valid credentials and returns a token.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success login"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="access_token",
     *                     type="string",
     *                     example="your-access-token"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials"),
     *             @OA\Property(property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     example={"email": {"Invalid credentials"}}
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return ApiResponse::error(
                    message: 'Invalid credentials',
                    data: [
                        'errors' => [
                            'email' => [
                                'Invalid credentials',
                            ]
                        ],
                    ],
                    statusCode: Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $accessToken = Auth::user()->createToken('access_token')->plainTextToken;

            return ApiResponse::success(
                message: "Success Login",
                data: [
                    'access_token' => $accessToken,
                ]
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to login: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
