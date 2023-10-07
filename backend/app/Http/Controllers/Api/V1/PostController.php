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

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Post endpoints"
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     summary="Get a list of posts",
     *     description="Returns a list of posts",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of posts per page (default is 5)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=5
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Current page (default is 1)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="with_trashed",
     *         in="query",
     *         description="Current page (default is 1)",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             default=false
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response: List of posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="posts",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/PostResource")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Invalid credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Insufficient permissions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found: Posts not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Not Found"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index(Request $request)
    {
        $search = $request->query("query");
        $limit = $request->query('limit', 5);
        $posts = Post::when($request->with_trashed == 'true', fn ($query) => $query->onlyTrashed())
            ->with("author")
            ->where(function ($query) use ($search) {
                $query->where("title", 'like', "%{$search}%");
            })
            ->paginate($limit);

        return ApiResponse::success(
            data: [
                'posts' => PostResource::collection($posts)
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     summary="Store a new post",
     *     description="Stores a new post in the database",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StorePostRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Post created successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post",
     *                     ref="#/components/schemas/PostResource"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Validation error"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Validation error details",
     *                 example={
     *                     "title": {
     *                         "The title field is required."
     *                     },
     *                     "slug": {
     *                         "The slug field is required.",
     *                         "The slug has already been taken."
     *                     },
     *                     "body": {
     *                         "The body field is required."
     *                     },
     *                     "author_id": {
     *                         "The user id field is required."
     *                     }
     *                 }
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function store(StorePostRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['author_id'] = Auth::user()->id;
            $post = Post::create($validatedData);

            return ApiResponse::success(
                message: 'Post created successfully',
                data: [
                    "post" => PostResource::make($post->load('author'))
                ],
                statusCode: Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to create post: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{post}",
     *     summary="Get a single post by ID",
     *     description="Retrieves a single post by its ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID of the post to retrieve",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post",
     *                     ref="#/components/schemas/PostResource"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Not Found"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(Post $post)
    {
        return ApiResponse::success(
            data: [
                'post' => PostResource::make($post->load("author"))
            ]
        );
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{post}",
     *     summary="Update a post by ID",
     *     description="Updates a post by its ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID of the post to update",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UpdatePostRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Post updated successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="post",
     *                     ref="#/components/schemas/PostResource"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Validation error"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Validation error details",
     *                 example={
     *                     "title": {
     *                         "The title field is required."
     *                     },
     *                     "slug": {
     *                         "The slug field is required.",
     *                         "The slug has already been taken."
     *                     },
     *                     "body": {
     *                         "The body field is required."
     *                     },
     *                     "author_id": {
     *                         "The user id field is required."
     *                     }
     *                 }
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $validatedData = $request->validated();
            $post->update($validatedData);

            return ApiResponse::success(
                message: 'Post updated successfully',
                data: [
                    "post" => PostResource::make($post->load('author'))
                ],
                statusCode: Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to update post: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{post}",
     *     summary="Delete a post by ID",
     *     description="Deletes a post by its ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="ID of the post to delete",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Post deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Not Found"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();

            return ApiResponse::success(message: 'Post deleted successfully', statusCode: Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to update post: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{post_id}/restore",
     *     summary="Restore a soft-deleted post by ID",
     *     description="Restores a soft-deleted post by its ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         required=true,
     *         description="ID of the soft-deleted post to restore",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post restored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Post restored successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Not Found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to restore post: Error message here"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function restore($post_id)
    {
        try {
            Post::withTrashed()->find($post_id)->restore();

            return ApiResponse::success(message: 'Post restored successfully');
        } catch (\Exception $e) {
            return ApiResponse::error(
                message: "Failed to update post: {$e->getMessage()}",
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
