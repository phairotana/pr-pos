<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=> $validator->errors()->all()], 422);
        }

        DeviceToken::firstOrCreate([
            'user_id' => auth('api')->user()->id,
            'device_token' => $request->device_token
        ]);

        $response = ["message" => "The process created succesfully."];
        return response($response, 200);
    }
}
