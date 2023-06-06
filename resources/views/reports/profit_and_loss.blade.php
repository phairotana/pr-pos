@extends('layouts.app')
@section('header')
    <div class="container-fluid mt-5">
        <h2>
            <span class="text-capitalize">Profit and Loss</span>
            <small id="datatable_info_stack"></small>
        </h2>
    </div>
@endsection
@section('content')
    <div class="card card-border-top">
        <div class="card-body">
            <div id="datatable-filters">
                <div class="row">
                    <form action="">
                        <div class="row">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                      <img src="https://image.pngaaa.com/1/18001-middle.png" style="width: 20px;"/>
                                  </span>
                                </div>
                                <input type="text" class="form-control" name="daterange">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <div>SALES</div>
                                <div class="text-value sale-data"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <div>PURCHASES</div>
                                <div class="text-value purchase-data"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <div>EXPENSES</div>
                                <div class="text-value expense-data"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <div>PROFIT (Sale - Purchase)</div>
                                <div class="text-value profit-data"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    @include('inc.datatable_styles')
@endsection
@section('after_scripts')
    @include('inc.datatable_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var fromDate = "";
            var toDate = "";

            fetch_profit_data(fromDate, toDate);

            $('input[name="daterange"]').daterangepicker({
                autoUpdateInput: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                fetch_profit_data(picker.startDate.format('DD-MM-YYYY'), picker.endDate.format('DD-MM-YYYY'));
            });
            function fetch_profit_data(fromDate, toDate) {
                $.ajax({
                    url: "{{ route('admin.report.fetch_profit_data') }}",
                    type: "POST",
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        fromDate: fromDate,
                        toDate: toDate
                    },
                    success: function(result) {
                        $('.sale-data').html(result['invoice'] + ' USD');
                        $('.purchase-data').html(result['purchase'] + ' USD');
                        $('.expense-data').html(result['expense'] + ' USD');
                        $('.profit-data').html(result['profit'] + ' USD');
                    }
                });
            }
        });
    </script>
@endsection
