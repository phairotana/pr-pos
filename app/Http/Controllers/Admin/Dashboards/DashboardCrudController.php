<?php

namespace App\Http\Controllers\Admin\Dashboards;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Expense;
use App\Models\Invoice;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Libraries\DashboardLib;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Backpack\CRUD\app\Http\Controllers\CrudController;


/**
 * Class BranchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (backpack_user() && !backpack_user()->hasAnyRole(\Spatie\Permission\Models\Role::all())) {
                Auth::logout();
                return redirect('admin/login');
            }
            return $next($request);
        });
    }
    public function fetching()
    {
        $data['expense_dash'] = DashboardLib::monthlyExpense(request()->from, request()->to);
        $data['payment_dash'] = DashboardLib::monthlyPayment(request()->from, request()->to);
        $data['sales_dash'] = Helper::calculatNumberToK(DashboardLib::salesDash(request()->from, request()->to));
        $data['purchases_dash'] = Helper::calculatNumberToK(DashboardLib::purchasesDash(request()->from, request()->to));
        return response()->json($data);
    }

    public function index()
    {
        $data['recent_sales_dash'] = DashboardLib::recentSales();
        $data['stock_alert_dash'] = DashboardLib::stockAlert();
        $data['sales_return_dash'] = Helper::calculatNumberToK(DashboardLib::salesReturnDash());
        $data['purchases_return_dash'] = Helper::calculatNumberToK(DashboardLib::purchasesReturnDash());
        $data['top_ten_selling_products_dash'] = DashboardLib::topTenSellingProducts();
        return view('admin.dashboards.index', $data);
    }
    public function payment(Request $request)
    {
        $query = (new Invoice)->newQuery();
        $query->with(['invoiceDetails']);
        $query->select('created_at as date', 'amount_payable', 'received_amount', 'due_amount');
        $query->selectRaw('lpad(id, 6, 0) as invoice');
        if ($request->ajax()) {
            Session::forget(['status', 'customer', 'startDate', 'endDate']);
            if (!empty(request()->status)) {
                Session::put('status', request()->status);
                if (request()->status == 'pending') {
                    $query->where(function ($q) {
                        $q->whereNull('received_amount');
                        $q->orWhereRaw('received_amount < amount_payable');
                    });
                }
                if (request()->status == 'paid') {
                    $query->whereRaw('received_amount = amount_payable');
                }
            }
            if (!empty(request()->customer)) {
                Session::put('customer', request()->customer);
                $query->where('customer_id', request()->customer);
            }
            if (!empty(request()->startDate)) {
                Session::put('startDate', request()->startDate);
                $query->whereDate('created_at', '>=', Carbon::parse(request()->startDate)->format('Y-m-d'));
            }
            if (!empty(request()->endDate)) {
                Session::put('endDate', request()->endDate);
                $query->whereDate('created_at', '<=', Carbon::parse(request()->endDate)->format('Y-m-d'));
            }
            $data = $query->orderBy('created_at', 'desc')->get();
            $dataTable = Datatables::of($data);
            return $dataTable
                ->addIndexColumn()
                ->make(true);
        }
        $data = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('reports.payment', $data);
    }
    public function printPayment()
    {
        $query = (new Invoice)->newQuery();
        $query->with(['branch', 'customer', 'invoiceDetails']);

        if (Session::has('startDate')) {
            $query->whereDate('created_at', '>=', Carbon::parse(Session::get('startDate'))->format('Y-m-d'));
        }
        if (Session::has('endDate')) {
            $query->whereDate('created_at', '<=', Carbon::parse(Session::get('endDate'))->format('Y-m-d'));
        }
        if (Session::has('customer')) {
            $query->where('customer_id', Session::get('customer'));
        }
        if (Session::has('status')) {
            if (Session::get('status') == 'pending') {
                $query->where(function ($q) {
                    $q->whereNull('received_amount');
                    $q->orWhereRaw('received_amount < amount_payable');
                });
            }
            if (Session::get('status') == 'paid') {
                $query->whereRaw('received_amount = amount_payable');
            }
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return view('doc.prints.payment', compact('data'));
    }

    public function profit_and_loss()
    {
        return view('reports.profit_and_loss');
    }

    public function fetch_profit_data()
    {
        $queryInvoice = (new Invoice)->newQuery();
        $queryPurchase = (new Purchase)->newQuery();
        $queryExpense = (new Expense)->newQuery();

        $queryInvoice->select('id', 'amount_payable');
        $queryPurchase->select('id', 'amount_payable');
        $queryExpense->select('id', 'amount');

        if (!empty(request()->fromDate)) {
            $sd = Carbon::parse(request()->fromDate)->format('Y-m-d');
            $queryInvoice->whereDate('invoice_date', '>=', $sd);
            $queryPurchase->whereDate('purchase_date', '>=', $sd);
            $queryExpense->whereDate('expense_date', '>=', $sd);
        }
        if (!empty(request()->toDate)) {
            $ed = Carbon::parse(request()->toDate)->format('Y-m-d');
            $queryInvoice->whereDate('invoice_date', '<=', $ed);
            $queryPurchase->whereDate('purchase_date', '<=', $ed);
            $queryExpense->whereDate('expense_date', '<=', $ed);
        }
        $invoice = $queryInvoice->get()->sum('amount_payable');
        $purchase = $queryPurchase->get()->sum('amount_payable');
        $data['invoice'] = number_format($invoice, 2);
        $data['purchase'] = number_format($purchase, 2);
        $data['expense'] = number_format($queryExpense->get()->sum('amount'), 2);
        $data['profit'] = number_format($invoice - $purchase, 2);
        return $data;
    }
}
