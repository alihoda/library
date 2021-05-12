<?php

namespace App\Http\Controllers;

use App\Http\Resources\SearchResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');

        $searchResult = (new Search())
            ->registerModel(Book::class, 'title')
            ->registerModel(Publisher::class, 'name')
            ->registerModel(Author::class, 'name')
            ->perform($searchTerm);

        return SearchResource::collection($searchResult);
    }
}
