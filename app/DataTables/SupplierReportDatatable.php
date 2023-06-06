<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Supplier;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierReportDatatable extends DataTable
{
    protected $startDate;
    protected $endDate;
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
            ->addColumn('supplier_code', function ($query) {
                return $query->supplier_code;
            })
            ->addColumn('supplier_name', function ($query) {
                return $query->supplier_name;
            })
            ->addColumn('contact_name', function ($query) {
                return $query->contact_name;
            })
            ->addColumn('contact_number', function ($query) {
                return $query->supplier_phone;
            })
            ->addColumn('total_purchased', function ($query) {
                return $query->TotalPurchased;
            })
            ->addColumn('amount', function ($query) {
                return $query->Amount;
            })
            ->filterColumn('supplier_code', function ($query, $keyword) {
                $query->where('supplier_code', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($query, $keyword) {
                $query->where('supplier_name', 'like', '%' . $keyword . '%');
            });
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Supplier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Supplier $model)
    {
        $query = (new $model)->newQuery();
        $query->whereHas('purchass');
        $query->with('purchass');
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
                    ->setTableId('supplierreportdatatable-table')
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
            Column::make('supplier_code'),
            Column::make('supplier_name'),
            Column::make('contact_name'), 
            Column::make('contact_number'), 
            Column::make('total_purchased'), 
            Column::make('amount')
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
