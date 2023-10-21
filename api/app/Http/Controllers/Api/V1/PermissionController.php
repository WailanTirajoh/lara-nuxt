<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('permission-access');

        $permissions = Permission::all();

        return ApiResponse::success(
            data: [
                'permissions' => PermissionResource::collection($permissions)
            ]
        );
    }
}
