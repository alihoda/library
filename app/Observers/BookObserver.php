<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookObserver
{
    public function creating(Book $book)
    {
        Cache::tags(['books'])->forget('book-list');
        Cache::tags(['publishers'])->forget('publisher-list');
    }

    public function updating(Book $book)
    {
        Cache::tags(['books'])->forget('book-list');
        Cache::tags(['publishers'])->forget('publisher-list');

        Cache::tags(['books'])->forget("book-{$book->id}");
        Cache::tags(['publishers'])->forget("publisher-{$book->publisher_id}");
    }

    public function deleting(Book $book)
    {
        Cache::tags(['books'])->forget('book-list');
        Cache::tags(['publishers'])->forget('publisher-list');

        Cache::tags(['books'])->forget("book-{$book->id}");
        Cache::tags(['publishers'])->forget("publisher-{$book->publisher_id}");
    }
}
