<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->header('Authorization')) {
            $user = user($request);
            $cat  = Category::query()
                ->with([
                    'subs' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                        $query->select(['subscribed', 'category_id']);
                    }
                ])
                ->select('id', 'name')
                ->get();
        } else {
            $cat = Category::select('id', 'name')->get();
        }

        return response()->json($cat);
    }

    public function create(CategoryRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();
        $name      = $validated['name'];

        if ($user->role === 'admin') {
            Category::query()
                ->create([
                    'user_id' => $user->id,
                    'name'    => $name,
                ]);

            return response()->json(201);
        }

        return response()->json(401);
    }

    public function edit(CategoryRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $id = $validated['id'];

        $category = Category::query()
            ->where('id', $id)
            ->first();

        $name = empty($validated['name']) !== false ? $category->name : $validated['name'];

        if ($user->role === 'admin') {
            $category->update([
                    'name' => $name,
                ]);

            return response()->json(201);
        }

        return response()->json(401);
    }

    public function delete(Request $request)
    {
        $user = user($request);

        $id = $request->input('id');

        if ($user->role === 'admin') {
            Category::query()
                ->where('id', $id)
                ->delete();

            return response()->json(410);
        }

        return response()->json(401);
    }
}
