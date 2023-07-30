@extends(backpack_view('blank'))
@section('header')
<section class="content-header">
    <div class="container-fluid mt-2 d-flex">
        <h2>
            <span>Dashboard</span>
            <small id="dashboardrange" class="float-right dashboardcss">
                <i class="la la-calendar"></i>
                <span></span> <i class="la la-caret-down"></i>
            </small>
        </h2>
    </div>
</section>
@endsection

@section('content')


<div class="row p-1" id="vuedash">
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">SALES</h5>
                        <span class="h6 font-weight-bold mb-0" v-text="sales_dash"></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-size icon-shape bg-info text-white rounded-circle shadow-card">
                            <svg t="1689579412940" class="icon-size" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="8101" width="40" height="40">
                                <path d="M0 0h1024v1024H0z" fill="#ffffff" opacity=".01" p-id="8102"></path>
                                <path d="M227.555556 625.777778a56.888889 56.888889 0 0 1 56.888888 56.888889v227.555555a56.888889 56.888889 0 0 1-56.888888 56.888889H113.777778a56.888889 56.888889 0 0 1-56.888889-56.888889v-227.555555a56.888889 56.888889 0 0 1 56.888889-56.888889h113.777778z m0 56.888889H113.777778v227.555555h113.777778v-227.555555zM568.888889 512a56.888889 56.888889 0 0 1 56.888889 56.888889v341.333333a56.888889 56.888889 0 0 1-56.888889 56.888889H455.111111a56.888889 56.888889 0 0 1-56.888889-56.888889v-341.333333a56.888889 56.888889 0 0 1 56.888889-56.888889h113.777778z m0 56.888889H455.111111v341.333333h113.777778v-341.333333zM910.222222 398.222222a56.888889 56.888889 0 0 1 56.888889 56.888889v455.111111a56.888889 56.888889 0 0 1-56.888889 56.888889h-113.777778a56.888889 56.888889 0 0 1-56.888888-56.888889V455.111111a56.888889 56.888889 0 0 1 56.888888-56.888889h113.777778z m0 56.888889h-113.777778v455.111111h113.777778V455.111111zM842.183111 103.765333a28.444444 28.444444 0 0 1 39.480889 40.675556l-3.697778 3.584-325.688889 263.452444a28.444444 28.444444 0 0 1-34.360889 1.080889l-4.152888-3.584-101.603556-106.723555L172.373333 494.990222a28.444444 28.444444 0 0 1-36.408889-0.625778l-3.584-3.697777a28.444444 28.444444 0 0 1 0.682667-36.408889l3.697778-3.584 260.152889-209.123556a28.444444 28.444444 0 0 1 34.304-1.024l4.096 3.584 101.546666 106.609778 305.322667-246.897778z" fill="#ffffff" p-id="8103"></path>
                                <path d="M896.512 59.448889a28.444444 28.444444 0 0 1 30.435556 26.851555l-0.170667 4.721778-25.6 225.052445a28.444444 28.444444 0 0 1-56.718222-1.308445l0.170666-5.12 21.731556-191.032889-198.599111 14.051556a28.444444 28.444444 0 0 1-29.582222-21.276445l-0.796445-5.063111a28.444444 28.444444 0 0 1 21.276445-29.582222l5.063111-0.796444 232.789333-16.497778z" fill="#ffffff" p-id="8104"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-muted text-sm">
                    <small class="text-nowrap"><i class="la la-info text-success"></i> This month by default.</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">PURCHASES</h5>
                        <span class="h6 font-weight-bold mb-0" v-text="purchases_dash"></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-size icon-shape bg-organe text-white rounded-circle shadow-card">
                            <svg t="1689579881269" class="icon-size" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="17175" width="40" height="40">
                                <path d="M701.416881 34.079174c10.865861-6.683144 24.995315-2.325294 30.956199 9.544061l211.383177 390.430832c6.263849 11.014148 2.454406 26.625194-8.415289 33.01432l-1.101927 0.588035q-182.317639 112.078158-364.686411 225.062657a22.754391 22.754391 0 0 1-31.481595-9.008437L326.963978 293.27981c-5.960883-11.84251-2.70496-26.653317 8.139169-32.988754L701.416881 34.079174z m3.28149 54.457138l-86.583014 53.545684 56.728742 104.28414c5.960883 11.014148 2.428839 26.652039-8.692689 33.01432l-114.505719 70.67795c-10.845407 6.950316-24.971026 2.619312-31.206752-9.27561l-56.703175-104.257295-85.754652 53.306635 188.315594 348.432362 86.306894-53.01006-56.452621-104.285419c-6.511846-11.868077-2.70496-26.625194 8.415289-33.01432l114.783119-70.681785c10.318733-6.923471 24.94546-3.153656 30.935744 8.394836l56.41555 104.28414 85.762322-52.71093L704.698371 88.533755z m-167.704974 626.742851a125.822833 125.822833 0 0 1 51.569376 11.040993l363.612609-223.910876c17.906939-11.28132 41.249364-4.651867 52.093493 15.049856 10.04389 19.729847 4.05744 44.323764-14.374895 55.607641L648.796713 783.342915a147.59674 147.59674 0 0 1 18.457902 70.975803 140.912318 140.912318 0 0 1-38.544404 97.600996c-23.59298 25.769987-56.152212 41.140706-91.716814 41.140706a122.913339 122.913339 0 0 1-92.266498-41.140706l-2.428839-2.00571a146.49737 146.49737 0 0 1 2.428839-194.05149l3.255923-3.768536-302.821313-559.890962h-92.244767c-21.188429 0-38.019008-18.819671-38.019008-41.115139 0-22.321035 16.830579-40.552671 38.019008-40.552671h114.506998c14.626727 0 28.201383 9.544061 34.162266 23.471538l315.876965 582.154471c5.959605-0.588035 12.497018-0.882052 19.534261-0.882052z m37.99472 98.189032c-10.04389-10.132096-23.342426-16.788394-37.99472-16.788395a51.417253 51.417253 0 0 0-37.988327 16.790951 59.036139 59.036139 0 0 0-1.603034 79.663378l1.603034 1.416397a50.315327 50.315327 0 0 0 37.993441 16.842084c14.652294 0 27.950829-6.391683 37.994719-16.842084v0.320863c9.491649-10.157662 15.17769-24.620762 15.17769-40.552671a61.224651 61.224651 0 0 0-15.17769-40.847967z m3.529487-647.329183l-75.185365 46.326917 45.607214 82.845157 74.63568-46.034178-45.057529-83.137896z m143.557196 264.999169l-74.910523 46.355041 45.031963 83.139175 74.63568-46.649058-44.75712-82.843879z" fill="#ffffff" p-id="17176"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-muted text-sm">
                    <small class="text-nowrap"><i class="la la-info text-success"></i> This month by default.</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">PAYMENTS</h5>
                        <span class="h6 font-weight-bold mb-0" v-text="sales_dash"></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-size icon-shape bg-success text-white rounded-circle shadow-card">
                            <svg t="1689579980370" class="icon-size" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="20248" width="40" height="40">
                                <path d="M444.416 648.192v-17.408c0-4.096-3.072-8.192-7.168-9.216-22.528-4.096-40.96-12.288-56.32-26.624-10.24-10.24-17.408-23.552-21.504-38.912-4.096-16.384 8.192-31.744 24.576-31.744h1.024c12.288 0 22.528 8.192 24.576 19.456 2.048 10.24 6.144 17.408 13.312 23.552 10.24 9.216 22.528 13.312 37.888 13.312 16.384 0 28.672-4.096 36.864-11.264 8.192-7.168 13.312-17.408 13.312-30.72 0-12.288-4.096-22.528-12.288-30.72s-21.504-15.36-39.936-21.504c-30.72-10.24-53.248-22.528-68.608-36.864s-22.528-34.816-22.528-59.392c0-23.552 7.168-43.008 21.504-58.368 13.312-13.312 30.72-22.528 52.224-26.624 4.096-1.024 7.168-4.096 7.168-9.216v-22.528c0-9.216 7.168-17.408 17.408-17.408h1.024c9.216 0 17.408 7.168 17.408 17.408V296.96c0 4.096 3.072 8.192 7.168 8.192 21.504 4.096 37.888 14.336 51.2 29.696 8.192 10.24 14.336 23.552 17.408 37.888 3.072 15.36-9.216 30.72-25.6 30.72H532.48c-12.288 0-22.528-8.192-25.6-20.48-2.048-9.216-5.12-16.384-10.24-22.528-8.192-10.24-19.456-16.384-32.768-16.384-14.336 0-25.6 4.096-32.768 11.264-7.168 8.192-11.264 18.432-11.264 31.744 0 12.288 4.096 22.528 11.264 29.696 8.192 8.192 21.504 15.36 41.984 22.528 30.72 11.264 53.248 23.552 67.584 37.888 15.36 14.336 22.528 33.792 22.528 59.392s-7.168 45.056-22.528 59.392c-13.312 13.312-31.744 21.504-54.272 25.6-4.096 1.024-7.168 4.096-7.168 9.216v17.408c0 9.216-7.168 17.408-17.408 17.408-9.216 0-17.408-7.168-17.408-17.408z m523.264 148.48c-9.216-6.144-22.528-3.072-27.648 7.168-13.312 21.504-66.56 82.944-100.352 112.64v-471.04c0-51.2-10.24-104.448-29.696-150.528-18.432-46.08-48.128-90.112-84.992-128C649.216 92.16 550.912 51.2 445.44 51.2 228.352 51.2 51.2 228.352 51.2 445.44S228.352 839.68 445.44 839.68H542.72c11.264 0 20.48-9.216 20.48-20.48s-9.216-20.48-20.48-20.48h-97.28C250.88 798.72 92.16 640 92.16 445.44S250.88 92.16 445.44 92.16c94.208 0 183.296 36.864 249.856 103.424 33.792 33.792 60.416 72.704 76.8 114.688C789.504 352.256 798.72 399.36 798.72 445.44v470.016c-32.768-29.696-87.04-90.112-100.352-112.64-6.144-9.216-18.432-13.312-27.648-7.168-9.216 6.144-13.312 18.432-7.168 27.648 9.216 15.36 38.912 51.2 67.584 81.92 61.44 65.536 79.872 65.536 88.064 65.536s25.6 0 88.064-65.536c28.672-30.72 58.368-66.56 67.584-81.92 6.144-8.192 3.072-20.48-7.168-26.624z" p-id="20249" fill="#ffffff"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-muted text-sm">
                    <small class="text-nowrap"><i class="la la-info text-success"></i> This month by default.</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">EXSPENES</h5>
                        <span class="h6 font-weight-bold mb-0" v-text="expense_dash"></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon-size icon-shape bg-danger text-white rounded-circle shadow-card">
                            <svg t="1689580025543" class="icon-size" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="21396" width="40" height="40">
                                <path d="M444.416 750.592v-17.408c0-4.096-3.072-8.192-7.168-9.216-22.528-4.096-40.96-12.288-56.32-26.624-10.24-10.24-17.408-23.552-21.504-38.912-4.096-16.384 8.192-31.744 24.576-31.744h1.024c12.288 0 22.528 8.192 24.576 19.456 2.048 10.24 6.144 17.408 13.312 23.552 10.24 9.216 22.528 13.312 37.888 13.312 16.384 0 28.672-4.096 36.864-11.264s13.312-17.408 13.312-30.72c0-12.288-4.096-22.528-12.288-30.72s-21.504-15.36-39.936-21.504c-30.72-10.24-53.248-22.528-68.608-36.864s-22.528-34.816-22.528-59.392c0-23.552 7.168-43.008 21.504-58.368 13.312-13.312 30.72-22.528 52.224-26.624 4.096-1.024 7.168-4.096 7.168-9.216v-22.528c0-9.216 7.168-17.408 17.408-17.408h1.024c9.216 0 17.408 7.168 17.408 17.408V399.36c0 4.096 3.072 8.192 7.168 8.192 21.504 4.096 37.888 14.336 51.2 29.696 8.192 10.24 14.336 23.552 17.408 37.888 3.072 15.36-9.216 30.72-25.6 30.72H532.48c-12.288 0-22.528-8.192-25.6-20.48-2.048-9.216-5.12-16.384-10.24-22.528-8.192-10.24-19.456-16.384-32.768-16.384-14.336 0-25.6 4.096-32.768 11.264-7.168 8.192-11.264 18.432-11.264 31.744 0 12.288 4.096 22.528 11.264 29.696 8.192 8.192 21.504 15.36 41.984 22.528 30.72 11.264 53.248 23.552 67.584 37.888 15.36 14.336 22.528 33.792 22.528 59.392s-7.168 45.056-22.528 59.392c-13.312 13.312-31.744 21.504-54.272 25.6-4.096 1.024-7.168 4.096-7.168 9.216v17.408c0 9.216-7.168 17.408-17.408 17.408-9.216 0-17.408-7.168-17.408-17.408z m530.432-550.912c-9.216-15.36-38.912-51.2-67.584-81.92C844.8 51.2 827.392 51.2 819.2 51.2s-25.6 0-88.064 65.536c-28.672 30.72-58.368 66.56-67.584 81.92-6.144 9.216-3.072 22.528 7.168 27.648s22.528 3.072 27.648-7.168c13.312-21.504 66.56-82.944 100.352-112.64v470.016c0 46.08-9.216 93.184-27.648 136.192-16.384 40.96-43.008 80.896-76.8 113.664C628.736 894.976 539.648 931.84 445.44 931.84 250.88 931.84 92.16 773.12 92.16 578.56S250.88 225.28 445.44 225.28H542.72c11.264 0 20.48-9.216 20.48-20.48s-9.216-20.48-20.48-20.48h-97.28C228.352 184.32 51.2 361.472 51.2 578.56S228.352 972.8 445.44 972.8c105.472 0 203.776-40.96 278.528-115.712 37.888-37.888 66.56-81.92 84.992-126.976 19.456-47.104 30.72-99.328 30.72-151.552V108.544c32.768 29.696 87.04 90.112 100.352 112.64 4.096 6.144 10.24 10.24 17.408 10.24 4.096 0 7.168-1.024 10.24-3.072 10.24-7.168 13.312-19.456 7.168-28.672z" p-id="21397" fill="#ffffff"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="mb-0 text-muted text-sm">
                    <small class="text-nowrap"><i class="la la-info text-success"></i> This month by default.</small>
                </p>
            </div>
        </div>
    </div>
</div>

@php
Widget::add([
'type' => 'chart',
'controller' => \App\Http\Controllers\Admin\Dashboards\Charts\WeeklySalePurchaseChartController::class,
'class' => 'card mb-2',
'wrapper' => ['class' => 'col-lg-8 float-left mt-4'],
'content' => [
'header' => '<strong>This Week Sales & Purchases</strong>',
],
]);
Widget::add([
'type' => 'chart',
'controller' => \App\Http\Controllers\Admin\Dashboards\Charts\TopFiveCustomersChartController::class,
'class' => 'card mb-2',
'wrapper' => ['class' => 'col-lg-4 float-left mt-4'],
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

@endsection

@section('after_styles')
@include('inc.datatable_styles')
<style>
    #vuedash {
        order: -1;
    }

    .bg-cyan {
        background-color: var(--cyan);
    }

    .bg-organe {
        background-color: var(--bs-orange);
    }

    .bg-indigo {
        background-color: var(--teal);
    }

    .dashboardcss {
        cursor: pointer;
        padding-bottom: 5px;
        border-bottom: 2px solid #f6993f;
        padding-top: 6px;
        margin-left: 25px;
    }

    .dashboardcss .la-calendar {
        margin-left: -7px;
    }

    .dashboardcss .la-caret-down {
        margin-right: -3px;
    }

    /* End */

    .card-stats .card-body {
        padding: .5rem 1rem;
    }


    .icon-size {
        width: 2.7rem;
        height: 2.7rem;
    }

    .icon-size i {
        font-size: 2.25rem;
    }

    .icon-shape {
        display: inline-flex;
        padding: 12px;
        text-align: center;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
    }

    .icon-shape i {
        font-size: 1.25rem;
    }

    .shadow-card {
        box-shadow: 0px 0rem 0.2rem 0px #1b2a4e !important;
    }
</style>
@endsection

@section('after_scripts')
@include('inc.datatable_scripts')
<script>
    $(document).ready(function() {
        // Date Range Picker To Filter Dashboards // *** Apply Only Dashboard Cards
        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function cb(start, end) {
            $('#dashboardrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        var vm = app;

        function fetchDashboardCard(from = null, to = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{ URL('admin/dashboard/fetching') }}",
                data: {
                    from: from,
                    to: to
                },
                success: function(response) {
                    vm.sales_dash = response['sales_dash'];
                    vm.payment_dash = response['payment_dash'];
                    vm.purchases_dash = response['purchases_dash'];
                    vm.expense_dash = response['expense_dash'];
                }
            });
        }

        $('#dashboardrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            function(start, end, label) {
                var s = moment(start.toISOString());
                var e = moment(end.toISOString());
                startdate = s.format("DD-MM-YYYY");
                enddate = e.format("DD-MM-YYYY");
            }
        }, cb);
        cb(start, end);
        fetchDashboardCard(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

        $('#dashboardrange').on('apply.daterangepicker', function(ev, picker) {
            from = picker.startDate.format('YYYY-MM-DD');
            to = picker.endDate.format('YYYY-MM-DD');

            fetchDashboardCard(from, to);
        });

        // Dashboards Datatables // *** Not Apply With Date Range Filter
        $('#dash-recent-sles').DataTable();
        $('#dash-stock-alert').DataTable();
        $('#dash-top-selling-product').DataTable();
        $('#dash-monthly-expense').DataTable();
        $('.dataTables_length').addClass('bs-select');
    });
</script>

<!-- Vue Script -->
<script type="text/javascript">
    var app = new Vue({
        el: "#vuedash",
        data() {
            return {
                sales_dash: '0.00 USD',
                purchases_dash: '0.00 USD',
                expense_dash: '0.00 USD',
                payment_dash: '0.00 USD'
            }
        }
    })
</script>
@endsection