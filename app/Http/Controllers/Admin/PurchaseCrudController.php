<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\CrudExtension;
use App\Models\PurchaseDetail;
use App\Helpers\RolePermission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Repositories\StockPurchaseRepository;
use App\Http\Controllers\TraitUse\AssociateProduct;
use App\Models\Purchase;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

/**
 * Class PurchaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PurchaseCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use FetchOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation {
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
        RolePermission::checkPermission($this->crud, 'purchase');
        CRUD::setModel(\App\Models\Purchase::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/purchase');
        CRUD::setEntityNameStrings('purchase', 'purchases');
        $this->crud->enableExportButtons();
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

        $this->filterOptions();
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
            'label' => 'Reference',
            'name' => 'ref_id',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label' => 'Purchase Date',
            'name' => 'PurchaseDateFormat',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label'     => 'Supplier',
            'type'      => 'select',
            'name'      => 'supplier_id',
            'entity'    => 'supplier',
            'attribute' => 'supplier_name',
            'model'     => "App\Models\Supplier",
        ]);
        $this->crud->addColumn([
            'label' => 'QTY',
            'name' => 'qty',
            'type' => 'closure',
            'function' => function ($query) {
                return $query->purchaseDetail->sum('qty');
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Discount Type',
            'name' => 'discount_type',
            'type' => 'closure',
            'function' => function ($entry) {
                if ($entry->discount_all_type == "per_item") {
                    return 'Per Item';
                }
                if ($entry->discount_all_type == "per_invoice") {
                    return 'Per Invoice';
                }
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Total',
            'name' => 'amount',
            'type' => 'closure',
            'function' => function ($entry) {
                return Str::numberFormatDollar($entry->amount);
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Discount',
            'name' => 'discount_amount',
            'type' => 'closure',
            'function' => function ($entry) {
                return Str::numberFormatDollar($entry->discount_amount);
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Grand Total',
            'name' => 'amount_payable',
            'type' => 'closure',
            'function' => function ($entry) {
                return Str::numberFormatDollar($entry->amount_payable);
            }
        ]);
        $this->crud->addColumn([
            'label'     => 'Created at',
            'type'      => 'datetime',
            'name'      => 'created_at',
        ]);
        $this->crud->addColumn([
            'name' => 'purchase_status',
            'label' => 'Purchase Status',
            'type' => 'closure',
            'function' => function ($entry) {
                $shtml1 = "<div class='border-outline-status'><span class='success'>Received";
                $shtml2 = "<div class='border-outline-status'><span class='pending'>Partial Received";
                $shtml3 = "<div class='border-outline-status'><span class='order'>Pending";
                $ehtml = "</span></div>";
                if (backpack_user()->can('update purchase')) :
                    $btn = '<i class="la la-edit la-lg change-status" style="cursor:pointer;" name="purchase" data-id="' . $entry->id . '" data-value="' . $entry->invoice_status . '" data-reason="' . $entry->status_reason . '"></i>' . $ehtml;
                endif;
                switch (strtolower($entry->purchase_status)) {
                    case 'receive':
                        return $btn . $shtml1;
                    case 'partial receive':
                        return $btn . $shtml2;
                    default:
                        return $btn . $shtml3;
                }
            }
        ]);

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function filterOptions()
    {
        $this->crud->addFilter(
            [
                'name' => 'branch_id',
                'type' => 'text',
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereHas', 'branch', function ($query) use ($value) {
                    $query->where('branch_name', 'LIKE', "%{$value}%");
                });
            }
        );
        $this->crud->addFilter([
            'name' => 'ref_id',
            'type' => 'text'
        ]);
        $this->crud->addFilter([
            'name' => 'purchase_date',
            'type' => 'date'
        ], false, function ($val) {
            $this->crud->addClause('whereDate', 'purchase_date', $val);
        });


        $this->crud->addFilter([
            'name' => 'supplied_id',
            'type' => 'select2_ajax'
        ], url('admin/crud_extension/ajax-supplier-options'), function ($val) {
            $this->addClause('whereHas', 'supplier', function ($q) use ($val) {
                $q->where('supplier_name', $val);
            });
        });

        $this->crud->addFilter([
            'name' => 'display',
            'type' => 'select2',
            'label' => 'Payment Status',
        ], [
            'Paid' => 'Paid',
            'Partial' => 'Partial',
            'Pending' => 'Pending',
        ], function ($value) {
            $this->crud->addClause('where', 'payment_status', 'LIKE', "%$value%");
        });
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PurchaseRequest::class);

        /* tabs */
        $tab_purchase = 'Purchase';
        $tab_shipper = 'Ship';
        $col_md_6 = ['class' => 'col-md-6 my-3'];
        $col_md_4 = ['class' => 'col-md-4 my-3'];
        $col_md_12 = ['class' => 'col-md-12 my-3'];

        $this->crud->addField([
            'method'      => 'POST',
            'label'       => 'Supplier <span class="text-danger">*</span>',
            'name'        => 'supplier',
            'type'        => 'select2_from_ajax',
            'entity'      => 'supplier',
            'attribute'   => "supplier_name",
            'data_source' => url('admin/stock/fetch/supplier'),
            'placeholder' => 'Select supplier',
            'tab'         => $tab_purchase,
            'minimum_input_length'  => 0,
            'wrapper'   => $col_md_6,
        ]);
        $this->crud->addField([
            'name' => 'purchase_date',
            'type' => "date_picker",
            'label' => "Purchase date <span class='text-danger'>*</span>",
            'tab' => $tab_purchase,
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true,
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'dd-mm-yyyy'
            ],
            'wrapper'   => $col_md_6,
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => "summernote",
            'label' => "Description",
            'wrapper' => $col_md_12,
            'tab' => $tab_purchase,
        ]);

        /* hidden field */
        $this->crud->addField([
            'name' => 'ref_id',
            'tab' => $tab_purchase,
            'type' => 'hidden',
            'value' => (string) 'PU-' . time(),
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'amount',
            'tab' => $tab_purchase,
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'amount_payable',
            'tab' => $tab_purchase,
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'discount_amount',
            'tab' => $tab_purchase,
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'due_amount',
            'tab' => $tab_purchase,
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'purchase_by',
            'value' => Auth::id(),
        ]);

        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'product_detail',
            'tab' => $tab_purchase,
            'source_route' => url('api/product/fetch?show_all'),
            'is_enable_alert' => 'false',
            'belongs_to' => "Purchase",
            'type' => 'searching_product'
        ]);

        $this->crud->addField([
            'name' => 'discount_all_type',
            'tab' => $tab_purchase,
            'type' => 'select2_from_array',
            'wrapper' => $col_md_4,
            'default' => 'per_invoice',
            'options' => ['per_item' => 'Per item', 'per_invoice' => "Per invoice"],
            'attributes' => [
                'class' => 'purchase_discount_all_type',
            ],
        ]);

        $this->crud->addField([
            'name' => 'discount_type',
            'tab' => $tab_purchase,
            'type' => 'select2_from_array',
            'wrapper' => $col_md_4,
            'options' => ['fixed_value' => 'Fixed value', 'percent' => "Percent"],
            'attributes' => [
                'class' => 'purchase_discount_type',
            ],
        ]);
        $this->crud->addField([
            'name' => 'discount_fixed_value',
            'tab' => $tab_purchase,
            'label' => 'Discount amount',
            'type' => 'number',
            'prefix' => "$",
            'wrapper' => $col_md_4,
            'attributes' => [
                'id' => 'purchase_discount_amount',
            ],
        ]);
        $this->crud->addField([
            'name' => 'discount_percent',
            'tab' => $tab_purchase,
            'type' => 'number',
            'prefix' => "%",
            'wrapper' => $col_md_4,
            'attributes' => [
                'id' => 'purchase_discount_percent',
            ],
        ]);
        $this->crud->addField([
            'name' => 'purchase_status',
            'label' => 'Purchase status <span class="text-danger">*</span>',
            'type' => 'select2_from_array',
            'tab' => $tab_purchase,
            'default' => 'Receive',
            'options' => ['Pending' => "Pending", "Receive" => "Receive", "Partial Receive" => "Partial Receive"],
            'wrapper' => $col_md_4
        ]);

        /* end payment status */

        /* Shipper session */

        $this->crud->addField([
            'name' => 'shipper_name',
            'type' => "text",
            'wrapper' => $col_md_6,
            'tab' => $tab_shipper,
        ]);

        $this->crud->addField([
            'name' => 'shipper_contact',
            'type' => "text",
            'wrapper' => $col_md_6,
            'tab' => $tab_shipper,
        ]);

        $this->crud->addField([
            'name' => 'shipper_via',
            'type' => "text",
            'wrapper' => $col_md_6,
            'tab' => $tab_shipper,
        ]);

        $this->crud->addField([
            'name' => 'ship_amount',
            'type' => "number",
            'prefix' => "$",
            'wrapper' => $col_md_6,
            'tab' => $tab_shipper,
        ]);
        $this->crud->addField([
            'name' => 'shipper_address',
            'type' => "textarea",
            'wrapper' => $col_md_12,
            'tab' => $tab_shipper,
        ]);
    }
    public function store()
    {
        $this->mergeFileds();
        $store = $this->traitStore();

        foreach ($this->mergeValidateProductDetails() ?? [] as  $value) {
            PurchaseDetail::create([
                'product_id' => $value->id,
                'product_name' => $value->product_name,
                'product_code' => $value->product_code,
                'product_note' => $value->note ?? '',
                'total_payable' => $value->t_total,
                'discount'     => $value->discount_amount ?? 0,
                'qty'          => $value->qty ?? 1,
                'cost_price'   => $value->cost_price ?? 0,
                'total_payable' => $value->t_total,
                'total_amount' => $value->t_total + $value->discount_amount,
                'purchase_id'  => $this->crud->entry->id,
                'ref_id' => $this->crud->entry->ref_id,
            ]);
        }

        /* insert to stock */
        $this->stockRepo->insertStock($this->mergeValidateProductDetails() ?? [], $this->crud->entry->id, request()->branch_id);

        $this->addToShippers();

        return $store;
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
        $this->data['supplier'] = $entry->supplier;
        $this->data['branch'] = $entry->branch;
        $this->data['product'] = $entry->product;
        $this->data['purchase_detail'] = $entry->purchaseDetail;
        return view('admin.purchases.show', $this->data);
    }
    public function editStatus(Request $request)
    {
        try {
            $entry = Purchase::find($request->dataId);
            if (!empty($entry)) {
                $entry->update([
                    'purchase_status' => $request->status,
                    'status_reason' => $request->discription
                ]);
            }
            return response()->json([
                'message' => 'Updating status successfully!',
                'messageType' => 'success',
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Updating status falsed!',
                'messageType' => 'error',
            ]);
        }
    }
}
