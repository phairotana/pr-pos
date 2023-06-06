<?php

namespace App\Libraries;

use App\Models\Invoice;
use App\Models\InvoiceReturn;
use App\Models\Payment;
use App\Models\Quotations;

class CustomerLib
{
    public static function customerQuotations($cusId)
    {
        return Quotations::select('seller_id', 'customer_id', 'ref_id', 'quotation_date', 'status', 'amount', 'branch_id')
            ->where('customer_id', $cusId)->get();
    }

    public static function customerSale($cusId)
    {
        return Invoice::select(
            'ref_id',
            'seller_id',
            'customer_id',
            'payment_status',
            'invoice_date',
            'amount_payable',
            'received_amount',
            'due_amount',
            'invoice_status',
            'branch_id'
        )->where('customer_id', $cusId)->get();
    }
    public static function customerReturn($cusId)
    {
        return InvoiceReturn::select(
            'ref_id',
            'seller_id',
            'customer_id',
            'payment_status',
            'invoice_return_date',
            'amount',
            'received_amount',
            'discount_amount',
            'due_amount',
            'invoice_status',
            'branch_id'
        )->where('customer_id', $cusId)->get();
    }
    public static function totalData($cusId)
    {
        $invoice = self::customerSale($cusId);
        $invoiceReturn = self::customerReturn($cusId);

        $total_sale = $invoice->count();
        $total_amount = $invoice->sum('amount_payable') - ($invoiceReturn->sum('amount') - $invoiceReturn->sum('discount_amount'));
        $paid_amount = $invoice->sum('received_amount') - $invoiceReturn->sum('received_amount');
        $due_amount = $invoice->sum('due_amount') - $invoiceReturn->sum('due_amount');
        return [
            'sale' => $total_sale,
            'total_amount' => $total_amount,
            'paid_amount' => $paid_amount,
            'due_amount' => $due_amount
        ];
    }
    public static function salePayment($cusId)
    {
        return Payment::whereHas('invoice', function ($query) use ($cusId) {
            $query->where('customer_id', $cusId);
        })->get();
    }
}
