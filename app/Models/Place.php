<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $table = 'address';
    protected $fillable = [
        'user_id', 'lat', 'long', 'place', 'address', 'last_name', 'first_name', 'contact_number'
    ];
    
}
