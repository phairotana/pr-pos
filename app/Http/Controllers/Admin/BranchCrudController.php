<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RolePermission;
use Carbon\Carbon;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BranchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BranchCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
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
        RolePermission::checkPermission($this->crud, 'branches');
        CRUD::setModel(\App\Models\Branch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/branch');
        CRUD::setEntityNameStrings('branch', 'branches');
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
        CRUD::addColumn([
            'name'     => 'branch_name',
            'label'    => 'Name',
            'type'     => 'closure',
            'function' => function ($entry) {
                if ($entry->main == 1) {
                    return '(M) ' . $entry->branch_name;
                }

                return $entry->branch_name;
            }

        ]);
        CRUD::addColumn([
            'name' => 'branch_phone',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'name' => 'branch_email',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Address',
            'name' => 'address',
            'type' => 'text'
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
        CRUD::setValidation(BranchRequest::class);

        CRUD::addField([
            'label' => 'Name',
            'name' => 'branch_name',
            'type' => 'text'
        ]);
        CRUD::addField([
            'name' => 'branch_phone',
            'type' => 'text'
        ]);
        CRUD::addField([
            'name' => 'branch_email',
            'type' => 'text'
        ]);
        CRUD::addField([
            'label' => 'Address',
            'name' => 'address',
            'type' => 'textarea'
        ]);
        CRUD::addField([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'tinymce'
        ]);
        CRUD::addField([
            'label' => "Logo",
            'name' => "profile_image",
            'type' => 'image',
            'crop' => false, // set to true to allow cropping, false to disable
            'aspect_ratio' => 0,
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
    protected function fetchBranch()
    {
        $query = (new Branch)->newQuery();
        if (request()->q) {
            $query->where(function ($q) {
                $q->where('branch_name', 'like', '%' . request()->q . '%');
            });
        }
        return $query->paginate(10);
    }
}
