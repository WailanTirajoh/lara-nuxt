<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReactRequest;
use App\Http\Requests\UpdateReactRequest;
use App\Models\React;

class ReactController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(React::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(React $react)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(React $react)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactRequest $request, React $react)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(React $react)
    {
        //
    }
}
