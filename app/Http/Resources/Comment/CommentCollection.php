<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    public $collects = CommentResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
