<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Option;
use App\Models\Product;
use App\Models\Storage;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\ProductUnit;
use App\Helpers\RolePermission;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Admin\Imports\ProductsImport;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        RolePermission::checkPermission($this->crud, 'products');
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
        $this->crud->addButtonFromView('top', 'importData', 'import_product', 'end');
        $this->crud->enableExportButtons();
        if (request()->trashed) {
            $this->crud->denyAccess(['update', 'delete', 'show']);
            $this->crud->addButtonFromView('line', 'restore', 'restore', 'beginning');
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
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        $this->crud->addColumn([
            'name' => 'thumnail',
            'label' => 'Thumnail',
            'type' => 'closure',
            'visibleInExport' => false,
            'function' => function ($entry) {
                if (!empty($entry->thumnail)) {
                    return '<a class="example-image-link" href="' . $entry->ProductThumnail . '" data-lightbox="lightbox-' . $entry->id . '">
                    <img class="example-image" src="' . $entry->ProductThumnail . '" alt="" width="35" style="cursor:pointer"/>
                    </a>';
                } else {
                    return '<a class="example-image-link" href="' . asset(config('const.filePath.default_image')) . '" data-lightbox="lightbox-' . $entry->id . '">
                    <img class="example-image" src="' . asset(config('const.filePath.default_image')) . '" alt="" width="35" style="cursor:pointer"/>
                    </a>';
                }
            },
        ]);
        CRUD::addColumn([
            'label' => 'Code',
            'name' => 'product_code',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Name',
            'name' => 'product_name',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label'     => 'Attributes',
            'type'      => 'select_multiple',
            'name'      => 'attributes',
            'entity'    => 'attributes',
            'attribute' => 'name',
            'model'     => 'App\Models\Attribute',
        ],);
        CRUD::addColumn([
            'label' => 'Brand',
            'name' => 'rBrand',
            'type' => 'select'
        ]);
        CRUD::addColumn([
            'name'     => 'unit_id',
            'label'    => 'Unit',
            'type'     => 'closure',
            'function' => function ($entry) {
                return optional($entry->productUnit)->name;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Cost Price',
            'name' => 'cost_price',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->CostPriceFormat;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Sell Price',
            'name' => 'sell_price',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->SellPriceFormat;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Category',
            'name' => 'category_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->CategoryName;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Pre Order',
            'name' => 'pre_order',
            'type' => 'text'
        ]);
        CRUD::addColumn([
            'label' => 'Location',
            'name' => 'location_id',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->LocationName;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Hot',
            'name'  => 'hot',
            'type'  => 'closure',
            'function' => function ($entry) {
                if($entry->hot){
                    return "<span style='color: blue!important;'>Yes</span>";
                }
                return "<span>No</span>";
            }
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
        CRUD::setValidation(ProductRequest::class);
        $colMd6 = ['class' => 'form-group col-md-6'];
        $branchURL = url('admin/product/fetch/branch');
        $categoryURL = url('admin/product/fetch/category');
        $unitURL = url('admin/product/fetch/unit');
        $attributesURL = url('admin/product/fetch/attribute');
        CRUD::addField([
            'label' => 'Product Code',
            'name' => 'product_code',
            'type' => 'text',
            'suffix' => '<i class="la la-random" style="cursor: pointer;" onclick="randomCode(this)" data-button-type="random-code"></i>',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Product Name',
            'name' => 'product_name',
            'type' => 'text',
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => "Brand Name",
            'type' => "relationship",
            'name' => 'rBrand',
            'attribute' => 'name',
            'placeholder' => "Select a Brand",
            'ajax' => true,
            'data_source' => url('admin/brand/fetch/brand'),
            'minimum_input_length' => 0,
            'include_all_form_fields' => true,
            'wrapperAttributes' => $colMd6,
            'inline_create' => [
                'entity' => 'rBrand',
                'modal_class' => 'modal-dialog modal-xl',
                'modal_route' => route('brand-inline-create'),
                'create_route' =>  route('brand-inline-create-save')
            ]
        ]);
        CRUD::addField([
            'label' => "Category",
            'type' => "relationship",
            'name' => 'category',
            'attribute' => 'category_name',
            'placeholder' => "Select a category",
            'ajax' => true,
            'inline_create' => true,
            'data_source' => $categoryURL,
            'minimum_input_length' => 0,
            'include_all_form_fields' => true,
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => "Attributes",
            'type' => "relationship",
            'name' => 'attributes',
            'attribute' => 'name',
            'placeholder' => "Select attributes",
            'ajax' => true,
            'inline_create' => ['entity' => 'attribute'],
            'data_source' => $attributesURL,
            'minimum_input_length' => 0,
            'include_all_form_fields' => true,
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => "Product Unit",
            'type' => "relationship",
            'name' => 'productUnit',
            'attribute' => 'name',
            'placeholder' => "Select a Unit",
            'ajax' => true,
            'inline_create' => true,
            'data_source' => $unitURL,
            'minimum_input_length' => 0,
            'include_all_form_fields' => true,
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'name'        => 'pre_order',
            'label'       => "Allow Preorder",
            'type'        => 'select2_from_array',
            'options'     => ['No' => 'No', 'Yes' => 'Yes'],
            'allows_null' => false,
            'wrapperAttributes' => $colMd6
        ]);
        CRUD::addField([
            'label' => 'Location',
            'type' => "relationship",
            'name' => 'storage',
            'attribute' => 'storage_name',
            'placeholder' => "Select a Storage",
            'ajax' => true,
            'inline_create' => true,
            'data_source' => url('admin/stock/fetch/storage'),
            'minimum_input_length' => 0,
            'include_all_form_fields' => true,
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Cost Price',
            'name' => 'cost_price',
            'type' => 'number',
            'default' => 0,
            'suffix' => '$',
            'wrapperAttributes' => $colMd6
        ]);
        CRUD::addField([
            'label' => 'Sell Price',
            'name' => 'sell_price',
            'default' => 0,
            'type' => 'number',
            'suffix' => '$',
            'wrapperAttributes' => $colMd6
        ]);
        CRUD::addField([
            'label' => 'Stock Alert',
            'type' => "number",
            'name' => 'stock_alert',
            'default' => 0,
            'wrapperAttributes' => $colMd6,
        ]);
        CRUD::addField([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea',
            'wrapperAttributes' => $colMd6,
            'attributes' => [
                'rows' => 1,
            ]
        ]);
        CRUD::addField([
            'name'  => 'hot',
            'label' => 'Hot Product',
            'type'  => 'checkbox'
        ]);
        CRUD::addField([
            'label' => 'Thumbnail',
            'type' => "image",
            'name' => 'thumnail',
            'upload' => true,
            'crop' => true,
            'aspect_ratio' => 0,
            'prefix'    => asset(config('const.filePath.medium')).'/',
            'wrapperAttributes' => ['class' => 'form-group col-lg-3'],
        ]);
        CRUD::addField([
            'label'     => 'Gallery (Each photo is limited within 10MB.)',
            'name'      => 'images',
            'type'      => 'multiple_photos',
            'upload'    => true,
            // 'prefix'    => asset(config('const.filePath.large')),
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-12 bp-image-full-preview'
            ]
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
    protected function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['branch'] = $entry->branch;
        return view('admin.products.show', $this->data);
    }
    public function store()
    {
        $rest = $this->traitStore();
        $this->crud->entry->update([
            'created_by' => backpack_user()->id
        ]);
        return $rest;
    }
    public function update()
    {
        $rest = $this->traitUpdate();
        $this->crud->entry->update([
            'updated_by' => backpack_user()->id
        ]);
        return $rest;
    }
    public function destroy($id)
    {
        $entry = $this->crud->model->withTrashed()->find($id);
        if (request()->force_delete) {
            return $entry->forceDelete();
        }
        return $entry->delete();
    }
    public function restore($id)
    {
        $entry = $this->crud->query->withTrashed()->findOrFail($id);
        return (string)$entry->restore();
    }
    protected function fetchBranch()
    {
        $query = (new Branch)->newQuery();
        $query->where(function ($q) {
            $q->where('branch_name', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(5);
    }
    protected function fetchCategory()
    {
        $query = (new Category)->newQuery();
        $query->where(function ($q) {
            $q->where('category_name', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(5);
    }
    protected function fetchUnit()
    {
        $query = (new ProductUnit())->newQuery();
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(5);
    }
    protected function fetchOption()
    {
        $query = (new Option)->where('type', 'product_unit')->newQuery();
        $query->where(function ($q) {
            $q->where('display', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(5);
    }
    protected function fetchAttribute()
    {
        $query = (new Attribute)->newQuery();
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(5);
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
    protected function fetchProduct()
    {
        $query = (new Product)->newQuery();
        $query->select('id as id', 'product_name as name');
        $products = $query->paginate(10);

        return response()->json($products);

    }
    public function product()
    {
        $query = (new Product)->newQuery();
        $query->where('id', request()->code);
        $query->orWhere('product_code', request()->code);
        return response()->json($query->get());

    }
    public function barcode()
    {
        return 'Under Develop';
    }
    function importData(Request $request)
    {
        try {
            if (request()->file) {
                DB::beginTransaction();
                ini_set('memory_limit', '-1');

                Excel::import(new ProductsImport, request()->file);

                DB::commit();
                return response()->json([
                    'messageType' => 'success',
                    'message' => 'The record created successfully.',
                ]);
            }
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json([
                'messageType' => 'error',
                'message' => ' JSON file is invalid. Make sure your file extension is json.',
            ]);
        }
    }
}
