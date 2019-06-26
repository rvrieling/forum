<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Sub;
use Illuminate\Http\Request;

class SubController extends Controller
{
    public function index(Request $request)
    {
        $user = user($request);

        $posts = Post::query()
            ->with([
                'likes' => function ($query) {
                    $query->where('liked', 1);
                },
                'user',
                'category.subs' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ])
            ->whereHas('category.subs', function ($query) use ($user) {
                $query->where('subscribed', 1);
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $likes = Like::query()
            ->whereIn('post_id', $posts->pluck('id'))
            ->where('user_id', $user->id)
            ->get();

        if (! count($likes) > 0) {
            $posts = Post::query()
            ->with([
                'likes' => function ($query) {
                    $query->where('liked', 1);
                },
                'user',
                'category.subs'
            ])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
            
            $likes = Like::query()
            ->whereIn('post_id', $posts->pluck('id'))
            ->where('user_id', $user->id)
            ->get();
        }

        $array = [
            'posts' => $posts,
            'likes' => $likes
        ];

        return response($array);
    }

    public function update(SubRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        Sub::query()
            ->where('user_id', $user->id)
            ->where('category_id', $validated['category_id'])
            ->update([
                'subscribed' => $validated['subscribed']
            ]);

        return response()->json(201);
    }
}
