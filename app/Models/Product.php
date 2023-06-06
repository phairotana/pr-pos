<?php

namespace App\Models;

use App\Models\Stock;
use App\Helpers\Helper;
use App\Models\Storage;
use App\Models\Attribute;
use App\Traits\UploadFiles\UploadFIle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Product extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use UploadFIle;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';
    protected $guarded = ['id'];
    protected $fillable = [
        'product_code',
        'product_name',
        'description',
        'branch_id',
        'category_id',
        'brand',
        'service_item',
        'unit_id',
        'thumnail',
        'images',
        'stock_alert',
        'service_item',
        'location_id',
        'sell_price',
        'cost_price',
        'pre_order',
        'created_by',
        'updated_by',
        'hot'
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
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_products', 'product_id', 'attribute_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function rBrand()
    {
        return $this->belongsTo(Brand::class, 'brand', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id', 'id');
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'location_id');
    }
    public function favourite()
    {
        return $this->hasOne(Favourite::class, 'product_id');
    }
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'id', 'product_id');
    }
    public function getStockQuantityAttribute()
    {
        return optional($this->stock)->quantity ?? 0;
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
    public function getUnitAttribute()
    {
        return optional($this->productUnit)->display;
    }
    public function getUnitNameAttribute()
    {
        return optional($this->productUnit)->name;
    }
    public function getCategoryNameAttribute()
    {
        return optional($this->category)->category_name;
    }
    public function getBranchNameAttribute()
    {
        return optional($this->branch)->branch_name;
    }
    public function getLocationNameAttribute()
    {
        return optional($this->storage)->storage_name;
    }
    public function getCostPriceFormatAttribute()
    {
        return Helper::formatCurrency($this->cost_price, '$');
    }
    public function getSellPriceFormatAttribute()
    {
        return Helper::formatCurrency($this->sell_price, '$');
    }
    public function getProductThumnailAttribute()
    {
        return asset($this->getUploadImage($this->thumnail, 'medium'));
    }
    public function getGalleriesAttribute()
    {
        $data = array();
        $galleries = !empty($this->images) ? json_decode($this->images) : [];
        foreach($galleries as $gallery){
            $data[] = asset($this->getUploadImage($gallery, 'medium'));
        }
        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setThumnailAttribute($value)
    {
        if(\Str::startsWith($value,'data:image')){
            $this->attributes['thumnail'] = $this->base64Upload($value);
            $this->deleteFiel($this->getOriginal('thumnail'));
        }
    }

    public function setImagesAttribute()
    {
        $this->attributes['images'] = $this->MultipleUploads('images', request());
    }
}
