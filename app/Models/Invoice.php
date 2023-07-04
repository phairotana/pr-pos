<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Invoice extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'invoices';
    protected $guarded = ['id'];
    protected $fillable = [
        'amount',
        'amount_payable',
        'due_amount',
        'ref_id',
        'discount_type',
        'discount_amount',
        'customer_id',
        'seller_id',
        'branch_id',
        'invoice_date',
        'payment_status',
        'payment_choice',
        'received_amount',
        'paying_amount',
        'change_amount',
        'shipping_id',
        'noted',
        'credit',
        'credit_date',
        'invoice_status',
        'status_reason',
        'discount_all_type',
        'total_cost',
        'discount_percent',
        'discount_fixed_value'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function customerInvoice($crud)
    {
        return '<a class="btn btn-sm btn-info" target="_blank" href="' . URL($crud->route.'/'. $this->id . '/print?mode=customer') . '" data-toggle="tooltip" title="Print Invoice customer"><i class="la la-print"></i></a>';
    }
    public function deliverInvoice($crud)
    {
        return '<a class="btn btn-sm btn-primary" target="_blank" href="' . URL($crud->route.'/'. $this->id . '/print?mode=deliver') . '" data-toggle="tooltip" title="Print Invoice for deliver"><i class="la la-truck"></i></a>';
    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function shipper()
    {
        return $this->belongsTo(Shipping::class, 'shipper_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'reference_id', 'id');
    }
    public function invoiceReturns()
    {
        return $this->hasMany(InvoiceReturn::class, 'invoice_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOnlyPassDue($query)
    {
        return  $query->whereDate('credit_date', '<=', \Carbon\Carbon::today())->where('due_amount', '>', DB::raw('received_amount'));
    }
    public function scopeSearchCode($query, $searchText)
    {
        return $query->orWhere('id', 'like', "%" . ltrim($searchText, "0") . "%");
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function GetInvoiceDateFormatAttribute()
    {
        return Carbon::parse($this->invoice_date)->format('d-m-Y');
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
    public function getCodeAttribute()
    {
        return str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
