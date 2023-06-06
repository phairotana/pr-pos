<?php

namespace App\Constraints;

use App\Models\Attribute;

class Constraint
{
    public static $AttributeType = [
        'Size' => 'Size',
        'Color' => 'Color',
        'Condition' => "Condition",
        'Packaging' => 'Packaging',
    ];
    public static function ATTRIBUTETE($type){
       return Attribute::where('type',$type)->get()->pluck('name', 'name');

    }

}
