<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RolePermission;
use App\Http\Controllers\TraitUse\AssociateProduct;
use App\Models\Product;
use App\Models\Supplier;
use App\Traits\CrudExtension;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PurchaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PrintBarcodeCrudController extends CrudController
{
    use FetchOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation{
        show as traitShow;
    }
    use AssociateProduct;
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
        RolePermission::checkPermission($this->crud, 'products');
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product/print-barcode');
        CRUD::setEntityNameStrings('product/print-barcode', 'print barcode');
        $this->crud->removeSaveAction('save_action');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    protected function setupCreateOperation()
    {
        $col_md_12 = ['class' => 'col-md-12 my-3'];

        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Branch',
            'name'        => 'branch_id',
            'type'        => 'select2_from_ajax',
            'entity'      => 'branch',
            'attribute'   => "branch_name",
            'data_source' => url('admin/stock/fetch/branch'),
            'placeholder' => 'Select branch',
            'minimum_input_length'  => 0,
            'wrapper'   => $col_md_12,
            'attributes' => [
                'class' => 'branch-element'
            ]
        ]);

        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'print_barcode',
            'type' => 'princt_barcode',
        ]);
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

    public function printBarcode($id)
    {
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        return view('admin.products.print_barcode', $this->data);
    }
}
