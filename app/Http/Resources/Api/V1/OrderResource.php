<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        switch (request()->route()->action['as']) {
            case 'v1.order.show':
                return [
                    'details' => $this->details($this->details)
                ];
            default:
                $detail = optional($this->details);
                $totalPrice = $detail->map(function($item){
                    return $item['qty']*$item['price'];
                })->toArray();
                return [
                    'id' => $this->id,
                    'total_qty' => $detail->sum('qty'),
                    'total_price' => '$'.number_format(array_sum($totalPrice), 2),
                    'status' => $this->status,
                    'date' => Carbon::parse($this->created_at)->format('d F Y')
                ];
        }
    }
    public function details($details)
    {
        $data = [];
        foreach($details as $detail){
            $product = Product::find($detail->product_id);
            array_push($data,[
                'id'            => $product->id,
                'name'          => $product->product_name,
                'description'   => $product->description,
                'sell_price'    => '$'.number_format($product->sell_price,2),
                'image'         => $product->ProductThumnail
            ]);
        }
        return $data;
    }
}
