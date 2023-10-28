<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    public function index(Request $request)
    {
        $orderBy = $request->query("order_by", "id");
        $orderType = $request->query("order_type", "ASC");
        $search = $request->query("query");
        $limit = $request->query('limit', 5);

        $roles = Role::with($this->associatedRole())->where(function ($query) use ($search) {
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

        $role = Role::create(array_merge($request->validated(), ['guard_name' => 'web']));
        $role->syncPermissions($request->permissions ?? []);

        return ApiResponse::success(
            message: 'Role created successfully',
            data: [
                "role" => RoleResource::make($role->load($this->associatedRole()))
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(Role $role)
    {
        return ApiResponse::success(
            data: [
                'role' => RoleResource::make($role->load($this->associatedRole()))
            ]
        );
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_if($role->name === 'Super', Response::HTTP_FORBIDDEN, "Super role cannot be updated");

        $role->update($request->validated());
        $role->syncPermissions($request->permissions ?? []);

        return ApiResponse::success(
            message: 'Role updated successfully',
            data: [
                "role" => RoleResource::make($role->load($this->associatedRole()))
            ],
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(Role $role)
    {
        abort_if($role->name === 'Super', Response::HTTP_FORBIDDEN, "Super role cannot be updated");

        $role->delete();

        return ApiResponse::success(message: 'Role deleted successfully', statusCode: Response::HTTP_NO_CONTENT);
    }

    private function associatedRole()
    {
        return [
            "permissions"
        ];
    }
}
