<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookObserver
{
    public function created(Book $book)
    {
        Cache::tags(['books'])->forget('book-list');

        $this->clearCaches($book);
    }

    public function updating(Book $book)
    {
        Cache::tags(['books'])->forget('book-list');
        Cache::tags(['books'])->forget("book-{$book->id}");

        $this->clearCaches($book);
    }

    public function deleting(Book $book)
    {
        Cache::tags(['books'])->forget('book-list');
        Cache::tags(['books'])->forget("book-{$book->id}");

        $this->clearCaches($book);
    }

    private function clearCaches(Book $book)
    {
        Cache::tags(['authors'])->forget('author-list');
        Cache::tags(['publishers'])->forget('publisher-list');
        Cache::tags(['categories'])->forget('category-list');

        Cache::tags(['publishers'])->forget("publisher-{$book->publisher_id}");
        // Clear all the book's author's cache
        foreach ($book->authors as $author) {
            Cache::tags(['authors'])->forget("author-{$author->id}");
        }
        // Clear all the book's category's cache
        foreach ($book->categories as $category) {
            Cache::tags(['categories'])->forget("category-{$category->id}");
        }
    }
}
