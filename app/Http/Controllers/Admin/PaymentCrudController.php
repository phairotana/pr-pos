<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Helpers\RolePermission;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PaymentRequest;
use App\Models\Purchase;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentCrudController extends CrudController
{
    use \App\Traits\BranchTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
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
        RolePermission::checkPermission($this->crud, 'payment');
        CRUD::setModel(\App\Models\Payment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payment');
        CRUD::setEntityNameStrings('payment', 'payment');
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
        $this->crud->addFilter(
            [
                'name' => 'Reference',
                'type' => 'text',
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereHas', 'invoice', function ($query) use ($value) {
                    $query->where('ref_id', 'LIKE', "%{$value}%");
                });
            }
        );
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        CRUD::addColumn([
            'name'  => 'reference',
            'label' => 'Invoice Reference',
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->invoice)->ref_id;
            }
        ]);
        CRUD::addColumn([
            'label' => 'Amount',
            'name' => 'amount',
            'type' => 'closure',
            'function' => function ($entry) {
                return '$' . number_format($entry->amount, 2);
            }
        ]);
        CRUD::addColumn([
            'name'    => 'received_by',
            'type'      => 'select',
            'entity'    => 'receivedBy',
            'attribute' => 'name',
            'model'     => "App\Models\User"
        ]);
        CRUD::addColumn([
            'name'    => 'branch_id',
            'label'   => 'Branch',
            'type'      => 'select',
            'entity'    => 'branch',
            'attribute' => 'branch_name',
            'model'     => "App\Models\Branch"
        ]);
        CRUD::addColumn([
            'name'  => 'created_at',
            'label' => 'Payment Date',
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
        CRUD::setValidation(PaymentRequest::class);
        $colMd6 = ['class' => 'form-group col-md-6'];

        CRUD::addField([
            'method' => 'POST',
            'name' => 'reference_id',
            'label' => 'Invoice Reference',
            'type' => 'select2_from_ajax',
            'entity' => 'invoice',
            'attribute' => "ref_id",
            'model' => "App\Models\Invoice",
            'data_source' => url("admin/payment/fetch/invoice"),
            'placeholder' => 'Select a reference',
            'minimum_input_length' => 1,
            'wrapperAttributes' =>  $colMd6
        ]);

        CRUD::addField([
            'name' => 'amount',
            'type' => 'number',
            'wrapperAttributes' =>  $colMd6,
            'suffix' => '$',
            'attributes' => [
                'placeholder' => '0.00',
            ],
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
    public function store()
    {
        $rest = $this->traitStore();
        $invoice = Invoice::find(request()->reference_id);
        $branchId = $invoice->branch_id;
        $receivedAmount = $invoice->received_amount;
        $due_amount = $invoice->due_amount;
        if (!empty($invoice)) {
            $invoice->update([
                'received_amount' => $receivedAmount + request()->amount,
                'due_amount' => $due_amount - request()->amount
            ]);
            $freshInvoice = Invoice::find(request()->reference_id);
            if ($freshInvoice) {
                if ($freshInvoice->due_amount > 0) {
                    $freshInvoice->update([
                        'payment_status' => 'Partial'
                    ]);
                }
                if ($freshInvoice->due_amount <= 0 && $freshInvoice->received_amount > 0) {
                    $freshInvoice->update([
                        'payment_status' => 'Paid'
                    ]);
                }
                if ($freshInvoice->due_amount <= 0 && $freshInvoice->received_amount = 0) {
                    $freshInvoice->update([
                        'payment_status' => 'Pending'
                    ]);
                }
            }
        }
        $this->crud->entry->update([
            'received_by' => backpack_user()->id,
            'branch_id' => $branchId
        ]);
        return $rest;
    }
    public function destroy($id)
    {
        $entry = $this->crud->model->find($id);
        if (!empty($entry)) {
            $invoice = Invoice::where('id', $entry->reference_id)->first();
            $invoice->decrement('received_amount', $entry->amount);
            $invoice->increment('due_amount', $entry->amount);
            $freshInvoice = Invoice::where('id', $entry->reference_id)->first();
            if ($freshInvoice && $freshInvoice->due_amount > 0 && $freshInvoice->received_amount > 0) {
                $freshInvoice->update([
                    'payment_status' => 'Partial'
                ]);
            }
            if ($freshInvoice && $freshInvoice->due_amount > 0 && $freshInvoice->received_amount <= 0) {
                $freshInvoice->update([
                    'payment_status' => 'Pending'
                ]);
            }
        }
        return $entry->delete();
    }
    protected function fetchInvoice()
    {
        $query = (new Invoice())->newQuery();
        $query->where('payment_status', '<>', 'Paid');
        $query->where(function ($q) {
            $q->where('ref_id', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(10);
    }
    protected function fetchPurchase()
    {
        $query = (new Purchase())->newQuery();
        $query->where(function ($q) {
            $q->where('ref_id', 'like', '%' . request()->q . '%');
        });
        return $query->paginate(10);
    }
}
