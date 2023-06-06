<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\V1\FavouriteResource;

class FavouriteController extends Controller
{
    public function index()
    {
        return FavouriteResource::collection(Favourite::where('user_id', auth('api')->user()->id)->get());
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|unique:favourites,product_id'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        Favourite::create([
            'user_id' => auth('api')->user()->id,
            'product_id' => $request->product_id
        ]);
        $response = ["message" => "The favourite created succesfully."];
        return response($response, 200);
    }
    public function delete($id)
    {
        Favourite::destroy($id);
        $response = ["message" => "The favourite created succesfully."];
        return response($response, 200);
    }
}
