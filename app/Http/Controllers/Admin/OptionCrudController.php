<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OptionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OptionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
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
        CRUD::setModel(\App\Models\Option::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/option');
        CRUD::setEntityNameStrings('option', 'options');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'type'           => 'checkbox',
            'name'           => 'bulk_actions',
            'label'          => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px; padding-left:0px;" />',
            'priority'       => 1,
            'searchLogic'    => false,
            'orderable'      => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name'  => 'display',
            'label' => 'Name',
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'type',
            'label' => 'Type',
            'type'  => 'text',
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
        CRUD::setValidation(OptionRequest::class);
 
        $this->crud->addField([
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select2_from_array',
            'allows_null' => true,
            'options' => \App\Models\Option::pluck('type', 'type'),
            'default' => request()->set_option_type ? request()->set_option_type : ''
        ]);
 
        $this->crud->addField([
            'name' => 'display',
            'label' => 'Name',
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
}
