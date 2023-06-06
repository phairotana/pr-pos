<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use CrudTrait;

    use HasFactory;
    protected $table = "purchase_details";

    protected $guarded = ['id'];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id', 'id');
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
