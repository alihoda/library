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

        Cache::tags(['authors'])->forget("author-{$book->author->id}");
        Cache::tags(['publishers'])->forget("publisher-{$book->publisher_id}");
    }
}
