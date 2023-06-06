<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use CrudTrait,HasFactory;
    protected $table = "quotation_details";
    protected $guarded = ['id'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
