<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->product_name,
            'description'       => $this->description,
            'discount'          => 0,
            'favourite'         => !empty($this->favourite) ? true : false,
            'sell_price'        => $this->sell_price,
            'sell_price_format' => '$'.number_format($this->sell_price,2),
            'image'             => $this->ProductThumnail,
            'gallery'           => $this->galleries
        ];
    }
}
