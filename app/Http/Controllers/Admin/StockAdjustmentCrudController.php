<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Traits\CrudExtension;
use App\Models\StockAdjustment;
use App\Exports\StockAdjustExport;
use App\Models\Stock;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QuotationsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockAdjustmentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\StockAdjustment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock/adjustment');
        CRUD::setEntityNameStrings('stock adjustments', 'stock adjustments');
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
            'name' => 'adjust_no',
            'type' => 'tye',
            'label' => 'ADJUST NO',
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
            'name' => 'stockadjustment',
            'type' => 'stockadjustment'
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
            return redirect()->to(backpack_url('stock/adjustment'));
        }

        $this->crud->addField(['type' => 'hidden', 'name' => 'details']);
        $this->crud->addField(['type' => 'hidden', 'name' => 'action_by']);

        $details = array_map(function ($val) {

            $stockEntry = Stock::findOrFail(optional($val->stock)->id);;
            if (!empty($stockEntry)) {
                $stockEntry->update([
                    'quantity' => $val->qty_after,
                    'purchase' => $val->si_after,
                    'sale_out' => $val->so_after
                ]);

                return [
                    'product_id' => $val->id,
                    'category_id' => $val->category_id,

                    'qty_before' => $val->stock_qty,
                    'si_before' => optional($val->stock)->purchase,
                    'so_before' => optional($val->stock)->sale_out,

                    'qty_movement' => $val->qty_movement,
                    'si_movement' => $val->si_movement,
                    'so_movement' => $val->so_movement,

                    'qty_after' => $val->qty_after,
                    'si_after' => $val->si_after,
                    'so_after' => $val->so_after
                ];
            }
        }, json_decode(request()->product_detail));

        request()->merge(
            [
                'details' => json_encode($details),
                'action_by' => request()->user()->id
            ]
        );
        $store = $this->traitStore();
        $entry = $this->crud->getCurrentEntry();
        $stk = 'ADJUST' . str_pad($entry->id, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(uniqid());
        $entry->update(['adjust_no' => $stk]);
        return $store;
    }

    protected function show($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['details'] = Product::hydrate($entry->details);
        return view('admin.stocks.stockadjust_details', $this->data);
    }

    public function exportToExcel($id)
    {
        $entry = StockAdjustment::findOrFail($id);
        $details = Product::hydrate($entry->details);

        ob_end_clean();
        ob_start();
        return Excel::download(new StockAdjustExport($entry, $details), $entry->adjust_no . '.xls');
    }
}
