<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        return ApiResponse::success(
            data: NotificationResource::collection(Auth::user()->notifications)
        );
    }

    public function unreadCount()
    {
        return ApiResponse::success(
            data: Auth::user()->unreadNotifications()->count()
        );
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return ApiResponse::success(
            message: "Successfully marked"
        );
    }

    public function markAsRead($id)
    {
        Auth::user()->unreadNotifications()->find($id)->markAsRead();
        return ApiResponse::success(
            message: "Successfully marked"
        );
    }
}
