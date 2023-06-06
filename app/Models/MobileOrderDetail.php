<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MobileOrderDetail extends Model
{
    use CrudTrait, HasFactory;

    protected $table = "mobile_order_details";

    protected $guarded = ['id'];

    public function mobileOrder()
    {
        return $this->belongsTo(MobileOrder::class, 'mobile_order_id', 'id');
    }
}
