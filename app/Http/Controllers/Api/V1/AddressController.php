<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\V1\AddressResource;

class AddressController extends Controller
{
    public function index()
    {
        return AddressResource::collection(Place::where('user_id', auth('api')->user()->id)->get());
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|string',
            'long' => 'required|string',
            'place' => 'required|string',
            'address' => 'required|string',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'contact_number' => 'required|string'
        ]);
        if ($validator->fails())
        {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        Place::create([
            'user_id' => auth('api')->user()->id,
            'lat' => $request->lat,
            'long' => $request->long,
            'place' => $request->place,
            'address' => $request->address,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'contact_number' => $request->contact_number
        ]);
        $response = ["message" => "The process created succesfully."];
        return response($response, 200);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|string',
            'long' => 'required|string',
            'place' => 'required|string',
            'address' => 'required|string',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'contact_number' => 'required|string'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=> $validator->errors()->all()], 422);
        }
        Place::where('id', $id)->update([
            'lat' => $request->lat,
            'long' => $request->long,
            'place' => $request->place,
            'address' => $request->address,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'contact_number' => $request->contact_number
        ]);
        $response = ["message" => "The process updated succesfully."];
        return response($response, 200);
    }
    public function destroy($id)
    {
        Place::destroy($id);
        $response = ["message" => "The address deleted succesfully."];
        return response($response, 200);
    }
}
