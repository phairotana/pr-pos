<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Purchase;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PurchaseReportDatatable extends DataTable
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
            ->addColumn('purchased_date', function ($query) {
                return $query->purchase_date;
            })
            ->addColumn('reference', function ($query) {
                return $query->ref_id;
            })
            ->addColumn('supplier_name', function ($query) {
                return optional($query->supplier)->supplier_name;
            })
            ->addColumn('purchase_status', function ($query) {
                return $query->purchase_status;
            })
            ->addColumn('grand_total', function ($query) {
                return $query->GrandTotal;
            })
            ->filterColumn('purchased_date', function ($query, $keyword) {
                $query->where('purchase_date', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('purchase_status', function ($query, $keyword) {
                $query->where('purchase_status', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_name', function ($query, $keyword) {
                $query->whereHas('supplier', function($q) use($keyword) {
                    $q->where('supplier_name', 'like', '%' . $keyword . '%');
                });
            });
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Purchase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Purchase $model)
    {
        $query = (new $model)->newQuery();
        $query->with(['supplier', 'product']);
        if (!empty($this->startDate)) {
            $query->whereDate('purchase_date', '>=', Carbon::parse($this->startDate)->format('Y-m-d'));
        }
        if (!empty($this->endDate)) {
            $query->whereDate('purchase_date', '<=', Carbon::parse($this->endDate)->format('Y-m-d'));
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
                    ->setTableId('purchasereportdatatable-table')
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
            Column::make('purchased_date'),
            Column::make('reference'),
            Column::make('supplier_name'), 
            Column::make('purchase_status'), 
            Column::make('grand_total'),
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
