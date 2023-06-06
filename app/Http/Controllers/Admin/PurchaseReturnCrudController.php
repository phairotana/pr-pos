<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Shipping;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\CrudExtension;
use App\Models\PurchaseReturn;
use App\Helpers\RolePermission;
use App\Models\PurchaseReturnDetail;
use App\Http\Requests\PurchaseReturnRequest;
use App\Repositories\StockPurchaseRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PurchaseReturnCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PurchaseReturnCrudController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
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
        RolePermission::checkPermission($this->crud, 'purchase return');
        CRUD::setModel(\App\Models\PurchaseReturn::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/purchase-return');
        CRUD::setEntityNameStrings('purchase return', 'purchase returns');

        $this->stockRepo = resolve(StockPurchaseRepository::class);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
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
            'label' => 'Reference',
            'name' => 'ref_id',
            'type' => 'text'
        ]);
        $this->crud->addFilter([
            'label' => 'Return Date',
            'name' => 'purchase_return_date',
            'type' => 'date'
        ], false, function ($val) {
            $this->crud->addClause('whereDate', 'purchase_return_date', $val);
        });


        $this->crud->addFilter([
            'name' => 'supplied_id',
            'type' => 'select2_ajax'
        ], url('admin/crud_extension/ajax-supplier-options'), function ($val) {
            $this->crud->addClause('whereHas', 'supplier', function ($q) use ($val) {
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
            'label' => 'Purchase Date',
            'name' => 'PurchaseDateFormat',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label' => 'Reference',
            'name' => 'ref_id',
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
                return $query->purchaseReturnDetail->sum('qty');
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
            'name' => 'purchase_status',
            'label' => 'Return Status',
            'type' => 'closure',
            'function' => function ($entry) {
                $shtml1 = "<div class='border-outline-status'><span class='success'>Received";
                $shtml2 = "<div class='border-outline-status'><span class='pending'>Partial Received";
                $shtml3 = "<div class='border-outline-status'><span class='order'>Pending";
                $ehtml = "</span></div>";
                if (backpack_user()->can('update purchase return')) :
                    $btn = '<i class="la la-edit la-lg change-status" style="cursor:pointer;" name="purchase return" data-id="' . $entry->id . '" data-value="' . $entry->invoice_status . '" data-reason="' . $entry->status_reason . '"></i>' . $ehtml;
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

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PurchaseReturnRequest::class);
        $tab_purchase = 'Purchase';
        $tab_shipper = 'Ship';
        $col_md_6 = ['class' => 'col-md-6 my-3'];
        $col_md_4 = ['class' => 'col-md-4 my-3'];
        $col_md_12 = ['class' => 'col-md-12 my-3'];

        CRUD::addField([
            'method' => 'POST',
            'name' => 'ref_id',
            'label' => 'Reference',
            'type' => 'select2_from_ajax',
            'attribute' => "ref_id",
            'model' => "App\Models\PurchaseReturn",
            'data_source' => url("admin/purchase-return/fetch/purchase"),
            'placeholder' => 'Select a reference',
            'minimum_input_length' => 0,
            'wrapperAttributes' =>  $col_md_6,
            'tab' => $tab_purchase
        ]);

        $this->crud->addField([
            'name' => 'purchase_return_date',
            'type' => 'date',
            'default' => Carbon::now()->format('Y-m-d'),
            'label' => "Purchase date <span class='text-danger'>*</span>",
            'tab' => $tab_purchase,
            'wrapper'   => $col_md_6,
        ]);
        $this->crud->addField([
            'name' => 'description',
            'type' => "textarea",
            'label' => "Description",
            'wrapper' => $col_md_12,
            'tab' => $tab_purchase,
        ]);
        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'product_detail',
            'type' => 'searching_product',
            'source_route' => url('api/product/fetch'),
            'model' => 'Purchase',
            'tab' => $tab_purchase,
            'belongs_to' => 'Purchase',
            /* ref id must be id of purchase */
            'dependencies' => ['ref_id'],
            'is_enable_alert' => 'false',
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
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'amount_payable',
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'discount_amount',
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'due_amount',
        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'supplier_id',
        ]);
        /* end hidden fields */

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
            'label' => 'Purchase Status <span class="text-danger">*</span>',
            'tab' => $tab_purchase,
            'type' => 'select2_from_array',
            'default' => 'Receive',
            'options' => ['Pending' => "Pending", "Receive" => "Receive", "Partial Receive" => "Partial Receive"],
            'wrapper' => $col_md_4
        ]);

        /* Shipper session */

        $this->crud->addField([
            'name' => 'shipper_name',
            'type' => "text",
            'wrapper' => $col_md_6,
            'tab' => $tab_shipper,
        ]);

        $this->crud->addField([
            'name' => 'shipper_address',
            'type' => "textarea",
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
    }
    public function store()
    {
        $this->mergeFileds();
        request()->merge([
            'seller_id' => backpack_user()->id,
            'supplier_id' => optional(Purchase::find(request()->ref_id))->supplier_id,
            'ref_id' => "IVR-" . time()
        ]);
        $this->stockRepo->removeStock($this->mergeValidateProductDetails() ?? []);

        $store =  $this->traitStore();
        foreach ($this->mergeValidateProductDetails() ?? [] as  $value) {
            PurchaseReturnDetail::create([
                'product_id' => $value->id,
                'product_name' => $value->product_name,
                'product_code' => $value->product_code,
                'product_note' => $value->note ?? '',
                'total_payable' => $value->t_total,
                'discount'     => $value->discount_amount ?? 0,
                'qty'          => $value->qty ?? 1,
                'cost_price'   => $value->cost_price ?? 0,
                'total_amount' => $value->t_total + $value->discount_amount,
                'purchase_return_id'  => $this->crud->entry->id,
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
        $this->data['supplier'] = $entry->supplier;
        $this->data['branch'] = $entry->branch;
        $this->data['product'] = $entry->product;
        $this->data['purchase_detail'] = $entry->purchaseReturnDetail;
        return view('admin.purchase_return.show', $this->data);
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
    protected function fetchPurchase()
    {
        $query = (new Purchase)->newQuery();
        $query->where(function ($q) {
            $q->where('ref_id', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(10);
    }

    public function editStatus(Request $request)
    {
        try {
            $entry = PurchaseReturn::find($request->dataId);
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
