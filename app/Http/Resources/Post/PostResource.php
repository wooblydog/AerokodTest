<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comment\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'external_id' => $this->external_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
