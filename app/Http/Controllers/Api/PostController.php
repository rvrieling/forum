<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index(Request $request, $filter = null)
    {
        if ($request->header('authorization')) {
            $user = user($request);

            $user_info = User::query()
                ->with([
                    'userSettings',
                    'subs' => function ($query) {
                        $query->where('subscribed', 1);
                    }
                ])
                ->where('id', $user->id)
                ->first();

            $cat_ids = $user_info->subs->pluck('category_id');

            if (! $cat_ids->isEmpty()) {
                if ($user_info->userSettings->sort_by == 'new') {
                    $posts = Post::query()
                        ->with([
                            'likes' => function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            },
                            'user' => function ($query) {
                                $query->select('id', 'user_name', 'role');
                            },
                            'category' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'images' => function ($query) {
                                $query->select('post_id', 'image');
                            },
                        ])
                        ->whereIn('category_id', $cat_ids)
                        ->select('id', 'content', 'name', 'likes_count', 'user_id', 'category_id')
                        ->orderByDesc('created_at')
                        ->paginate(20);
                } else {
                    $posts = Post::query()
                        ->with([
                            'likes' => function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            },
                            'user' => function ($query) {
                                $query->select('id', 'user_name', 'role');
                            },
                            'category' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'images' => function ($query) {
                                $query->select('post_id', 'image');
                            },
                        ])
                        ->whereIn('category_id', $cat_ids)
                        ->select('id', 'content', 'name', 'likes_count', 'user_id', 'category_id')
                        ->orderByDesc('likes_count')
                        ->paginate(20);
                }
            } elseif ($user_info->userSettings->sort_by == 'new') {
                $posts = Post::query()
                    ->with([
                        'likes' => function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        },
                        'user' => function ($query) {
                            $query->select('id', 'user_name', 'role');
                        },
                        'category' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'images' => function ($query) {
                            $query->select('post_id', 'image');
                        },
                    ])
                    ->orderByDesc('created_at')
                    ->select('id', 'content', 'name', 'likes_count', 'user_id', 'category_id')
                    ->paginate(20);
            } else {
                $posts = Post::query()
                    ->with([
                        'likes' => function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        },
                        'user' => function ($query) {
                            $query->select('id', 'user_name', 'role');
                        },
                        'category' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'images' => function ($query) {
                            $query->select('post_id', 'image');
                        },
                    ])
                    ->orderByDesc('likes_count')
                    ->select('id', 'content', 'name', 'likes_count', 'user_id', 'category_id')
                    ->paginate(20);
            }
        } elseif ($filter == 'new') {
            $posts = Post::query()
                ->with([
                    'user' => function ($query) {
                        $query->select('id', 'user_name', 'role');
                    },
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'images' => function ($query) {
                        $query->select('post_id', 'image');
                    },
                ])
                ->orderByDesc('created_at')
                ->select('id', 'content', 'name', 'likes_count', 'user_id', 'category_id')
                ->paginate(20);
        } else {
            $posts = Post::query()
                ->with([
                    'user' => function ($query) {
                        $query->select('id', 'user_name', 'role');
                    },
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'images' => function ($query) {
                        $query->select('post_id', 'image');
                    },
                ])
                ->orderByDesc('likes_count')
                ->select('id', 'content', 'name', 'likes_count', 'user_id', 'category_id')
                ->paginate(20);
        }

        return response()->json($posts);
    }

    public function create(PostRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $category_id = $validated['category_id'];
        $name        = $validated['name'];
        $content     = $validated['content'];
        $image       = $validated['image'];
        $imageName   = null;

        if (empty($image) == false) {
            $height      = 1080;
            $width       = 1920;
            $canvas      = Image::canvas($width, $height);
            $imageName   = 'post-' . rand(1, 1000000) . str_random(20) . Carbon::now()->timestamp;
            $img         = Image::make($image)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas->insert($img, 'center');
            $canvas->save(storage_path('app/public/images/posts/' . $imageName . '.png'));
        }

        $post = Post::query()
            ->create([
                'category_id' => $category_id,
                'user_id'     => $user->id,
                'name'        => $name,
                'content'     => $content,
                'likes_count' => 0,
            ]);

        if (empty($image) == false) {
            $post->images()->create(['image' => $imageName]);
        }

        return response()->json(201);
    }

    public function edit(PostRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $id  = $validated['id'];

        $post = Post::query()
            ->where('category_id', $validated['category_id'])
            ->where('id', $id)
            ->first();

        $category_id = empty($validated['category_id']) !== false ? $post->category_id : $validated['category_id'];
        $name        = empty($validated['name'])        !== false ? $post->name : $validated['name'];
        $content     = empty($validated['content'])     !== false ? $post->content : $validated['content'];

        if (empty($validated['image'])) {
            if ($post->image_name == null) {
                $imageStore = null;
            } else {
                $imageStore = $post->image_name;
            }
        } else {
            if ($post->image_name == null) {
                $imageName   = 'post-' . rand(1, 1000000) . str_random(20) . Carbon::now()->timestamp;
                $imageStore  = $imageName;
                $img         = Image::make($validated['image'])->resize(null, 720, function ($constraint) {
                    $constraint->aspectRatio();
                })->save();

                Storage::put('public/images/posts/' . $imageName . '.jpg', $img);
            } else {
                $imageName   = 'post-' . rand(1, 1000000) . str_random(20) . Carbon::now()->timestamp;
                $imageStore  = $imageName;
                $img         = Image::make($validated['image'])->resize(null, 720, function ($constraint) {
                    $constraint->aspectRatio();
                })->save();

                Storage::put('public/images/posts/' . $imageName . '.jpg', $img);
            }
        }

        if ($user->role === 'admin' | $post->user_id === $user->id) {
            $post->update([
                    'category_id' => $category_id,
                    'name'        => $name,
                    'content'     => $content,
                    'likes_count' => $post->likes_count,
                    'image_name'  => $imageStore,
                ]);
            
            return response()->json(201);
        }

        return response()->json(401);
    }

    public function delete(Request $request)
    {
        $user = user($request);

        $id = $request->input('id');

        $post = Post::findOrFail($id);

        if ($post->user_id !== $user->id && $user->role !== 'admin') {
            abort(401);
        }

        $post->delete();

        return response()->json(410);
    }
}
