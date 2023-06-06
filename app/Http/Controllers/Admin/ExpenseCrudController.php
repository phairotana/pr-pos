<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\RolePermission;
use App\Http\Requests\ExpenseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExpenseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExpenseCrudController extends CrudController
{
    use \App\Traits\BranchTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

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
        RolePermission::checkPermission($this->crud, 'expenses');
        CRUD::setModel(\App\Models\Expense::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/expense');
        CRUD::setEntityNameStrings('expense', 'expenses');
        $this->listDataByBranch($this->crud->query);
        $this->crud->enableExportButtons();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->filterOptions();

        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#'
        ]);

        $this->crud->addColumn([
            'name' => 'expense_date',
            'type' => 'date',
        ]);

        $this->crud->addColumn([
            'name' => 'branch_id',
            'type' => 'closure',
            'function' => function ($query) {
                return $query->branchName;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'amount',
            'type' => 'closure',
            'function' => function ($query) {
                return Str::numberFormatDollar($query->amount);
            }
        ]);
        $this->crud->addColumn([
            'name' => 'category',
            'type' => 'text',
        ]);
        $this->crud->addColumn([
            'name' => 'details',
            'type' => 'text',

        ]);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ExpenseRequest::class);

        $col_md_6 = ['class' => 'col-md-6 my-3'];
        $col_md_12 = ['class' => 'col-md-12 my-3'];
        $this->crud->addField([
            'name' => 'expense_date',
            'type' => "date_picker",
            'default' => Carbon::now()->format('Y-m-d'),
            'label' => 'Date <span class="text-danger">*</span>',
            'wrapper' => $col_md_6,
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true,
            ],
        ]);
        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Branch <span class="text-danger">*</span>',
            'name'        => 'branch_id',
            'type'        => 'select2_from_ajax',
            'entity'      => 'branch',
            'attribute'   => "branch_name",
            'data_source' => url('admin/stock/fetch/branch'),
            'placeholder' => '',
            'minimum_input_length'  => 0,
            'wrapper' => $col_md_6,
        ]);
        $this->crud->addField([
            'label' => 'Category <span class="text-danger">*</span>',
            'name' => 'category',
            'type' => 'select2_from_array',
            'wrapper' => $col_md_6,
            'options' => [],
            'options' => [
                'Meals & Entertainment' => 'Meals & Entertainment',
                'Employee Benefits' => 'Employee Benefits',
                'Office Expenses & Postage' => 'Office Expenses & Postage',
                'Petrol' => 'Petrol',
                'Other Expense' => 'Other Expense',
            ]
        ]);
        $this->crud->addField([
            'label' => 'Amount <span class="text-danger">*</span>',
            'name' => 'amount',
            'type' => 'number',
            'prefix' => '$',
            'wrapper' => $col_md_6,
        ]);
        $this->crud->addField([
            'name' => 'details',
            'type' => 'textarea',
            'wrapper' => $col_md_12,
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }
    public function filterOptions()
    {
        $this->crud->addFilter(
            [
                'name' =>  'branch_id',
                'type' => 'text'
            ],
            false,
            function ($query) {
                $this->crud->addClause('whereHas', 'branch', function ($relations) use ($query) {
                    $relations->where('branch_name', 'LIKE', "%{$query}%");
                });
            }
        );

        $this->crud->addFilter(
            [
                'name'       => 'amount',
                'type'       => 'range',
                'label'      => 'Amount',
                'label_from' => 'min value',
                'label_to'   => 'max value'
            ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'amount', '>=', (float) $range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'amount', '<=', (float) $range->to);
                }
            }
        );

        // date filter
        $this->crud->addFilter(
            [
                'type'  => 'date',
                'name'  => 'expense_date',
                'label' => 'Date'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $this->crud->addClause('where', 'expense_date', $value);
            }
        );
    }


    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
