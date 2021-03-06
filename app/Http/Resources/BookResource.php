<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pdf_url = $this->pdf_path !== null ? $this->url() : '';

        return [
            'title' => $this->title,
            'description' => $this->description,
            'year' => $this->year,
            'pdf_path' => $pdf_url,
            'publisher' => new BookPublisherAuthorResource($this->publisher),
            'authors' => BookPublisherAuthorResource::collection($this->authors),
            'categories' => BookPublisherAuthorResource::collection($this->categories),
            'images' => ImageResource::collection($this->images),
            'comments' => $this->getComments($this),
        ];
    }

    private function getComments($book)
    {
        $reviewerComments = CommentResource::collection($book->comments()->with('user')->reviewerComments());
        $userComments = CommentResource::collection($book->comments()->with('user')->userComments());
        $guestComments = CommentResource::collection($book->guestComments()->latest()->get());

        $otherComments = $userComments->merge($guestComments);

        return [
            'reviewerComments' => $reviewerComments,
            'otherComments' => $otherComments,
        ];
    }
}
