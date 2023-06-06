@extends('layouts.app')
@section('header')
    <div class="container-fluid mt-5">
        <h2>
            <span class="text-capitalize">Payment</span>
            <small id="datatable_info_stack"></small>
        </h2>
    </div>
@endsection
@section('content')
    <div class="card card-border-top">
        <div class="card-body">
            <div id="datatable-filters">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table text-nowrap overflow-auto mt-3" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Invoice</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    @include('inc.datatable_styles')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #e6e9ec;
            border-radius: 2px;
            height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 70%;
        }
    </style>
@endsection
@section('after_scripts')
    @include('inc.datatable_scripts')
    @include('inc.select2_from_ajax')
    <script>
        // FORMAT CURRENCY
        let currency_format = new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
        });
        var status = null;
        var customer = null;
        var fromDateCusInfo = null;
        var toDateCusInfo = null;

        function ucwords(str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
                return $1.toUpperCase();
            });
        }

        function cusInfoDataTable() {
            $('#datatable-filters').find('table').DataTable({
                dom: 'Blfrtip',
                pageLength: 10,
                destroy: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                buttons: [
                    'excel'
                ],
                ajax: {
                    url: '{{ URL("admin/report/payment") }}',
                    data: {
                        startDate: function() {
                            return fromDateCusInfo
                        },
                        endDate: function() {
                            return toDateCusInfo
                        },
                        customer: function() {
                            return customer
                        },
                        status: function() {
                            return status
                        }
                    }
                },
                columns: [{
                        data: "id",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'invoice'
                    },
                    {
                        data: 'amount_payable',
                        render: function(data, type, full, meta) {
                            return currency_format.format(data);
                        }
                    },
                    {
                        data: 'received_amount',
                        render: function(data, type, full, meta) {
                            return currency_format.format(data);
                        }
                    },
                    {
                        data: 'due_amount',
                        render: function(data, type, full, meta) {
                            return currency_format.format(data);
                        }
                    },
                    {
                        data: 'date'
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });
        }
        $(function() {
            cusInfoDataTable();
            $('.dt-buttons').append(
                `            
                    <div class="form-group col-3 col-sm-3 col-md-3 float-left pl-0">
                        <select name="customer" id="select2_customer" class="form-control customer_element">
                            <option value="">-</option>
                        </select>
                    </div>
                    <div class="form-group col-3 col-sm-3 col-md-3 float-left pl-0">
                        <select name="status" id="payment-status" class="form-control">
                            <option value="">-Status-</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div class="form-group col-4 col-sm-3 col-md-3 float-left pl-0">
                        <input readonly type="text" class="form-control" name="date-range-picker" id="date-range-picker" placeholder="DD/MM/YYYY - DD/MM/YYYY">
                    </div>
                    <button class="btn-reset buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" type="button"><span>Reset</span></button>
                    <a href="{{ url('admin/report/payment/print') }}" target="_blank" title="Print payment">
                        <button class="buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" type="button"><span>Print</span></button>    
                    </a>  
                `
            );
            // FILTER DATE RANGE PICKER
            $('#date-range-picker').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 last days': [moment().subtract(6, 'days'), moment()],
                    '30 last days': [moment().subtract(29, 'days'), moment()],
                    'This month': [moment().startOf('month'), moment().endOf('month')],
                    'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                function(start, end, label) {
                    var s = moment(start.toISOString());
                    var e = moment(end.toISOString());
                    startdate = s.format("DD-MM-YYYY");
                    enddate = e.format("DD-MM-YYYY");
                }
            });
            $('#date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                fromDateCusInfo = picker.startDate.format('YYYY-MM-DD');
                toDateCusInfo = picker.endDate.format('YYYY-MM-DD');
                $('#datatable-filters').find('table').DataTable().ajax.reload();
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });
            $('#date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                fromDateCusInfo = null;
                toDateCusInfo = null;
                $('#datatable-filters').find('table').DataTable().ajax.reload();
            });

            // FILTER BRANCH
            $(".customer_element").change(function() {
                customer = $(this).children("option:selected").val();
                $('#datatable-filters').find('table').DataTable().ajax.reload();
            });
            $("#payment-status").change(function() {
                status = $(this).children("option:selected").val();
                $('#datatable-filters').find('table').DataTable().ajax.reload();
            });
            // REMOVE ALL FILTERS
            $('.btn-reset').on('click', function() {
                $('#date-range-picker').val('');
                fromDateCusInfo = null;
                toDateCusInfo = null;
                $(".customer_element").val('').trigger("change");
                customer = null;
                $('#datatable-filters').find('table').DataTable().ajax.reload();
                $('.sidenav').hide(100);
            });
        });

        $(document).ready(function() {
            $('.sidenav').hide();
            $('.btn-filter').click(function() {
                $('.sidenav').fadeToggle();
            });
            $('.la-window-close').click(function() {
                $('.sidenav').hide(100);
            });

            // SELECT 2 SEARCH BOX
            $('.hidden-reset-filters').select2({
                theme: "bootstrap",
                allowClear: true,
                placeholder: {
                    text: '-'
                }
            }).on('select2:unselecting', function(e) {
                $(this).val('').trigger('change');
                e.preventDefault();
            });

            $('#payment-status').select2();
            // SELECT 2 SEARCH BOX
            $('.hidden-reset-filters').select2({
                theme: "bootstrap",
                allowClear: true,
                placeholder: {
                    text: '-'
                }
            }).on('select2:unselecting', function(e) {
                $(this).val('').trigger('change');
                e.preventDefault();
            });

            select2SingleOption("#select2_customer", `{{ URL('admin/customers/fetch/customer-name') }}`, (
                item) => {
                return {
                    text: item['customer_name'],
                    id: item['id']
                };
            }, 'POST', 'Select a customer');
        });
    </script>
@endsection
