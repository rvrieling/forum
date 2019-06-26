<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\ProfileImage;
use App\Support\ImageInsert;

class ProfileController extends Controller
{
    public function index(ProfileRequest $request)
    {
        $username = $request->username;

        return Profile::query()
            ->user($username)
            ->with('user.address')
            ->first();
    }

    public function create(ProfileRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $bio          = $validated['bio'];
        $website_link = isset($validated['website_link']) !== false ? $validated['website_link'] : null;

        Profile::query()
            ->create([
                'user_id'      => $user->id,
                'bio'          => $bio,
                'website_link' => $website_link,
            ]);

        return response()->json(201);
    }

    public function edit(ProfileRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $profile = Profile::query()
            ->user($user->user_name)
            ->first();

        $bio          = empty($validated['bio'])          !== false ? $profile->bio : $validated['bio'];
        $website_link = empty($validated['website_link']) !== false ? $profile->website_link : $validated['website_link'];

        if (empty($validated['image']) == false) {
            $imageInsert = new ImageInsert($validated['image'], 'users');
        }

        if (! empty($validated['profile_image'])) {
            $this->profileImage($validated['profile_image'], $profile->id);
        }

        if ($profile->user_id === $user->id) {
            $profile->update([
                'bio'          => $bio,
                'website_link' => $website_link,
                'image'        => empty($validated['image']) !== false ? $profile->image : $imageInsert->imageName ,
            ]);

            return response()->json(201);
        }

        return response()->json(401);
    }

    private function profileImage($image, $profile_id)
    {
        $profileImage = ProfileImage::query()
            ->where('profile_id', $profile_id)
            ->first();

        $imageInsert = new ImageInsert($image, 'profile_images');

        $profileImage->update([
            'image' => $imageInsert->imageName,
        ]);
    }
}
