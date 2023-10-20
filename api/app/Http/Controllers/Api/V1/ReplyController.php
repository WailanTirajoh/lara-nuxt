<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Reply;
use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadReplied;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Thread $thread)
    {
        $this->authorize('reply-access');

        $orderBy = $request->query("order_by", "created_at");
        $orderType = $request->query("order_type", "ASC");
        $limit = $request->query('limit', 25);

        $replies = $thread->replies()
            ->with('user')
            ->orderBy($orderBy, $orderType)
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'replies' => ReplyResource::collection($replies)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Thread $thread)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, Thread $thread)
    {
        $this->authorize('reply-store');

        DB::beginTransaction();
        $reply = $thread->replies()->create(
            array_merge($request->validated(), ['user_id' => Auth::id()])
        );
        DB::commit();

        return ApiResponse::success([
            'message' => 'Reply created successfully',
            'data' => ReplyResource::make($reply),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread, Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thread $thread, Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Thread $thread, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thread $thread, Reply $reply)
    {
        //
    }
}
