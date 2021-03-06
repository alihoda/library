<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentUserResource;
use App\Models\Book;
use App\Models\Comment;
use App\Models\GuestComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['index', 'show', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Book $book)
    {
        return Cache::tags('comments')->remember("book-{$book->id}-comment-list", now()->addSeconds(30), function () use ($book) {

            $reviewerComments = CommentResource::collection($book->comments()->with('user')->reviewerComments());
            $userComments = CommentResource::collection($book->comments()->with('user')->userComments());
            $guestComments = CommentResource::collection($book->guestComments()->latest()->get());

            $otherComments = $userComments->merge($guestComments)->sortByDesc('created_at')->values();

            return response()->json([
                'reviewerComments' => $reviewerComments,
                'otherComments' => $otherComments,
            ]);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request, Book $book)
    {
        // Validate request
        $request->validated();
        // Assign the comment's foreign keys
        $request['book_id'] = $book->id;

        // Check if user is authenticated
        if ($user = Auth::guard("api")->user()) {

            $request['user_id'] = $user->id;
            // Make an instance model of Comment
            $comment = Comment::make($request->all());
            // Assign the instance to the book and store it
            $book->comments()->save($comment);
            // Return message
            return response()->json([
                'message' => 'Comment posted',
                'data' => new CommentUserResource($comment)
            ]);
        }

        // User is guest (not authenticated)

        $request['user_name'] = $request->user_name;
        // Make an instance model of Comment
        $comment = GuestComment::make($request->all());
        // Assign the instance to the book and store it
        $book->guestComments()->save($comment);

        // Return message
        return response()->json([
            'message' => 'Comment posted',
            'data' => new CommentUserResource($comment)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book, Comment $comment)
    {
        return Cache::tags('comments')->remember("book-{$book->id}-comment-{$comment->id}", now()->addMinute(), function () use ($comment) {
            return new CommentResource($comment);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Book $book, Comment $comment)
    {
        $this->authorize('update', $comment);

        // Validate request
        $request->validated();
        // Update the comment model
        $comment->update($request->all());

        // Return message
        return response()->json([
            'message' => 'Comment updated',
            'data' => new CommentResource($comment)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book, Comment $comment)
    {
        $this->authorize('delete', $comment);

        // Delete the comment model
        $comment->delete();

        // Return message
        return response()->json(['message' => 'Comment deleted']);
    }
}
