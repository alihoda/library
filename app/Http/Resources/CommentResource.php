<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Retrieve comments which their user is reviewer
        $reviewerComments = $this->whereHas('user', function ($query) {
            $query->where('reviewer', 1);
        })->get();

        // Retrieve comments which their user is not reviewer
        $otherComments = $this->whereHas('user', function ($query) {
            $query->where('reviewer', 0);
        })->get();

        return [
            'reviewerComments' => CommentUserResource::collection($reviewerComments),
            'otherComments' => CommentUserResource::collection($otherComments),
        ];
    }
}
