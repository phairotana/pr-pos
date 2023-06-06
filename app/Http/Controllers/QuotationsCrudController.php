<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Quotations;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Traits\CrudExtension;
use App\Helpers\RolePermission;
use App\Models\QuotationDetail;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;
use App\Http\Requests\QuotationsRequest;
use App\Repositories\StockPurchaseRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QuotationsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuotationsCrudController extends CrudController
{
    use \App\Traits\BranchTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use CrudExtension;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        RolePermission::checkPermission($this->crud, 'quotation');
        CRUD::setModel(\App\Models\Quotations::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/quotations');
        CRUD::setEntityNameStrings('quotations', 'quotations');
        $this->listDataByBranch($this->crud->query);
        $this->crud->enableExportButtons();
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
            'label' => 'Quotation date',
            'name' => 'quotation_date',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
            'label' => 'Reference',
            'name' => 'ref_id',
            'type' => 'text'
        ]);


        $this->crud->addColumn([
            'label'     => 'Customer',
            'type'      => 'relationship',
            'name'      => 'customer',
            'attribute' => 'customer_name',
        ]);
        $this->crud->addColumn([
            'label' => 'Grand Total',
            'name' => 'amount',
            'type' => 'closure',
            'function' => function ($query) {
                return Str::numberFormatDollar($query->amount);
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
            'name' => 'status',
            'label' => 'Status',
            'type' => 'closure',
            'function' => function ($entry) {
                $shtml1 = "<div class='border-outline-status'><span class='success'>Sent";
                $shtml2 = "<div class='border-outline-status'><span class='order'>Pending";
                $ehtml = "</span></div>";
                if (backpack_user()->can('show quotation')) :
                    $btn = '<i class="la la-edit la-lg change-qoutation-status" style="cursor:pointer;" name="qoutation status" data-id="' . $entry->id . '" data-value="' . $entry->status . '" data-reason="' . $entry->status_reason . '"></i>' . $ehtml;
                endif;
                switch (strtolower($entry->status)) {
                    case 'sent':
                        return $btn . $shtml1;
                    case 'pending':
                        return $btn . $shtml2;
                    default:
                        return '-';
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
    protected function setupCreateOperation($update = false)
    {
        CRUD::setValidation(QuotationsRequest::class);

        $tab_purchase = 'Invoice';
        $tab_shipper = 'Ship';
        $col_md_6 = ['class' => 'col-md-6 my-3'];
        $col_md_4 = ['class' => 'col-md-4 my-3'];
        $col_md_12 = ['class' => 'col-md-12 my-3'];

        // Creating Field
        CRUD::addField([
            'label'                 => "Customer <span class='text-danger'>*</span>",
            'type'                  => "select2_from_ajax",
            'name'                  => 'customer_id',
            'entity'                => 'customer',
            'attribute'             => "customer_name",
            'data_source'           => url("api/customer"),
            'placeholder'           => "Select a customer",
            'minimum_input_length'  => 1,
            'wrapper'   => $col_md_4,
            'model'                 => "App\Models\Customer",
        ]);
        CRUD::addField([
            'label'                 => "Branch <span class='text-danger'>*</span>",
            'type'                  => "select2_from_ajax",
            'name'                  => 'branch_id',
            'entity'                => 'branch',
            'attribute'             => "branch_name",
            'data_source'           => url("api/branch"),
            'placeholder'           => "Select a customer",
            'minimum_input_length'  => 0,
            'wrapper'   => $col_md_4,
            'model'                 => "App\Models\Branch",
        ]);
        CRUD::addField([
            'label' => "Date <span class='text-danger'>*</span>",
            'name' => 'quotation_date',
            'type' => 'date',
            'default' => Carbon::now()->format('Y-m-d'),
            'wrapper'   => $col_md_4,

        ]);

        /* hidden field */
        $this->crud->addField([
            'name' => 'ref_id',
            'type' => 'hidden',
            'value' => $this->generateQuotationCode(),
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

        CRUD::addField([
            'label' => 'Search Product',
            'name' => 'product_detail',
            'is_enable_alert' => false,
            'type' => 'searching_product',
        ]);

        $this->crud->addField([
            'name' => 'discount_all_type',
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
            'type' => 'select2_from_array',
            'wrapper' => $col_md_4,
            'options' => ['fixed_value' => 'Fixed value', 'percent' => "Percent"],
            'attributes' => [
                'class' => 'purchase_discount_type',
            ],
        ]);
        $this->crud->addField([
            'name' => 'discount_amount_faker',
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
            'type' => 'number',
            'prefix' => "%",
            'wrapper' => $col_md_4,
            'attributes' => [
                'id' => 'purchase_discount_percent',
            ],
        ]);
        $this->crud->addField([
            'name' => 'status',
            'type' => 'select2_from_array',
            'wrapper' => $col_md_4,
            'default' => 'Sent',
            'options' => [
                'Sent' => "Sent",
                'Pending' => "Pending"
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
        $this->setupCreateOperation(true);
        /* tabs */
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
            'name' => 'quotation_date',
            'type' => 'date'
        ], false, function ($val) {
            $this->crud->addClause('whereDate', 'quotation_date', $val);
        });

        $this->crud->addFilter([
            'name' => 'ref_id',
            'type' => 'text'
        ]);
        $this->crud->addFilter([
            'name' => 'customer_id',
            'type' => 'select2_ajax',
        ], url('admin/crud_extension/ajax-category-options'), function ($value) {
            $this->crud->addClause('whereHas', 'customer', function ($q) use ($value) {
                $q->where('id', $value);
            });
        });
    }

    protected function store()
    {
        request()->merge(['amount' => (float) request()->amount]);

        if (empty(request()->product_detail)) {
            return redirect()->to(backpack_url('quotations'));
        }
        $dueAmount = request()->amount_payable - request()->received_amount;
        if ($dueAmount < 0) {
            $dueAmount = 0;
        }
        request()->merge(['due_amount' => $dueAmount]);
        request()->merge(['seller_id' => backpack_user()->id]);
        $this->crud->addField(['type' => 'hidden', 'name' => 'seller_id']);
        $store =  $this->traitStore();
        $productDetails = array_map(function ($val) {
            if ($val->qty == '') {
                $val->qty = null;
            }
            if ($val->cost_price == '') {
                $val->cost_price = null;
            }
            if ($val->sell_price == '') {
                $val->sell_price = null;
            }
            if ($val->discount == '') {
                $val->discount = 0;
            }
            return $val;
        }, json_decode(request()->product_detail));

        foreach ($productDetails ?? [] as  $value) {
            QuotationDetail::create([
                'product_name' => Product::find($value->id)->product_name,
                'product_code' => $value->product_code,
                'product_note' => $value->note ?? '',
                'total_payable' => $value->t_total,
                'discount'     => $value->discount,
                'qty'          => $value->qty,
                'sell_price'   => $value->sell_price,
                'total_amount' => $value->t_total,
                'quotation_id'  => $this->crud->entry->id,
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
        $this->data['details'] = $entry->quotationDetails;

        return view('admin.quotations.show', $this->data);
    }
    public function editStatus(Request $request)
    {
        try {
            $entry = Quotations::find($request->dataId);
            if (!empty($entry)) {
                $entry->update([
                    'status' => $request->qoute_status,
                    'status_reason' => $request->qoute_discription
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
    public function convert($quotations)
    {
        $quotationsModel = Quotations::firstWhere('id', $quotations);
        $quotationsModel->is_already_convert = true;
        $quotationsModel->save();
        /* Mutate to invoice model */
        $quotationsModel->received_amount = 0.0;
        $quotationsModel->payment_status = "Pending";
        $quotationsModel->invoice_status = "Pending";
        $invoice = Invoice::create(array_merge($quotationsModel->attributesToArray(), ['ref_id' => (string) 'QU-' . time(), 'invoice_date' => Carbon::now()]));

        /* insert to invoice details */
        $quotationDetail =  $quotationsModel->quotationDetails;
        $quotationDetail->map(function ($val) use ($invoice) {
            $val->invoice_id = $invoice->id;
            return $val;
        });
        $quotationDetail->each(fn ($val) => InvoiceDetail::create($val->toArray()));
        resolve(StockPurchaseRepository::class)->removeStock($quotationDetail);
        return true;
    }

    public function print($id)
    {
        $entry = $this->crud->getEntry($id);
        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;

        $this->data['details'] = $entry->quotationDetails;

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'UTF-8',
            'format' => 'A4-L',
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
        $view = view('admin.quotations.quotation_pdf', ['entry' => $entry, 'details' => $entry->quotationDetails]);

        $html = $view->render();
        $mpdf->showImageErrors = true;

        $mpdf->WriteHTML($html);
        $mpdf->Output('Quotation-' .
            Carbon::today()->format('d-m-Y') . '-' . uniqid() .  '.pdf', 'I');

        return abort(400, 'Bad request (something wrong with URL or parameters)');
    }


    private function generateQuotationCode()
    {
        $quotation = (string)((Quotations::max('id') ?? 0) + 1);
        $quotation_code = "";
        for ($i = strlen($quotation); $i < 6; $i++) {
            $quotation_code .= "0";
        }
        $quotation_code .= $quotation;
        // $month = Carbon::now()->month;
        // $year = Carbon::now()->year;
        return 'QU-' . $quotation_code;
    }
}
