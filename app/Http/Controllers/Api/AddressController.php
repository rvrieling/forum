<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index($name)
    {
        return Address::query()
            ->user($name)
            ->first();
    }

    public function create(Request $request)
    {
        $user = user($request);

        $streetname  = empty($request->input('streetname'))  == false ? $request->input('streetname') : null;
        $housenumber = empty($request->input('housenumber')) == false ? $request->input('housenumber') : null;
        $zipcode     = empty($request->input('zipcode'))     == false ? $request->input('zipcode') : null;
        $city        = empty($request->input('city'))        == false ? $request->input('city') : null;
        $state       = empty($request->input('state'))       == false ? $request->input('state') : null;

        Address::query()
            ->create([
                'user_id'     => $user->id,
                'streetname'  => $streetname,
                'housenumber' => $housenumber,
                'zipcode'     => $zipcode,
                'city'        => $city,
                'state'       => $state,
            ]);

        return response()->json(201);
    }

    public function edit(Request $request)
    {
        $user = user($request);

        $address = Address::query()
            ->where('user_id', $user->id)
            ->first();

        $streetname  = empty($request->input('streetname'))  == false ? $request->input('streetname') : $address->streetname;
        $housenumber = empty($request->input('housenumber')) == false ? $request->input('housenumber') : $address->housenumber;
        $zipcode     = empty($request->input('zipcode'))     == false ? $request->input('zipcode') : $address->zipcode;
        $city        = empty($request->input('city'))        == false ? $request->input('city') : $address->city;
        $state       = empty($request->input('state'))       == false ? $request->input('state') : $address->state;

        if ($address->user_id === $user->id) {
            $address->update([
                'streetname'  => $streetname,
                'housenumber' => $housenumber,
                'zipcode'     => $zipcode,
                'city'        => $city,
                'state'       => $state,
            ]);

            return response()->json(201);
        }

        return response()->json(401);
    }

    public function delete(Request $request)
    {
        $user = user($request);

        Address::query()
            ->where('user_id', $user->id)
            ->delete();
            
        return response()->json(410);
    }
}
