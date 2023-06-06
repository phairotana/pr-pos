<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Stock;
use App\Traits\CrudExtension;
use App\Helpers\RolePermission;
use App\Models\AdjustmentDetail;
use App\Http\Requests\AdjustmentRequest;
use App\Repositories\StockPurchaseRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AdjustmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdjustmentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use CrudExtension;
    protected $stockRepo;
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
        RolePermission::checkPermission($this->crud, 'adjustment');
        CRUD::setModel(\App\Models\Adjustment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/adjustment');
        CRUD::setEntityNameStrings('adjustment', 'adjustments');
        $this->stockRepo = resolve(StockPurchaseRepository::class);
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
            'label'     => 'Product Name',
            'type'      => 'select_multiple',
            'name'      => 'adjustmentDetail',
            'entity'    => 'adjustmentDetail',
            'attribute' => 'product_name',
            'model'     => 'App\Models\AdjustmentDetails',
        ]);
        CRUD::addColumn([
            'name'  => 'date',
            'label' => 'Created At',
            'type'  => 'date',
            'format' => 'DD-MM-Y'
        ]);
        CRUD::addColumn([
            'label'     => 'Created By',
            'type'      => 'select',
            'name'      => 'created_by',
            'entity'    => 'createBy',
            'attribute' => 'name',
            'model'     => "App\Models\User",
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation($update = false)
    {
        CRUD::setValidation(AdjustmentRequest::class);
        $colMd6 = ['class' => 'form-group col-md-6'];


        CRUD::addField([
            'name'  => 'date',
            'type'  => 'date',
            'label' => 'Date',
            'default' => Carbon::now()->format('Y-m-d'),
            'wrapperAttributes' => $colMd6
        ]);

        CRUD::addField([
            'name' => 'reference',
            'label' => 'Reference No',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,

        ]);

        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'product_detail',
            'type' => 'product_detail_adjustment'
        ]);

        CRUD::addField([   // Upload
            'name'      => 'attachments',
            'label'     => 'Attachments',
            'type'      => 'upload_multiple',
            'upload'    => true,
            'disk'      => 'uploads',
        ]);
        CRUD::addField([
            'name'  => 'description',
            'label' => 'Description',
            'type'  => 'tinymce',
        ]);
        CRUD::addField([
            'label'     => 'Gallery (Each photo is limited within 10MB.)',
            'name'      => 'attachments',
            'type'      => 'multiple_photos',
            'upload'    => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-12 bp-image-full-preview'
            ]
        ]);


        CRUD::addField([
            'type' => 'hidden',
            'name' => 'branch_id',
        ]);
        if ($update) {
            CRUD::addField([
                'type' => 'hidden',
                'name' => 'updated_by',
                'default' => backpack_user()->id,
            ]);
        } else {
            CRUD::addField([
                'type' => 'hidden',
                'name' => 'created_by',
                'default' => backpack_user()->id,
            ]);
        }
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
        if (empty(request()->type)) {
            return redirect()->to(backpack_url('adjustment'));
        }
        $store =  $this->traitStore();

        foreach (json_decode(request()->product_detail) ?? [] as  $value) {
            if ($value->type == 'Subtraction') {
                Stock::where('product_id',  $value->id)->decrement('quantity', $value->qty);
            }
            if ($value->type == 'Addition') {
                Stock::where('product_id',  $value->id)->increment('quantity', $value->qty);
            }
            AdjustmentDetail::create([
                'type'  => $value->type,
                'product_id' => $value->id,
                'product_name' => $value->product_name,
                'product_code' => $value->product_code,
                'product_note' => $value->note ?? '',
                'quantity'  => $value->qty,
                'adjustment_id'  => $this->crud->entry->id,
            ]);
        }
        return $store;
    }

    protected function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['adjustmentDetail'] = $entry->adjustmentDetail;
        $this->data['subtractionQty'] = $entry->adjustmentDetail->where('type', 'Subtraction');
        $this->data['additionQty'] = $entry->adjustmentDetail->where('type', 'Addition');
        return view('admin.adjustments.show', $this->data);
    }
}
