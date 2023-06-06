@extends(backpack_view('blank'))
@section('header')
    <section class="content-header">
        <div class="container-fluid mt-2 d-flex">
            <h2>Dashboard</h2>
        </div>
    </section>
@endsection

@section('content')
    @if (Auth::user()->can('list dashboards'))
        @php
            Widget::add([
                'type' => 'progress',
                'wrapper' => ['class' => 'col-sm-6 col-md-6'],
                'class' => 'card text-white bg-info mt-3',
                'value' => $sales_dash,
                'description' => 'SALES',
            ]);
            Widget::add([
                'type' => 'progress',
                'wrapper' => ['class' => 'col-sm-6 col-md-6'],
                'class' => 'card text-white bg-indigo mt-3',
                'value' => $purchases_dash,
                'description' => 'PURCHASES',
            ]);
            // Widget::add([
            //     'type' => 'progress',
            //     'wrapper' => ['class' => 'col-sm-6 col-md-4'],
            //     'class' => 'card text-white bg-cyan mt-3',
            //     'value' => $sales_return_dash,
            //     'description' => 'SALES RETURN',
            // ]);
            // Widget::add([
            //     'wrapper' => ['class' => 'col-sm-6 col-md-4'],
            //     'type' => 'progress',
            //     'class' => 'card text-white bg-primary mt-3',
            //     'value' => $purchases_return_dash,
            //     'description' => 'PURCHASES RETURN',
            // ]);
            Widget::add([
                'wrapper' => ['class' => 'col-sm-6 col-md-6'],
                'type' => 'progress',
                'class' => 'card text-white bg-success mt-3',
                'value' => $monthly_payment,
                'description' => 'MONTHLY PAYMENT',
            ]);
            Widget::add([
                'wrapper' => ['class' => 'col-sm-6 col-md-6'],
                'type' => 'progress',
                'class' => 'card text-white bg-organe mt-3',
                'value' => $monthly_expense,
                'description' => 'MONTHLY EXPENSE',
            ]);
            Widget::add([
                'type' => 'chart',
                'controller' => \App\Http\Controllers\Admin\Dashboards\Charts\WeeklySalePurchaseChartController::class,
                'class' => 'card mb-2',
                'wrapper' => ['class' => 'col-lg-8 float-left mt-3'],
                'content' => [
                    'header' => '<strong>This Week Sales & Purchases</strong>',
                ],
            ]);
            Widget::add([
                'type' => 'chart',
                'controller' => \App\Http\Controllers\Admin\Dashboards\Charts\TopFiveCustomersChartController::class,
                'class' => 'card mb-2',
                'wrapper' => ['class' => 'col-lg-4 float-left mt-3'],
                'content' => [
                    'header' => '<strong>Top 5 Customers</strong>',
                ],
            ]);
        @endphp
        <div class="col-lg-12 mt-3">
            @component('admin.dashboards.inc.recent_sales', compact('recent_sales_dash'))
            @endcomponent
        </div>
        <div class="col-lg-12 mt-3">
             @component('admin.dashboards.inc.stock_alert', compact('stock_alert_dash'))
            @endcomponent
        </div>
        <div class="col-lg-12 mt-3">
            @component('admin.dashboards.inc.top_selling_products', compact('top_ten_selling_products_dash'))
            @endcomponent
        </div>
        {{-- @include('admin.dashboards.inc.main_dashboard') --}}
    @endif
@endsection

@section('after_styles')
    @include('inc.datatable_styles')
    <style>
        .bg-cyan{
            background-color: var(--cyan);
        }
        .bg-organe{
            background-color: var(--bs-orange);
        }
        .bg-indigo{
            background-color: var(--teal);
        }
    </style>
@endsection

@section('after_scripts')
    @include('inc.datatable_scripts')
    <script>
        $(document).ready(function() {
            $('#dash-recent-sles').DataTable();
            $('#dash-stock-alert').DataTable();
            $('#dash-top-selling-product').DataTable();
            $('#dash-monthly-expense').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
@endsection
