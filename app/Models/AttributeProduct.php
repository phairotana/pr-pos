<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    use HasFactory;

    protected $table = 'attribute_products';
    protected $guarded = ['id'];
    protected $fillable = [
        'product_id',
        'category_id',
    ];
}
