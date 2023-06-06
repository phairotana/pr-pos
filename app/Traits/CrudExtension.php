<?php

namespace App\Traits;

use App\Models\Customer;
use App\Models\Shipping;
use App\Models\Supplier;
use Illuminate\Http\Request;

trait CrudExtension
{
    public function titleHead($text, $class = 'mt-3')
    {
        return '<nav class="navbar navbar-light bg-light ' . $class . '">
                    <span class="navbar-brand mb-0 h4">' . $text . '</span>
                </nav>';
    }
    public function customerOptions(Request $request)
    {
        $term = $request->input('term');
        return Customer::where('customer_name', 'like', '%' . $term . '%')->get()->pluck('customer_name', 'id');
    }
    public function supplierOptions(Request $request)
    {
        $term = $request->input('term');
        return Supplier::where('supplier_name', 'like', '%' . $term . '%')->get()->pluck('supplier_name', 'id');
    }
    public function mergeValidateProductDetails()
    {
        return array_map(function ($val) {
            if ($val->qty == '') {
                $val->qty = null;
            }
            if ($val->cost_price == '') {
                $val->cost_price = null;
            }
            if ($val->sell_price == '') {
                $val->sell_price = null;
            }
            if ($val->discount_amount == '') {
                $val->discount_amount = null;
            }
            return $val;
        }, json_decode(request()->product_detail) ?? []);
    }
    public function mergeDueAmountRequest()
    {
        $this->mergePaymentRequest();
        $dueAmount = request()->amount_payable - request()->received_amount;
        if ($dueAmount < 0) {
            $dueAmount = 0;
        }
        request()->merge(['due_amount' => $dueAmount]);
    }
    public function mergePaymentRequest()
    {
        if (request()->payment_status == "Paid") {
            request()->merge(['received_amount' => request()->amount_payable]);
        }
    }
    public function addGrandTotalRequest()
    {
        if (request()->ship_amount) {
            request()->merge(['grand_total' => request()->amount - request()->ship_amount]);
        }
    }
    public function addToShippers()
    {
        if (request()->shipper_name !== null || request()->ship_amount || null) {
            Shipping::create([
                'purchase_id' => $this->crud->entry->id,
                'shipper_name' => request()->shipper_name,
                'shipper_address' => request()->shipper_address,
                'shipper_contact' => request()->shipper_contact,
                'ship_via' => request()->shipper_via,
                'ship_amount' => request()->ship_amount
            ]);
        }
    }
    public function mergeAmountAndAmountPayable()
    {
        request()->merge([
            'amount' => request()->amount + request()->discount_amount
        ]);
    }

    // --Rotana
    public function mergeFileds()
    {
        request()->merge([
            'amount' => request()->amount + request()->discount_amount,
            'amount_payable' => (request()->amount - request()->ship_amount ?? 0 - request()->discount_amount),
            'discount_amount' => request()->discount_amount,
            'received_amount' => 0,
            'due_amount' => 0
        ]);
    }
}
