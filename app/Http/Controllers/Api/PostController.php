<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivatePostAndCommentsRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\GeneratesExternalId;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use GeneratesExternalId;

    public function index(): PostCollection
    {
        return new PostCollection(Post::query()->get());
    }

    public function store(PostStoreRequest $request): Response
    {
        Post::query()->create([
            'external_id' => $this->generateExternalId(Post::class),
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response(['message' => 'Пост создан'], Response::HTTP_CREATED);
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    public function postTwoComments(Request $request): PostCollection
    {
        $postsQuery = Post::with(['comments' => function (HasMany $query) {
            $query->latest()->take(2);
        }]);

        if (request()->bearerToken() && $user = Auth::guard('sanctum')->user()) {
            Auth::setUser($user);
        }

        if (!Auth::check()) {
            $postsQuery->where('is_active', true);
        }

        $posts = $postsQuery->paginate(perPage: 12, page: $request->page ?? 1);

        return new PostCollection($posts);
    }

    public function activatePostsAndComments(ActivatePostAndCommentsRequest $request): Response
    {
        Post::query()
            ->whereIn('id', $request->post_ids)
            ->update(['is_active' => true]);

        Comment::query()
            ->whereIn('id', $request->comment_ids)
            ->update(['is_active' => true]);

        return response(['message' => 'Указанным постам и комментариям успешно был выставлен is_active = true'], Response::HTTP_OK);
    }
}
