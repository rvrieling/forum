<?php


use App\Models\ApiToken;
use App\Models\User;

function logout($request)
{
    $api_token = $request->header('Authorization');
    $str       = str_replace('Bearer ', '', $api_token);

    ApiToken::query()
        ->where('api_token', $str)
        ->delete();
}

function user($request)
{
    $api_token = $request->header('Authorization');
    $str       = str_replace('Bearer ', '', $api_token);

    $user = User::query()
        ->with('profile')
        ->whereHas('api_token', function ($query) use ($str) {
            $query->where('api_token', $str);
        })
        ->first();

    return $user;
}
