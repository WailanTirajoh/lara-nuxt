<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    public function index(Request $request)
    {
        $orderBy = $request->query('order_by', 'id');
        $orderType = $request->query('order_type', 'ASC');
        $search = $request->query('query');
        $limit = $request->query('limit', 5);

        $users = User::with('roles')->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->orderBy($orderBy, $orderType)
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'users' => UserResource::collection($users),
            ]
        );
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $user->syncRoles($request->roles ?? []);

        if ($request->image) {
            $user->addMedia($request->image)->toMediaCollection('images');
        }

        return ApiResponse::success(
            message: 'User created successfully',
            data: [
                'user' => UserResource::make($user),
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(User $user)
    {
        return ApiResponse::success(
            data: [
                'user' => UserResource::make($user),
            ]
        );
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $user->update($validatedData);
        $user->syncRoles($request->roles ?? []);

        if ($request->image) {
            $user->clearMediaCollection('images');
            $user->addMedia($request->image)->toMediaCollection('images');
        }

        return ApiResponse::success(
            message: 'User updated successfully',
            data: [
                'user' => UserResource::make($user),
            ],
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(User $user)
    {
        abort_if(Auth::user()->id === $user->id, Response::HTTP_UNAUTHORIZED, 'You cant delete your own account');

        $user->delete();

        return ApiResponse::success(message: 'User deleted successfully', statusCode: Response::HTTP_NO_CONTENT);
    }
}
