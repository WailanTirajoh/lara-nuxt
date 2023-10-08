<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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
