<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerGroupRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Helpers\RolePermission;


/**
 * Class CustomerGroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerGroupCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
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
        RolePermission::checkPermission($this->crud,'customer group');
        CRUD::setModel(\App\Models\CustomerGroup::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer-group');
        CRUD::setEntityNameStrings('customer group', 'customer groups');
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
        CRUD::column('name');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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
        CRUD::setValidation(CustomerGroupRequest::class);

      
        CRUD::field('name');
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
}
