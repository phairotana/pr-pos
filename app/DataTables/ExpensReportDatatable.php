<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Expense;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class ExpensReportDatatable extends DataTable
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
            ->addColumn('expense_date', function ($query) {
                return $query->expense_date ? Carbon::parse($query->expense_date)->format('d-m-Y') : '';
            })
            ->addColumn('branch', function ($query) {
                return optional($query->branch)->branch_name;
            })
            ->addColumn('category', function ($query) {
                return $query->category;
            })
            ->addColumn('amount', function ($query) {
                return Str::numberFormatDollar($query->amount);
            })
            ->filterColumn('expense_date', function ($query, $keyword) {
                $query->where('expense_date', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $query->where('amount', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('branch', function ($query, $keyword) {
                $query->whereHas('branch', function($q) use($keyword) {
                    $q->where('branch_name','like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('category', function ($query, $keyword) {
                $query->where('category', 'like', '%' . $keyword . '%');
            });
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Expense $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Expense $model)
    {
        $query = (new $model)->newQuery();
        if(backpack_user()->hasRole('DEVELOPER') || backpack_user()->hasRole('ADMINISTRATOR')) {
            $query->with('branch');
        } else {
            $userId = Auth::user()->id;
            $user_branch_id = Helper::userBranch($userId);
            $query->with('branch')->where('branch_id', $user_branch_id);
        }

        if (!empty($this->branch)) {
            $query->where('branch_id', $this->branch);
        }
        if (!empty($this->startDate)) {
            $query->whereDate('expense_date', '>=', Carbon::parse($this->startDate)->format('Y-m-d'));
        }
        if (!empty($this->endDate)) {
            $query->whereDate('expense_date', '<=', Carbon::parse($this->endDate)->format('Y-m-d'));
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
            Column::make('expense_date'),
            Column::make('branch'),
            Column::make('category'),
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
