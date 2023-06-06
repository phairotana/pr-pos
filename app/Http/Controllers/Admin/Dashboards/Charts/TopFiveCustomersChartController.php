<?php

namespace App\Http\Controllers\Admin\Dashboards\Charts;

use App\Helpers\Helper;
use App\Libraries\DashboardLib;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Backpack\CRUD\app\Http\Controllers\ChartController;

/**
 * Class WeeklySalePurchaseChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TopFiveCustomersChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $labels = [];
        $values = [];
        $bgColors = [];
        $topFiveCus = DashboardLib::topFiveCustomers();
        $lenght = count($topFiveCus);
        $top1 = $lenght >= 1 ? $topFiveCus[0]['total_amount'] : 0;
        $top2 = $lenght >= 2 ? $topFiveCus[1]['total_amount'] : 0;
        $top3 = $lenght >= 3 ? $topFiveCus[2]['total_amount'] : 0;
        $top4 = $lenght >= 4 ? $topFiveCus[3]['total_amount'] : 0;
        $top5 = $lenght >= 5 ? $topFiveCus[4]['total_amount'] : 0;

        if ($lenght >= 1) {
            $labels[] = $topFiveCus[0]['customer_name'];
            $values[] = round($top1, 2);
            $bgColors[] = Helper::randomColor();
        }

        if ($lenght >= 2) {
            $labels[] = $topFiveCus[1]['customer_name'];
            $values[] = round($top2, 2);
            $bgColors[] = Helper::randomColor();
        }

        if ($lenght >= 3) {
            $labels[] = $topFiveCus[2]['customer_name'];
            $values[] = round($top3, 2);
            $bgColors[] = Helper::randomColor();
        }

        if ($lenght >= 4) {
            $labels[] = $topFiveCus[3]['customer_name'];
            $values[] = round($top4, 2);
            $bgColors[] = Helper::randomColor();
        }

        if ($lenght >= 5) {
            $labels[] = $topFiveCus[4]['customer_name'];
            $values[] = round($top5, 2);
            $bgColors[] = Helper::randomColor();
        }

        $this->chart->dataset('Red', 'pie', $values)
            ->backgroundColor($bgColors);

        // OPTIONAL
        $this->chart->displayAxes(false);
        $this->chart->displayLegend(true);

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels($labels);
    }
}
