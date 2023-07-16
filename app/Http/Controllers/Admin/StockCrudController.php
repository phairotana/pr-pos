<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Supplier;
use App\Http\Requests\StockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        if (backpack_user() && !backpack_user()->hasAnyRole(\Spatie\Permission\Models\Role::all())) {
            \Auth::logout();
            return redirect('admin/login');
        }
        // RolePermission::checkPermission($this->crud, 'stocks');
        CRUD::setModel(\App\Models\Stock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock');
        CRUD::setEntityNameStrings('stock', 'stocks');
        $this->crud->enableExportButtons();
        if (request()->only_out_stock) {
            $this->crud->addClause('onlyOutStock');
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
        $this->filterOptions();
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        CRUD::addColumn([
            'label' => 'Stock code',
            'name' => 'stock_code',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Product code',
            'name' => 'product_code',
            'type'     => 'text',
        ]);
        CRUD::addColumn([
            'label' => 'Product',
            'name' => 'product_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->ProductName;
            }
        ]);
        CRUD::addColumn([
            'label'     => 'Quantity',
            'type'      => 'number',
            'name'      => 'quantity',
        ]);
        CRUD::addColumn([
            'label'     => 'Stock In',
            'type'      => 'number',
            'name'      => 'purchase',
        ]);
        CRUD::addColumn([
            'label'     => 'Stock Out',
            'type'      => 'number',
            'name'      => 'sale_out',
        ]);
        CRUD::addColumn([
            'name'  => 'updated_at',
            'label' => 'Updated At',
            'type'     => 'closure',
            'function' => function ($entry) {
                return Carbon::parse($entry->updated_at)->format('d-m-Y H:i:s A');
            }
        ]);
    }

    protected function filterOptions()
    {
        $this->crud->addFilter(
            [
                'name' =>  'product_code',
                'type' => 'text'
            ],
            false,
            function ($query) {
                $this->crud->addClause('where', 'product_code', 'LIKE', "%{$query}%");
            }
        );
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StockRequest::class);

        CRUD::addField([
            'label' => 'Stock Code',
            'name' => 'stock_code',
            'type' => 'text',
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Product Name',
            'name'        => 'product_id',
            'type'        => 'select2_from_ajax',
            'entity'      => 'product',
            'attribute'   => "product_name",
            'data_source' => url('admin/stock/fetch/product'),
            'placeholder' => '',
            'minimum_input_length'  => 0,
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Supplier',
            'name'        => 'supplier_id',
            'type'        => 'select2_from_ajax',
            'entity'      => 'supplier',
            'attribute'   => "supplier_name",
            'data_source' => url('admin/stock/fetch/supplier'),
            'placeholder' => '',
            'minimum_input_length'  => 0,
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Branch',
            'name'        => 'branch_id',
            'type'        => 'select2_from_ajax',
            'entity'      => 'branch',
            'attribute'   => "branch_name",
            'data_source' => url('admin/stock/fetch/branch'),
            'placeholder' => '',
            'minimum_input_length'  => 0,
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::addField([
            'label' => 'Quantity',
            'name' => 'quantity',
            'type' => 'number',
            'default' => 0,
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::addField([
            'label' => 'Purchase',
            'name' => 'purchase',
            'type' => 'number',
            'default' => 0,
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Storage Location',
            'name'        => 'storage_location',
            'type'        => 'select2_from_ajax',
            'entity'      => 'storage',
            'attribute'   => "storage_name",
            // 'custom_attr_id' => 'loan-product',
            'data_source' => url('admin/stock/fetch/storage'),
            'placeholder' => '',
            'minimum_input_length'  => 0,
            'wrapper'   => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        CRUD::addField([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'tinymce',
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

    protected function fetchProduct()
    {
        $query = (new Product)->newQuery();
        $form = backpack_form_input();
        if (request()->id) {
            return $query->find(request()->id);
        }
        if (!$form['branch_id']) {
            return [];
        }
        if ($form['branch_id']) {
            $query = $query->where('branch_id', $form['branch_id']);
        }
        if (request()->q) {
            $query->where("product_name", 'LIKE', "%" . request()->q . "%");
        }
        return $query->paginate(10);
    }
    protected function fetchSupplier()
    {
        $query = (new Supplier)->newQuery();
        if (request()->id) {
            return $query->find(request()->id);
        }
        if (request()->q) {
            $query->where("supplier_name", 'LIKE', "%" . request()->q . "%");
        }
        return $query->paginate(10);
    }
    protected function fetchStorage()
    {
        $query = (new Storage)->newQuery();
        if (request()->id) {
            return $query->find(request()->id);
        }
        if (request()->q) {
            $query->where("storage_name", 'LIKE', "%" . request()->q . "%");
        }
        return $query->paginate(10);
    }
    protected function fetchBranch()
    {
        $query = (new Branch)->newQuery();
        $query->where(function ($q) {
            $q->where('branch_name', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(5);
    }
}
