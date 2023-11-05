<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Thread::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Channel $channel)
    {
        $orderBy = $request->query('order_by', 'created_at');
        $orderType = $request->query('order_type', 'DESC');
        $limit = $request->query('limit', 5);
        $threadIds = $request->query('thread_ids', []);

        $threads = $channel->threads()
            ->with($this->associatedThread())
            ->withCount('replies')
            ->orderBy($orderBy, $orderType)
            ->whereNotIn('id', $threadIds)
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'threads' => ThreadResource::collection($threads),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThreadRequest $request, Channel $channel)
    {
        $threadData = array_merge($request->validated(), ['user_id' => Auth::id()]);
        $thread = $channel->threads()->create($threadData);

        return ApiResponse::success(
            message: 'Thread created successfully',
            data: [
                'thread' => ThreadResource::make(
                    $thread->load($this->associatedThread())
                        ->loadCount('replies')
                ),
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThreadRequest $request, Channel $channel, Thread $thread)
    {
        $thread->update($request->validated());

        return ApiResponse::success(
            message: 'Thread updated successfully',
            data: [
                'thread' => ThreadResource::make(
                    $thread->load($this->associatedThread())
                        ->loadCount('replies')
                ),
            ],
            statusCode: Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $thread->delete();

        return ApiResponse::success(message: 'Post restored successfully', statusCode: Response::HTTP_NO_CONTENT);
    }

    private function associatedThread()
    {
        return [
            'user', 'activities', 'user.media',
        ];
    }
}
