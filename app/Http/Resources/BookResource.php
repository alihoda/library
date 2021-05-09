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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'year' => $this->year,
            'pdf_path' => $this->pdf_path !== null ? $this->url() : '',
            'publisher' => new BookPublisherAuthorResource($this->publisher),
            'authors' => BookPublisherAuthorResource::collection($this->authors),
            'categories' => BookPublisherAuthorResource::collection($this->categories),
            'images' => ImageResource::collection($this->images),
        ];
    }
}
