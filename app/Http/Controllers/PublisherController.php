<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublisherResource;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublisherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cache::tags('publishers')->remember('publisher-list', now()->addMinutes(5), function () {
            return PublisherResource::collection(Publisher::with('books')->get());
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Authorize user as admin
        // $this->authorize('create');

        // validate request
        $request->validate([
            'name' => 'bail | required | min:5 | max:100 | string | unique:publishers'
        ]);

        // Create new record
        $publisher = Publisher::create($request->all());

        // Return message
        return response()->json([
            'message' => 'Publisher created',
            'data' => new PublisherResource($publisher)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($publisher)
    {
        return Cache::tags('publishers')->remember("publisher-{$publisher}", now()->addMinutes(5), function () use ($publisher) {
            return new PublisherResource(Publisher::with('books')->findOrFail($publisher));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        // Authorize user as admin
        $this->authorize('create', $publisher);
        // validate request
        $request->validate([
            'name' => 'bail | min:5 | max:100 | string | unique:publishers'
        ]);
        // Update the publisher
        $publisher->update($request->all());

        // Return message
        return response()->json([
            'message' => 'Publisher updated',
            'data' => new PublisherResource($publisher)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        // Authorize user as admin
        $this->authorize('delete', $publisher);
        // Delete the publisher record
        $publisher->delete();
        // Return message
        return response()->json(['message' => 'Publisher deleted']);
    }
}
