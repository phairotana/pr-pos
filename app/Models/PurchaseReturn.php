<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchase_returns';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ref_id',
        'supplier_id',
        'purchase_return_date',
        'grand_total',
        'purchase_return_by',
        'branch_id',
        'purchase_return_note',
        'amount',
        'grand_total',
        'amount_payable',
        'discount_type',
        'discount_amount',
        'purchase_status',
        'due_amount',
        'received_amount',
        'discount_all_type',
        'created_at',
        'updated_at',
        'payment_status'
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

    public function purchaseReturnDetail()
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id', 'id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
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
        return "$ " . number_format(round($this->amount_payable, 2));
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
