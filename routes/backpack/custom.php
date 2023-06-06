<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PrintController;
use App\Http\Controllers\Admin\InvoiceCrudController;
use App\Http\Controllers\Admin\PurchaseCrudController;
use App\Http\Controllers\Admin\QuotationsCrudController;
use App\Http\Controllers\Admin\InvoiceReturnCrudController;
use App\Http\Controllers\Admin\PurchaseReturnCrudController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
Route::get('/home', function () {
    return redirect()->to('/');
});


Route::group([
    'prefix' => 'api',
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Api'
], function () {
    Route::get('product/fetch', 'SelectMultiApiController@productSearch')->name('admin.api.product_search');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('/', function () {
        return redirect('admin/dashboard');
    });
    Route::crud('customers', 'CustomerCrudController');
    Route::crud('supplier', 'SupplierCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('attribute', 'AttributeCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('stock', 'StockCrudController');
    Route::crud('branch', 'BranchCrudController');
    Route::crud('brand', 'BrandCrudController');
    Route::crud('payment', 'PaymentCrudController');
    // DASHBOARD
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Dashboards'], function () {
        Route::get('/', 'DashboardCrudController@index')->name('index');
        Route::get('charts/weekly-sale-purchase', 'Charts\WeeklySalePurchaseChartController@response');
        Route::get('charts/top-five-customer', 'Charts\TopFiveCustomersChartController@response');
    });
    // Route Restore
    Route::get('customers/{id}/restore', 'CustomerCrudController@restore');
    Route::get('supplier/{id}/restore', 'SupplierCrudController@restore');
    Route::get('category/{id}/restore', 'CategoryCrudController@restore');
    Route::get('product/{id}/restore', 'ProductCrudController@restore');
    Route::get('customer-group/{id}/restore', 'CustomerGroupCrudController@restore');
    Route::get('attribute/{id}/restore', 'AttributeCrudController@restore');
    Route::get('stock/{id}/restore', 'StockCrudController@restore');
    Route::get('branch/{id}/restore', 'BranchCrudController@restore');
    Route::get('storage/{id}/restore', 'StorageCrudController@restore');

    Route::crud('product/print-barcode', 'PrintBarcodeCrudController');
    Route::get('product/print-barcode', 'PrintBarcodeCrudController@barcode');
    Route::get('product/print-barcode/{id}', 'PrintBarcodeCrudController@printBarcode');

    Route::crud('storage', 'StorageCrudController');
    Route::crud('purchase', 'PurchaseCrudController');
    Route::crud('purchase-return', 'PurchaseReturnCrudController');

    Route::crud('invoice', 'InvoiceCrudController');
    Route::crud('option', 'OptionCrudController');
    Route::crud('expense', 'ExpenseCrudController');
    Route::crud('invoice-return', 'InvoiceReturnCrudController');
    Route::post('quotations/convert/{id}', 'QuotationsCrudController@convert');
    Route::crud('quotations', 'QuotationsCrudController');
    Route::crud('customer-group', 'CustomerGroupCrudController');
    Route::crud('adjustment', 'AdjustmentCrudController');

    // Reports
    Route::crud('report/customer', 'Reports\CustomerReportCrudController');
    Route::crud('report/supplier', 'Reports\SupplierReportCrudController');
    Route::crud('report/purchased', 'Reports\PurchaseReportCrudController');
    Route::crud('report/invoice', 'Reports\InvoiceReportCrudController');
    Route::crud('report/invoice/return', 'Reports\InvoiceReturnReportCrudController');
    Route::crud('product-unit', 'ProductUnitCrudController');
    Route::crud('report/product-alert', 'Reports\ProductAlertReportCrudController');
    Route::crud('report/expenses', 'Reports\ExpenseReportCrudController');
    Route::get('report/payment', 'Dashboards\DashboardCrudController@payment');

    /* filter extension */
    Route::get('crud_extension/ajax-category-options', 'InvoiceCrudController@customerOptions');
    Route::get('crud_extension/ajax-supplier-options', 'InvoiceCrudController@supplierOptions');
    // Print
    Route::post('/invoice/edit-status', [InvoiceCrudController::class, 'editStatus']);
    Route::get('/invoice/{id}/print', [InvoiceCrudController::class, 'printInvoice']);
    Route::get('/quotation/{id}/print', [QuotationsCrudController::class, 'print']);
    Route::get('/invoice-return/{id}/print', [InvoiceReturnCrudController::class, 'printInvoice']);
    Route::post('/invoicereturn/edit-status', [InvoiceReturnCrudController::class, 'editStatus']);
    Route::post('/purchase/edit-status', [PurchaseCrudController::class, 'editStatus']);
    Route::post('/purchasereturn/edit-status', [PurchaseReturnCrudController::class, 'editStatus']);
    Route::post('/qoutationstatus/edit-status', [QuotationsCrudController::class, 'editStatus']);
    Route::post('products-import', 'ProductCrudController@importData')->name('products-import');
    Route::get('invoice/{id}/normal-print', 'InvoiceCrudController@print');
    Route::get('report/payment/print', 'Dashboards\DashboardCrudController@printPayment');
    Route::get('get-invoice/{id}', [InvoiceCrudController::class, 'getInvoice']);
    Route::get('get-quotation/{id}', [QuotationsCrudController::class, 'getQuotation']);

    // Route::get('report/profit_and_loss', 'Dashboards\DashboardCrudController@profit_and_loss');

    Route::get('report/profit_and_loss', 'Dashboards\DashboardCrudController@profit_and_loss');
    Route::post(
        'report/fetch_profit_data',
        'Dashboards\DashboardCrudController@fetch_profit_data'
    )->name('admin.report.fetch_profit_data');
});
