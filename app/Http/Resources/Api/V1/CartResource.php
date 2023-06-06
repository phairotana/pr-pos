<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = optional($this->product);
        return [
            'id' => $product->id,
            'cart_id' => $this->id,
            'product' => $product->category_name,
            'name' => $product->category_name,
            'price_format' => '$'.number_format($product->sell_price, 2),
            'price' => $product->sell_price,
            'qty' => $this->qty,
            'description' => $product->description,
            'image' => $product->ProductThumnail
        ];
    }
}
