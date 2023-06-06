<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Customer;
use App\Helpers\RolePermission;
use App\Http\Requests\CustomerRequest;
use App\Libraries\CustomerLib;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use \App\Traits\BranchTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation {
        bulkDelete as traitBulkDelete;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

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
        RolePermission::checkPermission($this->crud,'customers');
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customers');
        CRUD::setEntityNameStrings('customers', 'customers');
        $this->listDataByBranch($this->crud->query);
        $this->crud->enableExportButtons();
        if (request()->trashed) {
            $this->crud->denyAccess(['update', 'delete', 'show']);
            $this->crud->addButtonFromView('line', 'restore', 'restore', 'beginning');
            $this->crud->addButtonFromView('line', 'forcedelete', 'forcedelete', 'end');
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addFilter(
            [
                'type' => 'simple',
                'name' => 'trashed',
                'label' => 'Trash',
            ],
            false,
            function () { // if the filter is active
                $this->crud->query = $this->crud->query->onlyTrashed();
            }
        );
        $this->crud->addColumn([
            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px; padding-left:0px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        $this->crud->addColumn([
            'name' => 'customer_profile',
            'label' => 'Profile',
            'type' => 'closure',
            'visibleInExport' => false,
            'function' => function ($entry) {
                if ($entry->customer_profile == null) {
                    return '<a class="example-image-link" href="' . asset('uploads/default/user_default.png') . '" data-lightbox="lightbox-' . $entry->id . '">
                    <img class="example-image" src="' . asset('uploads/default/user_default.png') . '" alt="" width="35" style="cursor:pointer"/>
                    </a>';
                }
                return '<a class="example-image-link" href="' . asset($entry->customer_profile) . '" data-lightbox="lightbox-' . $entry->id . '">
                    <img class="example-image" src="' . asset($entry->customer_profile) . '" alt="" width="35" style="cursor:pointer"/>
                    </a>';
            },
        ]);
        CRUD::addColumn([
            'label' => 'Code',
            'name' => 'customer_code',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Name',
            'name' => 'customer_name',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->customer_name.' '.$entry->customer_last_name;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Phone',
            'name' => 'customer_phone',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Email',
            'name' => 'customer_email',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Date Of Birth',
            'name' => 'customer_dob',
            'type' => 'date'
        ]);
        CRUD::addColumn([
            'label' => 'Company',
            'name' => 'company',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Address',
            'name' => 'customer_address',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'name'    => 'branch_id',
            'label'   => 'Branch',
            'type'      => 'select',
            'entity'    => 'branch',
            'attribute' => 'branch_name',
            'model'     => "App\Models\Branch"
        ]);
        CRUD::addColumn([
            'label' => 'Created By',
            'name' => 'CreatedBys',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Updated By',
            'name' => 'UpdatedBys',
            'type' => 'text'
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CustomerRequest::class);
        $colMd6 = ['class' => 'form-group col-md-6'];

        CRUD::addField([
            'label' => 'Customer code',
            'name' => 'customer_code',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
            'attributes' => [
                "readonly" => "readonly"
            ],
            'default' => $this->generateCustomerCode()
        ]);

        CRUD::addField([
            'label' => 'Name',
            'name' => 'customer_name',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Gender',
            'name' => 'customer_gender',
            'type' => 'select2_from_array',
            'options' => ['Male' => 'Male', 'Female' => 'Female'],
            'allows_null' => true,
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Date of Birth',
            'name' => 'customer_dob',
            'type' => 'date',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Phone',
            'name' => 'customer_phone',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,

        ]);
        CRUD::addField([
            'label' => 'Email',
            'name' => 'customer_email',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'name' => 'company',
            'label' => 'Company',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);  
        CRUD::addField([ 
            'name'        => 'branch_id',
            'label'       => "Branch",
            'type'        => 'select2_from_array',
            'options'     => Branch::all()->pluck('branch_name', 'id') ?? [],
            'allows_null' => true,
            'wrapperAttributes' => $colMd6,
        ]);    
        CRUD::addField([
            'name' => 'customer_address',
            'label' => 'Address',
            'type' => 'textarea',
            'wrapperAttributes' => ['class' => 'form-group col-lg-12'],
        ]);  
        CRUD::addField([
            'label' => '',
            'type' => "image",
            'name' => 'customer_profile',
            'upload' => true,
            'crop' => true,
            'aspect_ratio' => 1,
            'wrapperAttributes' => ['class' => 'form-group col-lg-2'],
        ]);
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

    private function generateCustomerCode()
    {
        $customer = (string)((Customer::withTrashed()->max('id') ?? 0) + 1);
        $customer_code = "";
        for ($i = strlen($customer); $i < 4; $i++) {
            $customer_code .= "0";
        }
        $customer_code .= $customer;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        return 'CUS-' . $month . $year . '-' . $customer_code;
    }
    

    public function store()
    {
        $this->crud->addField(['type' => 'hidden', 'name' => 'created_by']);
        request()->merge([
            'created_by' => backpack_user()->id,
        ]);
        return $this->traitStore();
    }

    public function update()
    {
        $this->crud->addField(['type' => 'hidden', 'name' => 'updated_by']);
        request()->merge([
            'updated_by' => backpack_user()->id,
        ]);
        return $this->traitUpdate();
    }
    public function destroy($id)
    {
        $entry = $this->crud->model->withTrashed()->find($id);
        if (request()->force_delete) {
            return $entry->forceDelete();
        }
        return $entry->delete();
    }

    public function bulkDelete()
    {
        $entries = request()->input('entries');
        $deletedEntries = [];
        foreach ($entries as $key => $id) {
            $entry = $this->crud->model->withTrashed()->find($id);
            if (empty($entry->deleted_at)) {
                $entry->delete();
            } else {
                $entry->forceDelete();
                $deletedEntries[] = $entry->delete();
            }
        }
        return $deletedEntries;
    }
    public function restore($id)
    {
        $entry = $this->crud->query->withTrashed()->findOrFail($id);
        return (string) $entry->restore();
    }
    protected function fetchCustomerName()
    {
        $query = (new Customer)->newQuery();
        if (request()->q) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . request()->q . '%');
            });
        }
        return $query->paginate(10);
    }
    protected function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['qoutation_data'] = CustomerLib::customerQuotations($id);
        $this->data['sale_data'] = CustomerLib::customerSale($id);
        $this->data['return_data'] = CustomerLib::customerReturn($id);
        $this->data['sale_payment_data'] = CustomerLib::salePayment($id);
        $this->data['total_data'] = CustomerLib::totalData($id);
    
        return view('admin.customers.show', $this->data);
    }
}
