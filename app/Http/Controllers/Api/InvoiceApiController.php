<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;

class InvoiceApiController extends Controller
{
    public function getInvoiceDetail(Request $request)
    {
        if($request->check_qty){
            $data = InvoiceDetail::where('invoice_id', $request->invoice_id)
            ->where('product_id', $request->product_id)
            ->first();
            if($data->qty < $request->current_value){
                return $data->qty;
            }
        }else{
            $data = InvoiceDetail::where('invoice_id', $request->invoice_id)->with('product')->get();
            return response()->json($data);
        }
    }
}
