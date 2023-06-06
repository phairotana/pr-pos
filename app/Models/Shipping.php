<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table= 'shippings';
    protected $guarded = ['id'];
}
