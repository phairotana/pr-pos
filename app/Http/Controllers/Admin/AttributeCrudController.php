<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RolePermission;
use Carbon\Carbon;
use App\Http\Requests\AttributeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AttributeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AttributeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation{
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation {
        bulkDelete as traitBulkDelete;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

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
        RolePermission::checkPermission($this->crud, 'attributes');
        CRUD::setModel(\App\Models\Attribute::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/attribute');
        CRUD::setEntityNameStrings('attribute', 'attributes');
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
        CRUD::addFilter(
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
        CRUD::addColumn([
            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px; padding-left:0px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();
        CRUD::addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        CRUD::addColumn([
            'label' => 'Name',
            'name' => 'name',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Created By',
            'name' => 'CreatedBys',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Update by',
            'name' => 'UpdatedBys',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'name'  => 'created_at',
            'label' => 'Created At', 
            'type'     => 'closure',
            'function' => function ($entry) {
                return Carbon::parse($entry->created_at)->format('d-m-Y H:i:s A');
            }
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
        CRUD::setValidation(AttributeRequest::class);
        CRUD::addField([
            'label' => 'Name',
            'name' => 'name',
            'type' => 'text',
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
}
