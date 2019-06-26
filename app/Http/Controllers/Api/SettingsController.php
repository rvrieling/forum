<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function updateSort(Request $request)
    {
        $user = user($request);

        UserSetting::where('user_id', $user->id)->update([
            'sort_by' => $request->sort_by
        ]);

        return response()->json(200);
    }
}
