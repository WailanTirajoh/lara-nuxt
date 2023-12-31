<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Channel::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return ApiResponse::success(
            data: [
                'channels' => ChannelResource::collection(
                    $request->user()
                        ->channels()
                        ->withResources()
                        ->get()
                ),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request)
    {
        DB::beginTransaction();
        $channel = Channel::create(array_merge($request->validated(), [
            'created_by' => Auth::id(),
        ]));
        $channel->users()->attach(Auth::id());
        DB::commit();

        return ApiResponse::success(
            message: 'Channel created successfully',
            data: [
                'channel' => ChannelResource::make(
                    $channel->loadResources()
                ),
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel)
    {
        return ApiResponse::success(
            data: [
                'channel' => ChannelResource::make($channel->loadResources()),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        DB::beginTransaction();
        $channel->update($request->validated());
        $channel->users()->sync($request->users);
        DB::commit();

        return ApiResponse::success(
            message: 'Channel updated successfully',
            data: [
                'channel' => ChannelResource::make($channel->loadResources()),
            ],
            statusCode: Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        $channel->delete();

        return ApiResponse::success(message: 'Channel permanently deleted', statusCode: Response::HTTP_NO_CONTENT);
    }
}
