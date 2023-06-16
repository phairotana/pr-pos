<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Traits\CrudExtension;
use Mpdf\Config\FontVariables;
use App\Helpers\RolePermission;
use Mpdf\Config\ConfigVariables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Session;
use App\Repositories\StockPurchaseRepository;
use App\Http\Controllers\TraitUse\AssociateProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

/**
 * Class InvoiceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InvoiceCrudController extends CrudController
{
    use CrudExtension;
    use FetchOperation;
    use AssociateProduct;
    use \App\Traits\BranchTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;



    protected $stockRepo;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        RolePermission::checkPermission($this->crud, 'invoices');
        CRUD::setModel(\App\Models\Invoice::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/invoice');
        CRUD::setEntityNameStrings('invoice', 'invoices');
        $this->stockRepo = resolve(StockPurchaseRepository::class);
        $this->listDataByBranch($this->crud->query);
        $this->crud->enableExportButtons();
        if (request()->only_pass_due) {
            $this->crud->addClause('OnlyPassDue');
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
        $this->crud->addButtonFromModelFunction('line', 'customer_invoice', 'customerInvoice', 'end');
        $this->crud->addButtonFromModelFunction('line', 'deliver_invoice', 'deliverInvoice', 'end');
        $this->filterOptions();
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        $this->crud->addColumn([
            'label' => 'Code',
            'name' => 'Code',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label' => 'Reference',
            'name' => 'ref_id',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label' => 'Sale Date',
            'name' => 'invoice_date',
            'type' => 'date',
            'format' => 'DD-MM-Y'
        ]);
        $this->crud->addColumn([
            'label'     => 'Customer',
            'type'      => 'relationship',
            'name'      => 'customer',
            'attribute' => 'customer_name',
        ]);
        $this->crud->addColumn([
            'label' => 'QTY',
            'name' => 'qty',
            'type' => 'closure',
            'function' => function ($query) {
                return $query->invoiceDetails->sum('qty');
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Discount Type',
            'name' => 'discount_all_type',
            'type' => 'closure',
            'function' => function ($entry) {
                if ($entry->discount_all_type == "per_item") {
                    return 'Per Item';
                }
                if ($entry->discount_all_type == "per_invoice") {
                    $type =  $entry->discount_type == "percent" ? $entry->discount_percent . "%" : Str::numberFormatDollar($entry->discount_fixed_value);
                    return  !empty($entry->discount_type) ?
                        'Per Invoice (' . $type . ')' : 'Per Invoice';
                }
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Total',
            'name' => 'amount',
            'type' => 'closure',
            'function' => function ($query) {
                return Str::numberFormatDollar($query->amount);
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
            'label' => 'Paid',
            'name' => 'received_amount',
            'type' => 'closure',
            'function' => function ($query) {
                return Str::numberFormatDollar($query->received_amount);
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Due',
            'name' => 'due_amount',
            'type' => 'closure',
            'function' => function ($query) {
                return Str::numberFormatDollar(($query->due_amount));
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
        $this->crud->addColumn([
            'label' => 'Credit Date',
            'name' => 'credit_date',
            'type' => 'closure',
            'function' => function ($query) {
                return !empty($query->credit_date) ? Carbon::parse($query->credit_date)->format('d-m-Y') : NULL;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'invoice_status',
            'label' => 'Invoice Status',
            'type' => 'closure',
            'function' => function ($entry) {
                $shtml1 = "<div class='border-outline-status'><span class='success'>Received";
                $shtml2 = "<div class='border-outline-status'><span class='pending'>Partial Received";
                $shtml3 = "<div class='border-outline-status'><span class='order'>Pending";
                $ehtml = "</span></div>";
                if (backpack_user()->can('update invoices')) :
                    $btn = '<i class="la la-edit la-lg change-status" style="cursor:pointer;" name="invoice" data-id="' .
                        $entry->id . '" data-value="' . $entry->invoice_status . '" data-reason="' .
                        $entry->status_reason . '"></i>' . $ehtml;
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
            'label' => 'Payment',
            'name' => 'payment_status',
            'type' => 'closure',
            'function' => function ($entry) {
                $html = '';
                $html .= "<div class='border-outline-status'>";
                if ($entry->payment_status == "Partial") {
                    $html .= "<span class='pending'>Partial</span>";
                } else if ($entry->payment_status == "Pending") {
                    $html .= "<span class='order'>Pending</span>";
                } else if ($entry->payment_status == "Paid") {
                    $html .= "<span class='success'>Paid</span>";
                }
                $html .= "</div>";
                return $html;
            }
        ]);
    }
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
            $this->crud->addClause('whereDate', 'invoice_date', $val);
        });

        $this->crud->addFilter([
            'name' => 'customer_id',
            'type' => 'select2_ajax',
        ], url('admin/crud_extension/ajax-category-options'), function ($value) {
            $this->crud->addClause('whereHas', 'customer', function ($q) use ($value) {
                $q->where('id', $value);
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

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(InvoiceRequest::class);
        $colMd4 = ['class' => 'col-md-4 my-3'];
        $colMd12 = ['class' => 'col-md-12'];
        $getCurrentEditRecord = request()->id ? $this->crud->getEntry(request()->id) : null;

        CRUD::addField([
            'label'                 => 'Customer <span class="text-danger">*</span>',
            'type'                  => "select2_from_ajax",
            'name'                  => 'customer_id',
            'entity'                => 'customer',
            'attribute'             => "customer_name",
            'data_source'           => url("api/customer"),
            'placeholder'           => "Select a customer",
            'minimum_input_length'  => 0,
            'wrapper'   => $colMd4,
            'model'                 => "App\Models\Customer",
        ]);
        CRUD::addField([
            'method'      => 'POST',
            'label'       => 'Branch <span class="text-danger">*</span>',
            'name'        => 'branch',
            'type'        => 'select2_from_ajax',
            'entity'      => 'branch',
            'attribute'   => "branch_name",
            'data_source' => url('admin/stock/fetch/branch'),
            'placeholder' => 'Select branch',
            'minimum_input_length'  => 0,
            'wrapper'   => $colMd4,
            'attributes' => [
                'class' => 'branch-element'
            ]
        ]);
        CRUD::addField([
            'label' => 'Date',
            'name' => 'invoice_date',
            'type' => 'date',
            'default' => Carbon::now()->format('Y-m-d'),
            'wrapper'   => $colMd4,
        ]);
        /* hidden field */
        $this->crud->addField([
            'name' => 'ref_id',
            'type' => 'hidden',
            'value' => (string) 'IV-' . time(),
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

        /* end hidden fields */


        $this->crud->addField([
            'name' => 'noted',
            'type' => "textarea",
            'label' => "Note",
            'wrapper' => [
                'class' => 'col-md-12 my-3'
            ]
        ]);
        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'product_detail',
            'type' => 'searching_product',
            'value' =>  $this->mapWithStockQty($getCurrentEditRecord)
        ]);
        $this->crud->addField([
            'name' => 'discount_all_type',
            'type' => 'select2_from_array',
            'wrapper' => $colMd4,
            'default' => 'per_invoice',
            'options' => ['per_item' => 'Per item', 'per_invoice' => "Per invoice"],
            'attributes' => [
                'class' => 'purchase_discount_all_type',
            ],
        ]);
        $this->crud->addField([
            'name' => 'discount_type',
            'type' => 'select2_from_array',
            'wrapper' => $colMd4,
            'options' => ['fixed_value' => 'Fixed value', 'percent' => "Percent"],
            'attributes' => [
                'class' => 'purchase_discount_type',
            ],
        ]);
        $this->crud->addField([
            'name' => 'discount_fixed_value',
            'label' => 'Discount amount',
            'type' => 'number',
            'prefix' => "$",
            'wrapper' => $colMd4,
            'attributes' => [
                'id' => 'purchase_discount_amount',
            ],
        ]);
        $this->crud->addField([
            'name' => 'discount_percent',
            'type' => 'number',
            'prefix' => "%",
            'wrapper' => $colMd4,
            'attributes' => [
                'id' => 'purchase_discount_percent',
            ],
        ]);

        $this->crud->addField([
            'name' => 'invoice_status',
            'label' => "Invoice Status <span class='text-danger'>*</span>",
            'type' => 'select2_from_array',
            'wrapper' => $colMd4,
            'default' => 'Receive',
            'options' => ["Receive" => "Receive", "Partial Receive" => "Partial Receive"],
        ]);

        $this->crud->addField([
            'name' => 'customer-header',
            'type' => 'custom_html',
            'wrapper' => $colMd12,
            'value' => $this->titleHead('Payment'),
        ]);

        /* start payment_status */
        if (Session::getOldInput() && !empty(Session::getOldInput()['payment_status'])) {
            if (Session::getOldInput()['payment_status'] == 'Paid') {
                $displayReceivedAmount = false;
                $displayCreditDay = false;
            }
            if (Session::getOldInput()['payment_status'] == 'Partial') {
                $displayReceivedAmount = true;
                $displayCreditDay = true;
            }
            if (Session::getOldInput()['payment_status'] == 'Pending') {
                $displayReceivedAmount = false;
                $displayCreditDay = true;
            }
        }

        $this->crud->addField([
            'label' => 'Payment Status <span class="text-danger">*</span>',
            'name' => 'payment_status',
            'type' => 'select2_from_array',
            'wrapper' => $colMd4,
            'default' => 'Paid',
            'attribute' => [
                'id' => 'input-payment-status'
            ],
            'options' => [
                'Paid' => 'Paid',
                'Partial' => "Partial",
                'Pending' => "Pending"
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Received Amount <span class="text-danger">*</span>',
            'name' => 'received_amount',
            'type' => 'number',
            'prefix' => '$',
            'attributes' => [
                'placeholder' => '0.00'
            ],
            'wrapper' => [
                'class' => 'col-md-4 my-3 received-amount',
                'style' => !empty($displayReceivedAmount) ? 'display:block;' : 'display:none;'
            ]
        ]);
        $this->crud->addField([
            'label' => 'Credit Days <span class="text-danger">*</span>',
            'name' => 'credit_day',
            'type' => 'number',
            'attributes' => [
                'placeholder' => 'Ex: 10'
            ],
            'wrapper' => [
                'class' => 'col-md-4 my-3 credit-day',
                'style' => !empty($displayCreditDay) ? 'display:block;' : 'display:none;'
            ]
        ]);
        /* end payment status */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation(true);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function mapWithStockQty($getCurrentEditRecord)
    {
        return optional(optional($getCurrentEditRecord)->invoiceDetails)->map(function ($val) {
            $val['stock_qty'] = optional(Stock::firstWhere('product_code', '=', $val->product_code))->quantity;
            return $val;
        }) ?? [];
    }

    public function store()
    {
        $discountType = null;
        if (request()->discount_all_type == 'per_item') {
            $discount = 0;

            foreach ($this->mergeValidateProductDetails() ?? [] as  $value) {
                if ($value->discount > 0 && $value->dis_type == "percent") {
                    $discount += ($value->qty * $value->sell_price) * ($value->discount / 100);
                } elseif ($value->discount > 0 && $value->dis_type == "fix_val") {
                    $discount += $value->discount;
                }
            }
            request()->merge([
                'discount_amount' => $discount
            ]);
        } else {
            $discountType = request()->discount_type == "percent" ? 'percent' : 'fix_val';
        }
        request()->merge([
            'amount' => (float) request()->amount
        ]);

        $this->crud->addField(['type' => 'hidden', 'name' => 'seller_id']);
        $this->crud->addField(['type' => 'hidden', 'name' => 'credit_date']);
        request()->merge([
            'seller_id' => backpack_user()->id,
            'credit_date' => !empty(request()->credit_day) ? Carbon::today()->addDays(request()->credit_day) : null
        ]);
        $this->mergeDueAmountRequest();
        $this->mergeAmountAndAmountPayable();
        $store =  $this->traitStore();

        $totalCost = 0;
        foreach ($this->mergeValidateProductDetails() ?? [] as  $value) {
            $totalCost += $value->cost_price * $value->qty;
            InvoiceDetail::create([
                'product_id' => $value->id,
                'product_name' => $value->product_name ?? '',
                'product_code' => $value->product_code ?? '',
                'product_note' => $value->note ?? '',
                'total_payable' => $value->t_total ?? 0,
                'discount'     => $value->discount ?? 0,
                'qty'          => $value->qty ?? 1,
                'cost_price'   => $value->cost_price ?? 0,
                'sell_price'   => $value->sell_price ?? 0,
                'total_payable' => $value->t_total ?? 0,
                'total_amount' => $value->t_total + $value->discount_amount,
                'invoice_id'  => $this->crud->entry->id,
                'ref_id' => $this->crud->entry->ref_id,
                'dis_type' => request()->discount_all_type == 'per_item' ? $value->dis_type : $discountType
            ]);
        }
        if (request()->payment_status != 'Pending') {
            Payment::create([
                'received_by' => backpack_user()->id,
                'branch_id' => request()->branch,
                'reference_id' => $this->crud->entry->id,
                'amount' => request()->received_amount ?? 0
            ]);
        }
        $this->crud->entry->update([
            'total_cost' => $totalCost
        ]);
        $this->stockRepo->removeStock($this->mergeValidateProductDetails() ?? []);

        return $store;
    }
    protected function update()
    {
        $update =  $this->traitUpdate();
        DB::beginTransaction();
        try {
            $discountType = null;
            $entry = $this->crud->getCurrentEntry();
            $entry->invoiceDetails()->delete();

            if (request()->discount_all_type == 'per_item') {
                $discount = 0;
                foreach ($this->mergeValidateProductDetails() ?? [] as  $value) {
                    if ($value->discount > 0 && $value->dis_type == "percent") {
                        $discount += ($value->qty * $value->sell_price) * ($value->discount / 100);
                    } elseif ($value->discount > 0 && $value->dis_type == "fix_val") {
                        $discount += $value->discount;
                    }
                }
                request()->merge([
                    'discount_amount' => $discount
                ]);
            } else {
                $discountType = request()->discount_type == "percent" ? 'percent' : 'fix_val';
            }
            request()->merge([
                'amount' => (float) request()->amount
            ]);

            $this->crud->addField(['type' => 'hidden', 'name' => 'seller_id']);
            $this->crud->addField(['type' => 'hidden', 'name' => 'credit_date']);
            request()->merge([
                'seller_id' => backpack_user()->id,
                'credit_date' => !empty(request()->credit_day) ? Carbon::today()->addDays(request()->credit_day) : null
            ]);
            $this->mergeDueAmountRequest();
            $this->mergeAmountAndAmountPayable();

            if ($entry->discount_type == 'fixed_value') {
                $entry->update(['discount_percent' => 0]);
            } else if ($entry->discount_type == 'percent') {
                $entry->update(['discount_fixed_value' => 0]);
            } else {
                $entry->update(['discount_fixed_value' => 0, 'discount_percent' => 0]);
            }

            $totalCost = 0;
            foreach ($this->mergeValidateProductDetails() ?? [] as  $value) {
                $totalCost += $value->cost_price * $value->qty;
                InvoiceDetail::create([
                    'product_id' => $value->id,
                    'product_name' => $value->product_name ?? '',
                    'product_code' => $value->product_code ?? '',
                    'product_note' => $value->note ?? '',
                    'total_payable' => $value->t_total ?? 0,
                    'discount'     => $value->discount ?? 0,
                    'qty'          => $value->qty ?? 1,
                    'cost_price'   => $value->cost_price ?? 0,
                    'sell_price'   => $value->sell_price ?? 0,
                    'total_payable' => $value->t_total ?? 0,
                    'total_amount' => $value->t_total + $value->discount_amount,
                    'invoice_id'  => $this->crud->entry->id,
                    'ref_id' => $this->crud->entry->ref_id,
                    'dis_type' => request()->discount_all_type == 'per_item' ? $value->dis_type : $discountType

                ]);
            }
            if (request()->payment_status != 'Pending') {
                Payment::create([
                    'received_by' => backpack_user()->id,
                    'branch_id' => request()->branch,
                    'reference_id' => $this->crud->entry->id,
                    'amount' => request()->received_amount ?? 0
                ]);
            }
            $this->crud->entry->update([
                'total_cost' => $totalCost
            ]);
            $this->stockRepo->removeStock($this->mergeValidateProductDetails() ?? []);

            DB::commit();
        } catch (\Exception $exp) {
            DB::rollBack();
            return back()->withInput();
        }
        return $update;
    }

    protected function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['details'] = $entry->invoiceDetails;

        return view('admin.invoices.show', $this->data);
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $entry = $this->crud->getEntry($id);

        DB::beginTransaction();
        try {
            $products = $entry->invoiceDetails;
            foreach ($products as $item) {
                $stock = Stock::where('product_id', $item->product_id)->first();
                if (!empty($stock)) {
                    $stock->increment('quantity', $item->qty);
                    $stock->increment('sale_out', $item->qty);
                }
            }
            $entry->invoiceDetails()->delete();
            DB::commit();
            return (string) $entry->delete();
        } catch (\Exception $exp) {
            DB::rollBack();
            return response()->json(['message' => $exp->getMessage()], 500);
        }
    }

    public function printInvoice($id)
    {
        $entry = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $mpdf = new Mpdf([
            'mode' => 'UTF-8',
            'format' => 'A4-P',
            'autoLangToFont' => false,
            'media' => "all",
            'fontDir' => array_merge($fontDirs, [public_path('fonts/')]),
            'fontdata' => $fontData + [
                'kh_moul' => [
                    'R' => 'KhmerOSmuollight.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
                'khmer_siemreap' => [
                    'R' => 'KhmerOSSiemreap.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
                'kh_battambang' => [
                    'R' => 'Battambang-Regular.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
                'sans-serif' => [
                    'R' => 'OpenSans-Regular.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'tempDir' => storage_path('app/mpdf')
        ]);

        if (request()->mode == "customer") {

            $view = view('admin.invoices.invoice_pdf', ['entry' => $entry, 'details' => $entry->invoiceDetails]);

            $html = $view->render();
            $mpdf->showImageErrors = true;

            $mpdf->WriteHTML($html);
            $mpdf->Output('Invoice-' .
                Carbon::today()->format('d-m-Y') . '-' . uniqid() .  '.pdf', 'I');

            return abort(400, 'Bad request (something wrong with URL or parameters)');
        }

        if (request()->mode == "deliver") {
            $view = view('admin.invoices.delivery_pdf', ['entry' => $entry, 'details' => $entry->invoiceDetails]);

            $html = $view->render();
            $mpdf->showImageErrors = true;

            $mpdf->WriteHTML($html);
            $mpdf->Output('Delivery-' .
                Carbon::today()->format('d-m-Y') . '-' . uniqid() .  '.pdf', 'I');

            return abort(400, 'Bad request (something wrong with URL or parameters)');
        }
    }

    public function editStatus(Request $request)
    {
        try {
            $entry = Invoice::find($request->dataId);
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
    public function getInvoice($id)
    {
        $quotation = Invoice::find($id)->invoiceDetails;
        return response()->json($quotation);
    }
}
