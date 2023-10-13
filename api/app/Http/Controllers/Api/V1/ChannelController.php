<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Channel;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies("channel-access"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        $channels = Channel::all();

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
        abort_if(Gate::denies("channel-store"), Response::HTTP_FORBIDDEN, "You are not allowed to access this");

        $channel = Channel::create($request->validated());

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        //
    }
}
