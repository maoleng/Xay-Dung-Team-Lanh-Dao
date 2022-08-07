<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class PostController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])]
    public function index(): array
    {
        $posts = Post::query()
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(['id', 'title', 'banner', 'likes', 'views', 'created_at']);

        return [
            'status' => true,
            'data' => $posts
        ];
    }

    public function show($id): array
    {
        $post = Post::query()->find($id);
        if (empty($post)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy bài viết'
            ];
        }
        $post->increment('views');
        $user = c('authed');
        $if_liked = !$user
            ->where('id', $user->id)
            ->whereHas('posts', static function ($q) use ($post) {
                $q->where('id', $post->id);
            })
            ->get()->isEmpty();

        return [
            'status' => true,
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'banner' => $post->banner,
                'likes' => $post->likes,
                'views' => $post->views,
                'if_liked' => $if_liked
            ]
        ];

    }

    public function like($id): array
    {
        $post = Post::query()->find($id);
        if (empty($post)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy bài viết'
            ];
        }
        $user = c('authed');
        $if_liked = !$user
            ->where('id', $user->id)
            ->whereHas('posts', static function ($q) use ($post) {
                $q->where('id', $post->id);
            })
            ->get()->isEmpty();
        if (!$if_liked) {
            $post->increment('likes');
            $user->posts()->attach($post);
        }

        return [
            'status' => true,
            'data' => $post
        ];

    }

    #[ArrayShape(['status' => "bool"])]
    public function store(StorePostRequest $request): array
    {
        Post::query()->create($request->validated());

        return [
            'status' => true,
        ];
    }
}
