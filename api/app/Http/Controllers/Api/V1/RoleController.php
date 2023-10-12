<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies("role-access"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        $orderBy = $request->query("order_by", "id");
        $orderType = $request->query("order_type", "ASC");
        $search = $request->query("query");
        $limit = $request->query('limit', 5);

        $roles = Role::where(function ($query) use ($search) {
            $query->where("name", 'like', "%{$search}%");
        })
            ->orderBy($orderBy, $orderType)
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'roles' => RoleResource::collection($roles)
            ]
        );
    }

    public function store(StoreRoleRequest $request)
    {
        abort_if($request->name === 'Super', Response::HTTP_FORBIDDEN, "Super role cannot be created");
        abort_if(Gate::denies("role-store"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        $role = Role::create(array_merge($request->validated(), ['guard_name' => 'web']));
        $role->syncPermissions($request->permissions ?? []);

        return ApiResponse::success(
            message: 'Role created successfully',
            data: [
                "role" => RoleResource::make($role)
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies("role-show"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        return ApiResponse::success(
            data: [
                'role' => RoleResource::make($role)
            ]
        );
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_if($role->name === 'Super', Response::HTTP_FORBIDDEN, "Super role cannot be updated");
        abort_if(Gate::denies("role-update"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        $role->update($request->validated());
        $role->syncPermissions($request->permissions ?? []);

        return ApiResponse::success(
            message: 'Role updated successfully',
            data: [
                "role" => RoleResource::make($role)
            ],
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(Role $role)
    {
        abort_if($role->name === 'Super', Response::HTTP_FORBIDDEN, "Super role cannot be updated");
        abort_if(Gate::denies("role-delete"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        $role->delete();

        return ApiResponse::success(message: 'Role deleted successfully', statusCode: Response::HTTP_NO_CONTENT);
    }
}
