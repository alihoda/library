<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
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
        // Retrieve all books
        return Cache::tags('books')->remember('book-list', now()->addMinutes(5), function () {
            return BookResource::collection(Book::latest()->with('publisher')->get());
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreRequest $request)
    {
        // Validate request's data
        $request->validated();
        // Assign current user's id as book's user_id
        $request['user_id'] = $request->user()->id;
        // Retrieve publisher id
        $request['publisher_id'] = Publisher::retrievePublisherId($request['publisher']);
        // Create book object
        $book = Book::create($request->all());

        // Return book
        return response()->json([
            'message' => 'Book created',
            'data' => new BookResource($book)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // Retrieve requested book
        return Cache::tags('books')->remember("book-{$book->id}", now()->addMinutes(5), function () use ($book) {
            return new BookResource($book);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, Book $book)
    {
        // Authorize user as admin
        $this->authorize('update', $book);

        // Validate request
        $request->validated();
        // Update the book
        $book->update($request->all());

        return response()->json([
            'message' => 'Book updated',
            'data' => new BookResource($book)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Authorize user as admin
        $this->authorize('delete', $book);
        // Delete the book record
        $book->delete();
        // Return message
        return response()->json(['message' => 'Book deleted']);
    }
}
