<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Storage;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Stock extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'stocks';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'stock_code',
        'product_id',
        'branch_id',
        'quantity',
        'purchase',
        'sale_out',
        'description',
        'product_code',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    // protected $hidden = [];
    // protected $dates = [];

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
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_location');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOnlyOutStock($query)
    {
        return $query->whereHas('product',function($q){
            $q->where('stock_alert', '>=', \DB::raw('quantity'));
        });
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getProductNameAttribute()
    {
        return optional($this->product)->product_name;
    }
    public function getBranchNameAttribute()
    {
        return optional($this->branch)->branch_name;
    }
    public function getSupplierNameAttribute()
    {
        return optional($this->supplier)->supplier_name;
    }
    public function getStorageAttribute()
    {
        return optional($this->storage)->storage_name;
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
