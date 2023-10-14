<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Channel;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('channel-access');

        $channels = Auth::user()->channels()->with('users')->get();

        return ApiResponse::success(
            data: [
                'channels' => ChannelResource::collection($channels)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request)
    {
        $this->authorize('channel-store');

        DB::beginTransaction();
        $channel = Channel::create(array_merge($request->validated(), [
            'created_by' => Auth::id()
        ]));
        $channel->users()->attach(Auth::id());
        DB::commit();

        return ApiResponse::success(
            message: 'Channel created successfully',
            data: [
                "channel" => ChannelResource::make($channel)
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel)
    {
        $this->authorize('channel-show');

        return ApiResponse::success(
            data: [
                'channel' => ChannelResource::make($channel->load('users'))
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        abort_if($channel->created_by !== Auth::id(), Response::HTTP_FORBIDDEN, "You're not the channel creator!");
        $this->authorize('channel-update');

        DB::beginTransaction();
        $channel->update($request->validated());
        $channel->users()->sync($request->users);
        DB::commit();

        return ApiResponse::success(
            message: 'Channel updated successfully',
            data: [
                "channel" => ChannelResource::make($channel)
            ],
            statusCode: Response::HTTP_CREATED
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        abort_if($channel->created_by !== Auth::id(), Response::HTTP_FORBIDDEN, "You're not the channel creator!");
        $this->authorize('channel-delete');

        $channel->delete();

        return ApiResponse::success(message: 'Channel permanently deleted', statusCode: Response::HTTP_NO_CONTENT);
    }
}
