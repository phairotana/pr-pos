<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = (new Product())->newQuery();
        $query->orderBy('id', 'DESC');
        if($request->search){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('product_name','like','%'.$search.'%');
                $q->orWhere('product_code','like','%'.$search.'%');
            });
        }
        if($request->hot){
            $query->where('hot',1);
        }
        if($request->category_id){
            $query->where('category_id',$request->category_id);
        }
        if($request->relate && $request->product_id){
            $current = Product::find($request->product_id);
            $query->where('category_id',$current->category_id);
            $query->where('id','<>',$request->product_id);
        }
        $product = $query->paginate(10)->appends(request()->query());
        return ProductResource::collection($product);
    }
    public function show($id)
    {
        $product = Product::find($id);
        return new ProductResource($product);
    }
}
