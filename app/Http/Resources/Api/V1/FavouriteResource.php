<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $product->category_name,
            'discount'      => 0,
            'favourite'     => !empty($product->favourite) ? true : false,
            'price'         => $product->sell_price,
            'price_format'  => '$'.number_format($product->sell_price, 2),
            'description'   => $product->description,
            'image'         => $product->ProductThumnail,
            'gallery'       => $product->galleries
        ];
    }
}
