<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MobileOrder extends Model
{
    use CrudTrait, HasFactory;

    protected $table = "mobile_orders";

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function details()
    {
        return $this->hasMany(MobileOrderDetail::class, 'mobile_order_id', 'id');
    }
}
