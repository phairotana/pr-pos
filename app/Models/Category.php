<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\UploadFiles\UploadFIle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Category extends Model
{
    use CrudTrait;
    use UploadFIle;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'categories';
    protected $guarded = ['id'];
    protected $fillable = [
        'category_name',
        'image',
        'created_by',
        'updated_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getCreatedBysAttribute()
    {
        return optional($this->createBy)->name ?? "";
    }
    public function getUpdatedBysAttribute()
    {
        return optional($this->updateBy)->name ?? "";
    }
    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }
    public function getMediumImageAttribute()
    {
        return asset($this->getUploadImage($this->image, 'medium'));
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImageAttribute($value)
    {
        if(\Str::startsWith($value,'data:image')){
            $this->attributes['image'] = $this->base64Upload($value);
            $this->deleteFiel($this->getOriginal('image'));
        }
    }
}
