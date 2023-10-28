<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\StoreReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Reply::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Thread $thread)
    {
        $orderBy = $request->query('order_by', 'created_at');
        $orderType = $request->query('order_type', 'ASC');
        $limit = $request->query('limit', 25);

        $replies = $thread->replies()
            ->with('user')
            ->orderBy($orderBy, $orderType)
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'replies' => ReplyResource::collection($replies),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, Thread $thread)
    {
        DB::beginTransaction();
        $reply = $thread->replies()->create(
            array_merge($request->validated(), ['user_id' => Auth::id()])
        );
        DB::commit();

        return ApiResponse::success(
            message: 'Reply created successfully',
            data: [
                'reply' => ReplyResource::make($reply),
            ],
            statusCode: Response::HTTP_CREATED
        );
    }
}
