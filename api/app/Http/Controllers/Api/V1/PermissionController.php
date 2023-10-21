<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Permission::class);
    }

    public function index(Request $request)
    {
        $permissions = Permission::all();

        return ApiResponse::success(
            data: [
                'permissions' => PermissionResource::collection($permissions)
            ]
        );
    }
}
