<?php

namespace App\Http\Controllers\Admin\Reports;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\RolePermission;
use Yajra\DataTables\Facades\DataTables;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductAlertReportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        if (backpack_user() && !backpack_user()->hasAnyRole(\Spatie\Permission\Models\Role::all())){
            \Auth::logout();
            return redirect('admin/login');
        }
        RolePermission::checkPermission($this->crud, 'products');
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . 'report/product-alert');
        CRUD::setEntityNameStrings('report/product-alert', 'product alert report');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $view = 'reports.productAlertReport';
        if ($request->ajax()) {
            $start = request()->startDate;
            $end = request()->endDate;
            $data = Product::leftJoin('stocks', 'stocks.product_id', '=', 'products.id')
                ->leftJoin('branches', 'branches.id', '=', 'products.branch_id')
                ->whereRaw('products.stock_alert > stocks.quantity')
                ->where(function($q) use($start,$end) {
                    if(!empty($start)) { $q->whereDate('products.created_at', '>=', Carbon::parse($start)->format('Y-m-d')); }
                    if(!empty($end)) { $q->whereDate('products.created_at', '<=', Carbon::parse($end)->format('Y-m-d')); }
                })
                ->select('products.*', 'stocks.quantity', 'branches.branch_name')
                ->orderByRaw('products.id')->get();
                $dataTable = Datatables::of($data);
                return $dataTable
                    ->addIndexColumn()
                    ->make(true);
        }
        return view($view, $this->data);
    }
}
