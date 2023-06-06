@extends('layouts.app')
@php
$defaultBreadcrumbs = [
    'Admin' => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('flexi_crud.list') => false,
];
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
        </h2>
    </div>
@endsection

@section('content')
    <div id="mySidenav" class="sidenav shadow right col-md-4">
        @component('inc.date_range_filter')
        @endcomponent
    </div>
    <div class="card card-border-top">
        <div class="card-body">
            <div id="datatable-filters">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table text-nowrap overflow-auto mt-3" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope=""> No</th>
                                    <th scope="">Supplier Code</th>
                                    <th scope="">Supplier Name</th>
                                    <th scope="">Contact Name</th>
                                    <th scope="">Contact Number</th>
                                    <th scope="">Total Purchase</th>
                                    <th scope="">Amount</th>
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
@endsection

@section('after_scripts')
    @include('inc.datatable_scripts')
    <script>
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
                    url: '{{ URL('admin/report/supplier') }}',
                    data: {
                        startDate: function() {
                            return fromDateCusInfo
                        },
                        endDate: function() {
                            return toDateCusInfo
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
                        data: 'supplier_code',
                        name: 'supplier_code'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name'
                    },
                    {
                        data: 'contact_name',
                        name: 'contact_name'
                    },
                    {
                        data: 'contact_number',
                        name: 'contact_number'
                    },
                    {
                        data: 'total_purchased',
                        name: 'total_purchased'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        }
        $(function() {
            cusInfoDataTable();
            $('#datatable-filters .dataTables_filter').append(
                '<label style="margin-left: 10px;"><a href="#" class="btn btn-info btn-filter" data-toggle="0"><i class="la la-filter"></i> Filter</a></label>'
            );
            $('#datatable-filters .dataTables_filter').append(
                '<label style="margin-left: 10px;"><a href="#" class="btn btn-danger btn-reset" data-toggle="0"><i class="la la-eraser"></i> Reset</a></label>'
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

            // REMOVE ALL FILTERS
            $('.btn-reset').on('click', function() {
                $('#date-range-picker').val('');
                fromDateCusInfo = null;
                toDateCusInfo = null;
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
        });
    </script>
@endsection
