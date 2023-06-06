<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'invoice_details';

    public function product(){
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
    public function invoices(){
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
    public function productCode()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
    public function getDisFtTypeAttribute()
    {
       if ($this->discount > 0 && $this->dis_type=="percent"){
            return $this->discount."%";
       }
       elseif($this->discount > 0 && $this->dis_type=="fix_val")
       {
           return $this->discount."$";
       }
       return $this->discount;
    }

    public function getDisFtTypeShowAttribute()
    {
       if ($this->discount > 0 && $this->dis_type=="percent"){
            return $this->discount."%";
       }
       elseif($this->discount > 0 && $this->dis_type=="fix_val")
       {
           return \Str::numberFormatDollar($this->discount);
       }
       return $this->discount;
    }
}
