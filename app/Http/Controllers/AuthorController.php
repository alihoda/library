<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublisherAuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
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
        return Cache::tags('authors')->remember('author-list', now()->addMinutes(5), function () {
            return PublisherAuthorResource::collection(Author::with('books')->withCount('books')->get());
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'bail | required | min:3 | max:50 | string'
        ]);
        // Create author model
        $author = Author::create($request->all());
        // Return message
        return response()->json([
            'message' => 'Author created',
            'data' => new PublisherAuthorResource($author)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($author)
    {
        return Cache::tags('authors')->remember("author-{$author}", now()->addMinutes(5), function () use ($author) {
            return new PublisherAuthorResource(Author::with('books')->withCount('books')->findOrFail($author));
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        // Authorize user as admin
        $this->authorize('update', $author);
        // Validate request
        $request->validate([
            'name' => 'bail | min:3 | max:50 | string'
        ]);
        // Update the author model
        $author->update($request->all());
        // Return message
        return response()->json([
            'message' => 'Author updated',
            'data' => new PublisherAuthorResource($author)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        // Authorize user as admin
        // $this->authorize('delete', $author);
        // Delete the author model
        $author->delete();
        // Return message
        return response()->json(['message' => 'Author deleted']);
    }
}
