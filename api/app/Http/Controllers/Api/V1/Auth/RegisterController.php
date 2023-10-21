<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $accessToken = $user->createToken('access_token')->plainTextToken;

        return ApiResponse::success(
            message: 'User created successfully',
            data: ['access_token' => $accessToken],
            statusCode: Response::HTTP_CREATED
        );
    }
}
