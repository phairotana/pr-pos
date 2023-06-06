<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'kh_address';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Address has been {$eventName}";
    }

    public function getFullAddressAttribute()
    {
        return implode(', ', array_reverse(explode('/', $this->_path_en)));
    }

    public function getCityAttribute()
    {
        list($city)  = explode('/', $this->_path_en);
        return str_replace('Province', '', $city);
    }

    public function getFullAddressKhAttribute()
    {
        return trim(implode(' ', array_reverse(explode('/', $this->_path_kh))));
    }
}
