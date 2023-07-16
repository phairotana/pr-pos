<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\StockTake;
use App\Traits\CrudExtension;
use Mpdf\Config\FontVariables;
use App\Exports\StockTakeExport;
use Mpdf\Config\ConfigVariables;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QuotationsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockTakeCrudController extends CrudController
{
    use \App\Traits\BranchTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
    //     update as traitUpdate;
    // }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    use CrudExtension;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\StockTake::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock/take');
        CRUD::setEntityNameStrings('stock takes', 'stock takes');
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
            'name' => 'stk_no',
            'type' => 'tye',
            'label' => 'Stk No',
            'orderable' => false,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'label' => 'Action Date',
            'name' => 'created_at',
            'type' => 'closure',
            'function' => function ($entry) {
                return Carbon::parse($entry->created_at)->format('d-m-Y h:i:s A');
            }
        ]);
        $this->crud->addColumn([
            'label' => 'Remarks',
            'name' => 'remarks',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'label' => 'Action By',
            'name' => 'CreatedBy',
            'type' => 'text'
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
        $this->crud->addField([
            'type' => 'hidden',
            'name' => 'remarks',
        ]);
        CRUD::addField([
            'label' => '',
            'name' => 'stoktage',
            'type' => 'stocktake'
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
    }

    protected function store()
    {
        if (empty(request()->product_detail)) {
            return redirect()->to(backpack_url('stock/take'));
        }

        $this->crud->addField(['type' => 'hidden', 'name' => 'details']);
        $this->crud->addField(['type' => 'hidden', 'name' => 'action_by']);

        $details = array_map(function ($val) {
            $qty = !empty($val->qty) ? $val->qty : 0;
            return [
                'product_id' => $val->id,
                'category_id' => $val->category_id,
                'expected' => $val->stock_qty,
                'counted' => $qty,
                'difference' => $val->qty_counted,
                'note' => $val->note
            ];
        }, json_decode(request()->product_detail));

        request()->merge(
            [
                'details' => json_encode($details),
                'action_by' => request()->user()->id
            ]
        );
        $store = $this->traitStore();
        $entry = $this->crud->getCurrentEntry();
        $stk = 'STK' . str_pad($entry->id, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(uniqid());
        $entry->update(['stk_no' => $stk]);
        return $store;
    }

    protected function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['details'] = Product::hydrate($entry->details);
        return view('admin.stocks.stocktake_details', $this->data);
    }

    public function exportToExcel($id)
    {
        $entry = StockTake::findOrFail($id);
        $details = Product::hydrate($entry->details);

        ob_end_clean();
        ob_start();
        return Excel::download(new StockTakeExport($entry, $details), $entry->stk_no . '.xls');
    }

    public function exportToPdf($id)
    {
        $entry = StockTake::findOrFail($id);
        $details = Product::hydrate($entry->details);

        if (!empty($entry)) {

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
                    'kh-siemreap' => [
                        'R' => 'KhmerOSSiemreap.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75
                    ]
                ],
                'tempDir' => storage_path('app/mpdf')
            ]);

            $view = View('admin.stocks.stocktake_pdf', [
                'entry' => $entry,
                'detials' => $details
            ]);

            $html = $view->render();
            $mpdf->showImageErrors = true;
            $mpdf->WriteHTML($html);
            $mpdf->Output($entry->stk_no . '.pdf', 'I');
        }

        return abort(400, 'Bad request (Oops something went wrong, Please try again later.)');
    }
}
