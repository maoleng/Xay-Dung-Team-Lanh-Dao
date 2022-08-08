<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class PostController extends Controller
{
    #[ArrayShape(['status' => "bool", 'data' => "mixed", 'views' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed"])]
    public function index(Request $request): array
    {
        $limit = $request->get('limit');
        $order_by = $request->get('order_by');
        $posts = Post::query()
            ->with('likers', function ($q) {
                $q->where('id', c('authed')->id);
            });
        if (isset($limit)) {
            $posts->limit($limit);
        }
        if (isset($order_by)) {
            $posts->orderBy($order_by, 'DESC');
        }
        $posts = $posts
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(['id', 'title', 'banner', 'likes', 'views', 'created_at']);
        $posts = $posts->map(static function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'banner' => $post->banner,
                'views' => $post->views,
                'likes' => $post->likes,
                'if_liked' => !$post->likers->isEmpty(),
                'created_at' => $post->created_at->format('d-m-Y H:i:s'),
            ];
        });

        return [
            'status' => true,
            'data' => $posts,
            'views' => increaseViews()
        ];
    }

    public function show($id): array
    {
        $post = Post::query()->find($id);
        if (empty($post)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy bài viết',
                'views' => increaseViews()
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
            ],
            'views' => increaseViews(),
        ];

    }

    public function like($id): array
    {
        $post = Post::query()->find($id);
        if (empty($post)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy bài viết',
                'views' => increaseViews(),
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
            'data' => $post,
            'views' => increaseViews(),
        ];

    }

    #[ArrayShape(['status' => "bool", 'views' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed"])]
    public function store(StorePostRequest $request): array
    {
        Post::query()->create($request->validated());

        return [
            'status' => true,
            'views' => increaseViews(),
        ];
    }
}
