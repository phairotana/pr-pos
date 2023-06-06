<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Purchase extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchases';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ref_id',
        'supplier_id',
        'purchase_date',
        'grand_total',
        'purchase_by',
        'branch_id',
        'purchase_note',
        'amount',
        'amount_payable',
        'discount_type',
        'received_amount',
        'due_amount',
        'discount_amount',
        'purchase_status',
        'payment_status',
        'discount_all_type'
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
    public function purchaseBy()
    {
        return $this->belongsTo(User::class, 'purchase_by');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function purchaseDetail(){
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }
    public function shipper()
    {
        return $this->hasOne(Shipping::class, 'purchase_id', 'id');
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

    public function GetPurchaseDateFormatAttribute()
    {
        return Carbon::parse($this->purchase_date)->format('d-m-Y');
    }

    public function GetGrandTotalAttribute()
    {
        return Helper::formatCurrency($this->amount_payable, '$');
    }
    public function getAmountPaidAttribute()
    {
        return Helper::formatCurrency($this->received_amount, '$');
    }
    public function getAmountDueAttribute()
    {
        return Helper::formatCurrency($this->due_amount, '$');
    }
    public function getDateAttribute()
    {
        return Carbon::parse($this->purchase_date)->format('d-m-Y');
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
