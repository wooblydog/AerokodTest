<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostCollection;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentAdded;
use App\Traits\GeneratesExternalId;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    use GeneratesExternalId;

    public function index(): CommentCollection
    {
        return new CommentCollection(Comment::query()->get());
    }

    public function store(CommentStoreRequest $request): Response
    {
        $fields = [
            'external_id' => $this->generateExternalId(Comment::class),
            'name' => $request->name,
            'email' => $request->user()->email,
            'post_id' => $request->post_id,
            'description' => $request->description,
        ];

        $comment = Comment::query()->create($fields);

        $post = Post::query()->findOrFail($comment->post_id);
        $post->user->notify(new CommentAdded($comment));

        return response(['message' => 'Комментарий создан'], Response::HTTP_CREATED);
    }

    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment->load('post'));
    }

    public function postWithComments(Post $post): PostCollection
    {
        $comments = Comment::query()
            ->where('post_id', $post->external_id)
            ->paginate(perPage: 12, page: $request->page ?? 1);

        return new PostCollection($comments);
    }
}
