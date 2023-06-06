<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Helpers\RolePermission;
use App\Http\Requests\SupplierRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SupplierCrudController extends CrudController
{
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
        RolePermission::checkPermission($this->crud, 'suppliers');
        CRUD::setModel(\App\Models\Supplier::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/supplier');
        CRUD::setEntityNameStrings('supplier', 'suppliers');
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
            'name' => 'supplier_profile',
            'label' => 'Profile',
            'type' => 'closure',
            'visibleInExport' => false,
            'function' => function ($entry) {
                if ($entry->supplier_profile == null) {
                    return '<a class="example-image-link" href="' . asset('uploads/default/user_default.png') . '" data-lightbox="lightbox-' . $entry->id . '">
                    <img class="example-image" src="' . asset('uploads/default/user_default.png') . '" alt="" width="35" style="cursor:pointer"/>
                    </a>';
                }
                return '<a class="example-image-link" href="' . asset($entry->supplier_profile) . '" data-lightbox="lightbox-' . $entry->id . '">
                    <img class="example-image" src="' . asset($entry->supplier_profile) . '" alt="" width="35" style="cursor:pointer"/>
                    </a>';
            },
        ]);
        CRUD::addColumn([
            'label' => 'Code',
            'name' => 'supplier_code',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Name',
            'name' => 'supplier_name',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Contact Name',
            'name' => 'contact_name',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Contact Number',
            'name' => 'supplier_phone',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Email',
            'name' => 'supplier_email',
            'type' => 'email'
        ]);
        $this->crud->addColumn([
            'label' => 'Created by',
            'name' => 'CreatedBys',
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
        CRUD::setValidation(SupplierRequest::class);

        $colMd6 = ['class' => 'form-group col-md-6'];
        $colMd12 = ['class' => 'form-group col-md-12'];

        CRUD::addField([
            'label' => 'Suppler code',
            'name' => 'supplier_code',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
            'attributes' => [
                "readonly" => "readonly"
            ],
            'default' => $this->generateSupplierCode()
        ]);

        CRUD::addField([
            'label' => 'Name',
            'name' => 'supplier_name',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Contact Name',
            'name' => 'contact_name',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Contact Number',
            'name' => 'supplier_phone',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        $this->crud->addField([
            'name' => 'supplier_email',
            'label' => 'Email',
            'type' => 'email',
            'wrapperAttributes' => $colMd6
        ]);
        CRUD::addField([
            'label' => 'Address',
            'name' => 'address',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => '',
            'type' => "image",
            'name' => 'supplier_profile',
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

    private function generateSupplierCode()
    {
        $supplier = (string)((Supplier::max('id' ?? 0)) + 1);
        $supplier_code = "";
        for ($i = strlen($supplier); $i < 4; $i++) {
            $supplier_code .= "0";
        }
        $supplier_code .= $supplier;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        return 'SUP-' . $month . $year . '-' . $supplier_code;
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
    public function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['purchase'] = $entry->purchass;
        // $this->data['purchaseDetail'] = $this->crud;
       
        return view('admin.suppliers.show', $this->data);
    }
}
