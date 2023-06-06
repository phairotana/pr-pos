<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Branch;
use App\Models\Expense;
use App\Helpers\RolePermission;
use App\DataTables\ExpensReportDatatable;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExpenseReportCrudController extends CrudController
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
        RolePermission::checkPermission($this->crud,'expenses');
        CRUD::setModel(Expense::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . 'report/expenses');
        CRUD::setEntityNameStrings('report/expenses', 'expenses report');
    }

    protected function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $this->data['branch_options'] = Branch::orderBy('branch_name')->pluck('branch_name', 'id');
        $view = 'reports.expensesReport';
        if (request()->ajax()) {
            $dataTable = new ExpensReportDatatable(request()->startDate, request()->endDate, request()->branch);
            return $dataTable->render($view);
        }
        return view($view, $this->data);
    }
}



