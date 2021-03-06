<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        if ($this->user) {
            $user = [
                'id' => $this->user->id,
                'username' => $this->user->username
            ];
        } else {
            $user = $this->user_name;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'user' => $user,
        ];
    }
}
