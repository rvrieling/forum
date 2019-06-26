<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function update(UserRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $if = $user->id == $validated['user_id'];

        if ($if === false && $user->role !== 'admin') {
            abort(401);
        }
        $user_id      = $validated['user_id'];
        $first_name   = empty($validated['first_name'])   !== false ? $user->first_name : $validated['first_name'];
        $last_name    = empty($validated['last_name'])    !== false ? $user->last_name : $validated['last_name'];
        $username     = empty($validated['username'])     !== false ? $user->user_name : $validated['username'];
        $bio          = empty($validated['bio'])          !== false ? $user->profile->bio : $validated['bio'];
        $website_link = empty($validated['website_link']) !== false ? $user->profile->website_link : $validated['website_link'];

        $imageName   = $user->profile->image;
        

        if (empty($validated['image']) == false) {
            $imageName   = 'user-' . rand(1, 1000000) . str_random(20) . Carbon::now()->timestamp;
            $img         = Image::make($validated['image'])->resize(null, 720, function ($constraint) {
                $constraint->aspectRatio();
            })->save();

            Storage::put('public/images/users/' . $imageName . '.jpg', $img);
        }

        if ($if == true) {
            $if = $user->user_name == $validated['username'];

            if ($if == false) {
                $userif = User::query()
                    ->where('user_name', $validated['username'])
                    ->exists();

                if ($userif == true) {
                    abort(226);
                }
            }
        } else {
            $userif = User::query()
                ->where('user_name', $validated['username'])
                ->exists();

            if ($userif == true) {
                abort(226);
            }
        }

        $user = User::query()
            ->where('id', $user_id)
            ->first();

        $user->update([
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'user_name'  => $username,
        ]);

        $user->profile()->update([
            'bio'          => $bio,
            'website_link' => $website_link,
            'image'        => $imageName,
        ]);

        return response()->json(201);
    }

    public function getUser(Request $request)
    {
        $user = user($request);

        return response()->json($user);
    }

    public function getAllUsers()
    {
        $users = User::select('id', 'first_name', 'user_name')->get();

        return response()->json($users);
    }
}
