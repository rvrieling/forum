<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        
        $first_name = null;
        $last_name  = null;
        $user_name  = $validated['user_name'];
        $email      = $validated['email'];
        $password   = $validated['password'];

        $user = User::query()
            ->where('email', $email)
            ->exists();

        if ($user === false) {
            $user_info = User::query()
                ->where('user_name', $user_name)
                ->exists();
              
            if ($user_info === false) {
                $array = [
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'user_name'  => $user_name,
                    'email'      => $email,
                    'password'   => bcrypt($password)
                ];
                $user = User::create($array);

                return response(201);
            } else {
                return response(226);
            }
        } else {
            return response(406);
        }
    }

    public function login(LoginRequest $request)
    {
        $user_or_email     = $request->get('user_or_email');
        $password          = $request->get('password');
        $api_token         = str_random(80);

        if (strpos($user_or_email, '@') == true) {
            $info = 'email';
        } else {
            $info = 'user_name';
        }

        $user = User::query()
            ->where($info, $user_or_email);

        if ($user->exists() === true) {
            if (Hash::check($password, $user->first()->password)) {
                $user->first()->api_token()->create(['api_token' => $api_token]);

                $array = [
                    'api_token' => $api_token,
                    'user'      => $user->select('id', 'user_name', 'role')->first()
                ];

                return response()->json($array);
            } else {
                return response(203);
            }
        } else {
            return response(404);
        }
    }

    public function editUser(UserRequest $request)
    {
        $user = user($request);

        return $user;

        $validated = $request->validated();

        $first_name   = empty($validated['first_name'])   !== false ? $user->first_name : $validated['first_name'];
        $last_name    = empty($validated['last_name'])    !== false ? $user->last_name : $validated['last_name'];
        $username     = empty($validated['username'])     !== false ? $user->user_name : $validated['username'];
        $bio          = empty($validated['bio'])          !== false ? $profile->bio : $validated['bio'];
        $website_link = empty($validated['website_link']) !== false ? $profile->website_link : $validated['website_link'];

        $userUpdate = User::query();

        if ($username === $user->user_name) {
            $userUpdate->where('id', $user->id)->update([
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'user_name'  => $username,
            ]);

            return response()->json(200);
        } else {
            $userCheck = $userUpdate->where('user_name', $username)->exists();

            if ($userCheck === true) {
                return response()->json(226);
            } else {
                User::where('id', $user->id)->update([
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'user_name'  => $username,
                ]);

                return response()->json(200);
            }
        }
    }

    public function logout(Request $request)
    {
        logout($request);
        
        return response(200);
    }
}
