<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use App\Models\InvoiceReturn;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class InvoiceReturnReportDatatable extends DataTable
{
    protected $startDate;
    protected $endDate;
    protected $branch;
    public function __construct($startDate, $endDate, $branch)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->branch = $branch;
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('invoice_date', function ($query) {
                return $query->invoice_return_date ? Carbon::parse($query->invoice_return_date)->format('d-m-Y') : '';
            })
            ->addColumn('reference', function ($query) {
                return $query->ref_id;
            })
            ->addColumn('customer_name', function ($query) {
                return optional($query->customer)->customer_name;
            })
            ->addColumn('invoice_status', function ($query) {
                return $query->invoice_status;
            })
            ->addColumn('grand_total', function ($query) {
                return Str::numberFormatDollar($query->amount - $query->discount_amount);
            })
            ->addColumn('paid', function ($query) {
                return Str::numberFormatDollar($query->received_amount);
            })
            ->addColumn('due', function ($query) {
                return Str::numberFormatDollar(($query->due_amount));
            })
            ->addColumn('payment_status', function ($query) {
                return $query->payment_status;
            })
            ->filterColumn('invoice_date', function ($query, $keyword) {
                $query->where('invoice_date', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('payment_status', function ($query, $keyword) {
                $query->where('payment_status', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->whereHas('customer', function($q) use($keyword) {
                    $q->where('customer_name', 'like', '%' . $keyword . '%');
                });
            });
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\InvoiceReturn $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InvoiceReturn $model)
    {
        $query = (new $model)->newQuery();
        if(backpack_user()->hasRole('DEVELOPER') || backpack_user()->hasRole('ADMINISTRATOR')) {
            $query->with(['branch', 'customer', 'product']);
        } else {
            $userId = Auth::user()->id;
            $user_branch_id = Helper::userBranch($userId);
            $query->with(['branch', 'customer', 'product'])->where('branch_id', $user_branch_id);
        }

        if (!empty($this->branch)) {
            $query->where('branch_id', $this->branch);
        }
        if (!empty($this->startDate)) {
            $query->whereDate('invoice_return_date', '>=', Carbon::parse($this->startDate)->format('Y-m-d'));
        }
        if (!empty($this->endDate)) {
            $query->whereDate('invoice_return_date', '<=', Carbon::parse($this->endDate)->format('Y-m-d'));
        }
        return $query->orderBy('id', 'asc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('invoicereportdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('invoice_date'),
            Column::make('reference'),
            Column::make('customer_name'),
            Column::make('invoice_status'), 
            Column::make('grand_total'), 
            Column::make('paid'),
            Column::make('due'),
            Column::make('payment_status')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'StoctAlert_' . date('YmdHis');
    }
}
