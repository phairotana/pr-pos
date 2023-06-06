<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Branch;
use App\Models\InvoiceReturn;
use App\Helpers\RolePermission;
use App\DataTables\InvoiceReturnReportDatatable;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InvoiceReturnReportCrudController extends CrudController
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
        RolePermission::checkPermission($this->crud,'invoices');
        CRUD::setModel(InvoiceReturn::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . 'report/invoice/return');
        CRUD::setEntityNameStrings('report/invoice/return', 'invoice return report');
    }

    protected function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $this->data['branch_options'] = Branch::orderBy('branch_name')->pluck('branch_name', 'id');
        $view = 'reports.invoiceReturnReport';
        if (request()->ajax()) {
            $dataTable = new InvoiceReturnReportDatatable(request()->startDate, request()->endDate, request()->branch);
            return $dataTable->render($view);
        }
        return view($view, $this->data);
    }
}



