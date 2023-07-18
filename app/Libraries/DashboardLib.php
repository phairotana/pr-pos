<?php

namespace App\Libraries;

use App\Helpers\Helper;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\InvoiceReturn;
use App\Models\Payment;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;

class DashboardLib
{
    public static function recentSales()
    {
        return Invoice::join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->whereBetween('invoices.invoice_date', [Helper::thisMonthBetween()[0], Helper::thisMonthBetween()[1]])
            ->select('invoices.*', 'customers.customer_name')
            ->orderByRaw('invoices.id')->get();
    }
    public static function stockAlert()
    {
        return Product::leftJoin('stocks', 'stocks.product_id', '=', 'products.id')
            ->leftJoin('branches', 'branches.id', '=', 'products.branch_id')
            ->whereRaw('products.stock_alert > stocks.quantity')
            ->select('products.*', 'stocks.quantity', 'branches.branch_name')
            ->orderByRaw('products.id')->get();
    }

    public static function salesReturnDash()
    {
        return InvoiceReturn::select('invoice_return_date, received_amount, invoice_status')
            ->whereIn('invoice_status', ['Receive', 'Partial Receive'])
            ->whereBetween('invoice_return_date', [Helper::thisMonthBetween()[0], Helper::thisMonthBetween()[1]])
            ->sum('received_amount');
    }
    public static function purchasesReturnDash()
    {
        return PurchaseReturn::select('purchase_return_date, amount_payable, purchase_status')
            ->where('purchase_status', ['Receive', 'Partial Receive'])
            ->whereBetween('purchase_return_date', [Helper::thisMonthBetween()[0], Helper::thisMonthBetween()[1]])
            ->sum('amount_payable');
    }

    public static function weeklySalesDash($date)
    {
        return Invoice::select('invoice_date, amount_payable, invoice_status')
            ->where('invoice_status', ['Receive', 'Partial Receive'])
            ->whereDate('invoice_date', $date)
            ->sum('amount_payable');
    }
    public static function weeklyPurchasesDash($date)
    {
        return Purchase::select('purchase_date, amount_payable, purchase_status')
            ->where('purchase_status', ['Receive', 'Partial Receive'])
            ->whereDate('purchase_date', $date)
            ->sum('amount_payable');
    }
    public static function topFiveCustomers()
    {
        $result = [];
        $topFiveCustome = Invoice::with('customer')
            ->addSelect(DB::raw('SUM(amount_payable) as total_amount, customer_id'))
            ->groupBy('customer_id')->take(5)
            ->orderBy('total_amount', 'DESC')->get();
        foreach ($topFiveCustome as $item) {
            array_push($result, ['customer_name' => optional($item->customer)->customer_name, 'total_amount' => $item->total_amount]);
        }
        return $result;
    }
    public static function topTenSellingProducts()
    {
        return InvoiceDetail::addSelect(DB::raw('SUM(qty) as total_qty, SUM(total_payable) as grand_total, product_code, product_name'))
            ->whereBetween('created_at', [Helper::thisMonthBetween()[0], Helper::thisMonthBetween()[1]])
            ->groupBy('product_name')
            ->groupBy('product_code')
            ->orderBy('total_qty', 'DESC')->get();
    }


    // public static function salesDash()
    public static function salesDash($from, $to)
    {
        return Invoice::select('invoice_date, received_amount, invoice_status')
            ->whereIn('invoice_status', ['Receive', 'Partial Receive'])
            ->whereBetween('invoice_date', [$from ?? Helper::thisMonthBetween()[0], $to ?? Helper::thisMonthBetween()[1]])
            ->sum('received_amount');
    }
    public static function purchasesDash($from, $to)
    {
        return Purchase::select('purchase_date, amount_payable, purchase_status')
            ->whereIn('purchase_status', ['Receive', 'Partial Receive'])
            ->whereBetween('purchase_date', [$from ?? Helper::thisMonthBetween()[0], $to ?? Helper::thisMonthBetween()[1]])
            ->sum('amount_payable');
    }
    public static function monthlyExpense($from, $to)
    {
        $amount = Expense::whereBetween('expense_date', [$from ?? Helper::thisMonthBetween()[0], $to ?? Helper::thisMonthBetween()[1]])
            ->sum('amount');
        return Helper::calculatNumberToK($amount);
    }
    public static function monthlyPayment($from, $to)
    {
        $amount = Payment::whereBetween('created_at', [$from ?? Helper::thisMonthBetween()[0], $to ?? Helper::thisMonthBetween()[1]])
            ->sum('amount');
        return Helper::calculatNumberToK($amount);
    }
}
