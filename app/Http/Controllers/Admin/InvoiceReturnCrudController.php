<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceReturn;
use App\Traits\CrudExtension;
use App\Helpers\RolePermission;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceReturnDetail;
use App\Http\Requests\InvoiceReturnRequest;
use App\Repositories\StockPurchaseRepository;
use App\Http\Controllers\TraitUse\AssociateProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

/**
 * Class InvoiceReturnCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InvoiceReturnCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use FetchOperation;
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
        RolePermission::checkPermission($this->crud, 'invocie return');
        CRUD::setModel(\App\Models\InvoiceReturn::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/invoice-return');
        CRUD::setEntityNameStrings('invoice return', 'invoice returns');
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
            'label' => 'Invoice',
            'name' => 'invoice',
            'type' => 'closure',
            'function' => function ($query) {
                return optional($query->invoice)->code;
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Reference',
            'name' => 'ref_id',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label' => 'Return date',
            'name' => 'invoice_return_date',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label'     => 'Customer',
            'type'      => 'relationship',
            'name'      => 'customer',
            'attribute' => 'customer_name',
        ]);
        $this->crud->addColumn([
            'label' => 'Total Qty',
            'name' => 'qty',
            'type' => 'closure',
            'function' => function ($query) {
                return $query->productDetails->sum('quantity');
            }
        ]);
        $this->crud->addColumn([
            'name' => 'invoice_status',
            'label' => 'Return Status',
            'type' => 'closure',
            'function' => function ($entry) {
                $shtml1 = "<div class='border-outline-status'><span class='success'>Received";
                $shtml2 = "<div class='border-outline-status'><span class='pending'>Partial Received";
                $shtml3 = "<div class='border-outline-status'><span class='order'>Pending";
                $ehtml = "</span></div>";
                if (backpack_user()->can('update invocie return')) :
                    $btn = '<i class="la la-edit la-lg change-status" style="cursor:pointer;" name="invoice return" data-id="' . $entry->id . '" data-value="' . $entry->invoice_status . '" data-reason="' . $entry->status_reason . '"></i>' . $ehtml;
                endif;
                switch (strtolower($entry->invoice_status)) {
                    case 'receive':
                        return $btn . $shtml1;
                    case 'partial receive':
                        return $btn . $shtml2;
                    default:
                        return $btn . $shtml3;
                }
            }
        ]);
        $this->crud->addColumn([
            'label'     => 'Branch',
            'type'      => 'select',
            'name'      => 'branch_id',
            'entity'    => 'branch',
            'attribute' => 'branch_name',
            'model'     => "App\Models\Branch",
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
            'name' => 'invoice_date',
            'type' => 'date'
        ], false, function ($val) {
            $this->crud->addClause('whereDate', 'invoice_return_date', $val);
        });

        $this->crud->addFilter([
            'name' => 'customer_id',
            'type' => 'select2_ajax',
        ], url('admin/crud_extension/ajax-category-options'), function ($value) {
            $this->crud->addClause('whereHas', 'customer', function ($q) use ($value) {
                $q->where('id', $value);
            });
        });
    }
    protected function setupCreateOperation()
    {
        CRUD::setValidation(InvoiceReturnRequest::class);

        $col_md_6 = ['class' => 'form-group col-md-6 my-3'];
        $col_md_4 = ['class' => 'form-group col-md-4 my-3'];

        CRUD::addField([
            'method' => 'POST',
            'name' => 'ref_id',
            'label' => 'Reference',
            'type' => 'select2_from_ajax',
            'attribute' => "ref_id",
            'model' => "App\Models\Invoice",
            'data_source' => url("admin/invoice-return/fetch/invoice"),
            'placeholder' => 'Select a reference',
            'minimum_input_length' => 1,
            'wrapperAttributes' =>  $col_md_6
        ]);

        CRUD::addField([
            'label' => 'Date',
            'name' => 'invoice_return_date',
            'type' => 'date',
            'default' => Carbon::now()->format('Y-m-d'),
            'wrapper' => $col_md_6,

        ]);
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'customer_id',
        ]);
        /* end hidden fields */

        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'product_detail',
            'type' => 'invoice_return_searching_product',
            'source_route' => url('api/product/fetch'),
            'model' => 'Invoice',
            'dependencies' => ['ref_id'],
            'is_enable_alert' => 'false',
        ]);
        $this->crud->addField([
            'name' => 'noted',
            'label' => "Noted",
            'type' => 'textarea',
            'wrapper' => ['class' => 'form-group col-md-12'],
            'attributes' => [
                'rows' => 5
            ]
        ]);
        $this->crud->addField([
            'name' => 'invoice_status',
            'label' => "Return Status",
            'type' => 'select2_from_array',
            'wrapper' => $col_md_4,
            'default' => 'Receive',
            'options' => ["Pending" => "Pending", "Receive" => "Receive", "Partial Receive" => "Partial Receive"],
        ]);
    }
    public function store()
    {
        $invoiceId = request()->ref_id;
        $this->crud->addField(['type' => 'hidden', 'name' => 'seller_id']);
        $this->crud->addField(['type' => 'hidden', 'name' => 'branch_id']);
        $this->crud->addField(['type' => 'hidden', 'name' => 'invoice_id']);
        $invoice = Invoice::find($invoiceId);
        request()->merge([
            'invoice_id' => $invoiceId,
            'seller_id' => backpack_user()->id,
            'branch_id' => $invoice->branch_id ?? '',
            'customer_id' => $invoice->customer_id ?? '',
            'ref_id' => "PUR-" . time()
        ]);
        
        $store = $this->traitStore();

        $qtys = request()->qty;
        $productIds = request()->product_id;
        $productCodes = request()->product_code;
        if(!empty($productIds)){
            $totalReturnCost = 0;
            $totalReturnAmount = 0;
            foreach($productIds as $key => $returnDetail){
                if(!empty($qtys[$key]) && $qtys[$key] > 0){
                    InvoiceReturnDetail::create([
                        'product_id' => $returnDetail ?? '',
                        'product_code' => $productCodes[$key] ?? '',
                        'quantity' => $qtys[$key] ?? 0,
                        'invoice_return_id'  => $this->crud->entry->id,
                    ]);
                    Stock::where('product_id', $returnDetail)->increment('quantity', $qtys[$key]); 
                    $in = InvoiceDetail::where('invoice_id',$invoiceId)->where('product_id',$returnDetail)->first();
                    $in->decrement('qty', $qtys[$key]);
                    $in->decrement('total_payable', ($in->sell_price*$qtys[$key]));
                    $in->decrement('total_amount', ($in->sell_price*$qtys[$key]));
                    $totalReturnCost += $in->cost_price*$qtys[$key];
                    $totalReturnAmount += $in->sell_price*$qtys[$key];
                }
            }
            $invoice->decrement('amount', $totalReturnAmount);
            $invoice->decrement('total_cost', $totalReturnCost);
            $invoice->decrement('amount_payable', $totalReturnAmount);
            $refreshInvoice = $invoice->refresh();
            $invoice->update([
                'due_amount' => ($refreshInvoice->amount_payable - $refreshInvoice->received_amount)
            ]);
        }

        return $store;
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
        $this->data['details'] = $entry->productDetails;

        return view('admin.invoices_return.show', $this->data);
    }

    protected function printInvoice($id)
    {
        $entry = $this->crud->getEntry($id);
        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['details'] = $entry->productDetails;

        return view('admin.invoices_return.invoice', $this->data);
    }

    protected function fetchInvoice()
    {
        $query = (new Invoice())->newQuery();
        $query->where(function ($q) {
            $q->where('ref_id', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(10);
    }

    public function editStatus(Request $request)
    {
        try {
            $entry = InvoiceReturn::find($request->dataId);
            if (!empty($entry)) {
                $entry->update([
                    'invoice_status' => $request->status,
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
