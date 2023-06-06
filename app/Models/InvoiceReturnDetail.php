<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReturnDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_return_details';
    protected $guarded = ['id'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
    public function productCode()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
