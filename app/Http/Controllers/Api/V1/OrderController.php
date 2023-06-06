<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\Product;
use App\Models\MobileOrder;
use Illuminate\Http\Request;
use App\Models\MobileOrderDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\V1\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(MobileOrder::where('user_id', auth('api')->user()->id)->paginate(10));
    }
    public function show($id)
    {
        return new OrderResource(MobileOrder::find($id));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'address_id' => 'required|integer'
        ]);
        if ($validator->fails()){
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        if(is_array($request->items)){
            $order = MobileOrder::create([
                'user_id' => auth('api')->user()->id,
                'address_id' => $request->address_id,
                'status' => 'New'
            ]);
            foreach($request->items as $cart){
                $pro = Product::find($cart['product_id']);
                MobileOrderDetail::create([
                    'mobile_order_id' => $order->id,
                    'product_id' => $cart['product_id'],
                    'price' => $pro->sell_price,
                    'qty' => $cart['qty']
                ]);
            }
            Cart::where('user_id', auth('api')->user()->id)->delete();
            $response = ["message" => "The order created succesfully."];
            return response($response, 200);
        }
        return response(['message' => false], 400);
    }
}
