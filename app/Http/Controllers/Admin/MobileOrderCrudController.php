<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\RolePermission;
use App\Libraries\Firebases\Firebase;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MobileOrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation {
        bulkDelete as traitBulkDelete;
    }
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
        $this->crud->enableExportButtons();
        CRUD::setModel(\App\Models\MobileOrder::class);
        RolePermission::checkPermission($this->crud,'invoices');
        CRUD::setEntityNameStrings('Mobile Order', 'Mobile Order');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mobile/order');
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
        $this->crud->addFilter([
            'name'  => 'status',
            'type'  => 'select2',
            'label' => 'Status'
        ], function () {
            return [
                'New' => 'New',
                'Approved' => 'Approved',
                'Delivered' => 'Delivered',
                'Completed' => 'Completed'
            ];
        }, function ($value) {
            $this->crud->addClause('where', 'status', $value);
        });
        CRUD::addColumn([
            'label' => 'Customer',
            'name' => 'customer',
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->user)->name.' '.optional($entry->user)->last_name;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Qty',
            'name' => 'qty',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->details->sum('qty');
            }
        ]);
        CRUD::addColumn([
            'label' => 'Total',
            'name' => 'total',
            'type' => 'closure',
            'function' => function ($entry) {
                $detail = optional($entry->details);
                $totalPrice = $detail->map(function($item){
                    return $item['qty']*$item['price'];
                })->toArray();
                return '$'.number_format(array_sum($totalPrice), 2);
            }
        ]);
        CRUD::addColumn([
            'label' => 'Status',
            'name' => 'status',
            'type' => 'closure',
            'function' => function ($entry) {
                if($entry->status == 'New'){
                    return '<span style="font-weight: bold;">'.$entry->status.'</span>';
                }
                if($entry->status == 'Approved'){
                    return '<span style="color:blue;font-weight: bold;">'.$entry->status.'</span>';
                }
                if($entry->status == 'Delivered'){
                    return '<span style="color:darkcyan;font-weight: bold;">'.$entry->status.'</span>';
                }
                if($entry->status == 'Completed'){
                    return '<span style="color:green;font-weight: bold;">'.$entry->status.'</span>';
                }
            }
        ]);
        CRUD::addColumn([
            'label' => 'Date',
            'name' => 'date',
            'type' => 'closure',
            'function' => function ($entry) {
                return Carbon::parse($entry->created_at)->format('d F Y h:i A');
            }
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
        CRUD::addField([
            'label' => 'Satus',
            'name' => 'status',
            'type' => 'select2_from_array',
            'options' => [
                'New' => 'New', 
                'Approved' => 'Approved',
                'Delivered' => 'Delivered',
                'Completed' => 'Completed'
            ]
        ]);
    }
    public function destroy($id)
    {
        $entry = $this->crud->model->find($id);
        return $entry->delete();
    }
    protected function update()
    {
        $rest = $this->traitUpdate();
        $entry = $this->crud->entry;
        Firebase::sendOrder(
            $entry->user,
            str_pad($entry->id, 3, '0', STR_PAD_LEFT),
            $entry->status
        );
        return $rest;
    }
    public function bulkDelete()
    {
        $entries = request()->input('entries');
        $deletedEntries = [];
        foreach ($entries as $id) {
            $entry = $this->crud->model->find($id);
            $deletedEntries[] = $entry->delete();
        }
        return $deletedEntries;
    }
    protected function show($id)
    {
        $entry = $this->crud->getEntry($id);
        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        return view('admin.mobile.show', $this->data);
    }
}
