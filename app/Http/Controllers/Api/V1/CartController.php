<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\V1\CartResource;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth('api')->user()->id)->get();
        $subTotal = $cart->sum(function ($item) {
            $price = optional($item->product)->sell_price ?? 0;
            return $price * $item->qty;
        });
        return CartResource::collection($cart)->additional([
            'sub_total' => '$'.number_format($subTotal, 2),
            'delivery_fee' => 0,
            'total' => '$'.number_format($subTotal, 2)
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|gt:0',
            'product_id' => 'required|integer|unique:carts,product_id'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        Cart::create([
            'user_id' => auth('api')->user()->id,
            'product_id' => $request->product_id,
            'qty' => $request->qty
        ]);
        $response = ["message" => "The cart created succesfully."];
        return response($response, 200);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|gt:0'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        Cart::where('id', $id)->update([
            'qty' => $request->qty
        ]);
        $response = ["message" => "The cart updated succesfully."];
        return response($response, 200);
    }
    public function delete($id)
    {
        Cart::destroy($id);
        $response = ["message" => "The cart deleted succesfully."];
        return response($response, 200);
    }
}
