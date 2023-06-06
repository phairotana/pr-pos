<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Customer;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class CustomerReportDatatable extends DataTable
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
            ->addColumn('customer_code', function ($query) {
                return$query->customer_code;
            })
            ->addColumn('customer_name', function ($query) {
                return $query->customer_name;
            })
            ->addColumn('branch', function ($query) {
                return optional($query->branch)->branch_name;
            })
            ->addColumn('customer_phone', function ($query) {
                return $query->customer_phone;
            })
            ->addColumn('total_bought', function ($query) {
                return $query->TotalBought;
            })
            ->addColumn('amount', function ($query) {
                return $query->Amount;
            })
            ->addColumn('paid', function ($query) {
                return $query->AmountPaid;
            })
            ->addColumn('due', function ($query) {
                return $query->AmountDue;
            })
            ->filterColumn('customer_code', function ($query, $keyword) {
                $query->where('customer_code', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->where('customer_name', 'like', '%' . $keyword . '%');
            });
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Customer $model)
    {
        $query = (new $model)->newQuery();
        if(backpack_user()->hasRole('DEVELOPER') || backpack_user()->hasRole('ADMINISTRATOR')) {
            $query->whereHas('invoices');
        } else {
            $userId = Auth::user()->id;
            $user_branch_id = Helper::userBranch($userId);
            $query->where('branch_id', $user_branch_id)->whereHas('invoices');
        }

        $query->with(['branch', 'invoices']);
        if (!empty($this->branch)) {
            $query->where('branch_id', $this->branch);
        }
        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->startDate)->format('Y-m-d'));
        }
        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->endDate)->format('Y-m-d'));
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
                    ->setTableId('customerreportdatatable-table')
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
            Column::make('customer_code'),
            Column::make('customer_name'),
            Column::make('branch'),
            Column::make('customer_phone'),
            Column::make('total_sale'),
            Column::make('amount'),
            Column::make('paid'),
            Column::make('due'),
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
