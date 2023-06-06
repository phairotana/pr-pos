<?php

namespace App\Http\Controllers\Admin\Dashboards\Charts;

use Carbon\Carbon;
use App\Libraries\DashboardLib;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Backpack\CRUD\app\Http\Controllers\ChartController;

/**
 * Class WeeklySalePurchaseChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklySalePurchaseChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();
        // MANDATORY. Set the labels for the dataset points
        $labels = [];

        for ($days_backwards = 6; $days_backwards >= 0; $days_backwards--) {
            $labels[] = Carbon::now()->subDay($days_backwards - 1)->format('d-m-Y');
        }

        $this->chart->labels($labels);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('dashboard/charts/weekly-sale-purchase'));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    { 
        for ($days_backwards = 6; $days_backwards >= 0; $days_backwards--) {

            $date = Carbon::now()->subDay($days_backwards - 1)->format('Y-m-d');
            $sales_dash[] =  round(DashboardLib::weeklySalesDash($date), 2);
            $purchases_dash[] = round(DashboardLib::weeklyPurchasesDash($date), 2);
        }
        
        $this->chart->dataset('Sales', 'bar', $sales_dash)
            ->color('rgb(77, 189, 116)')
            ->backgroundColor('rgba(77, 189, 116, 0.4)');

        $this->chart->dataset('Purchase', 'bar', $purchases_dash)
            ->color('rgb(96, 92, 168)')
            ->backgroundColor('rgba(96, 92, 168, 0.4)');
    }
}