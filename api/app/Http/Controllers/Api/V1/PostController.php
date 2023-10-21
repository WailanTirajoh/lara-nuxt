<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('post-access');

        $orderBy = $request->query("order_by", "id");
        $orderType = $request->query("order_type", "ASC");
        $search = $request->query("query");
        $limit = $request->query('limit', 5);

        $posts = Post::when($request->with_trashed == 'true', fn ($query) => $query->onlyTrashed())
            ->with("author")
            ->where(function ($query) use ($search) {
                $query->where("title", 'like', "%{$search}%");
            })
            ->orderBy($orderBy, $orderType)
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'posts' => PostResource::collection($posts)
            ]
        );
    }

    public function store(StorePostRequest $request)
    {
        $this->authorize('post-store');

        $post = Post::create(array_merge(
            $request->validated(),
            ['author_id' => Auth::user()->id]
        ));
        $post->attachTags($request->tags ?? []);

        return ApiResponse::success(
            message: 'Post created successfully',
            data: [
                "post" => PostResource::make($post->load('author'))
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(Post $post)
    {
        $this->authorize('post-show');

        return ApiResponse::success(
            data: [
                'post' => PostResource::make($post->load("author"))
            ]
        );
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('post-update');

        $post->update($request->validated());
        $post->syncTags($request->tags ?? []);

        return ApiResponse::success(
            message: 'Post updated successfully',
            data: [
                "post" => PostResource::make($post->load('author'))
            ]
        );
    }

    public function destroy(Post $post)
    {
        $this->authorize('post-delete');

        $post->delete();

        return ApiResponse::success(message: 'Post deleted successfully', statusCode: Response::HTTP_NO_CONTENT);
    }

    public function restore($post_id)
    {
        $this->authorize('post-restore');

        Post::withTrashed()->find($post_id)->restore();

        return ApiResponse::success(message: 'Post restored successfully');
    }

    public function destroy_permanent($post_id)
    {
        $this->authorize('post-delete-permanent');

        Post::withTrashed()->find($post_id)->forceDelete();

        return ApiResponse::success(message: 'Post permanently deleted');
    }
}
