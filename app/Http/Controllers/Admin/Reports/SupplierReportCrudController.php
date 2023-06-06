<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Branch;
use App\Helpers\RolePermission;
use App\DataTables\SupplierReportDatatable;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SupplierReportCrudController extends CrudController
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
        RolePermission::checkPermission($this->crud,'suppliers');
        CRUD::setModel(\App\Models\Supplier::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . 'report/supplier');
        CRUD::setEntityNameStrings('report/supplier', 'supplier report');
    }

    protected function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $view = 'reports.supplierReport';
        if (request()->ajax()) {
            $dataTable = new SupplierReportDatatable(request()->startDate, request()->endDate);
            return $dataTable->render($view);
        }
        return view($view, $this->data);
    }
}



