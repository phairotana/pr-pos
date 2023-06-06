@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
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
    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">

            <div class="row mb-0">
                <div class="col-sm-6">
                    @if ($crud->buttons()->where('stack', 'top')->count() || $crud->exportButtons())
                        <div class="d-print-none {{ $crud->hasAccess('create') ? 'with-border' : '' }}">

                            @include('crud::inc.button_stack', ['stack' => 'top'])

                        </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
                </div>
            </div>

            {{-- Backpack List Filters --}}
            @if ($crud->filtersEnabled())
                @include('crud::inc.filters_navbar')
            @endif

            <table id="crudTable"
                class="bg-white table-sticky-list table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2"
                cellspacing="0">
                <thead>
                    <tr>
                        {{-- Table columns --}}
                        @foreach ($crud->columns() as $column)
                            <th data-orderable="{{ var_export($column['orderable'], true) }}"
                                data-priority="{{ $column['priority'] }}" {{-- If it is an export field only, we are done. --}}
                                @if (isset($column['exportOnlyField']) && $column['exportOnlyField'] === true) data-visible="false"
                      data-visible-in-table="false"
                      data-can-be-visible-in-table="false"
                      data-visible-in-modal="false"
                      data-visible-in-export="true"
                      data-force-export="true"
                    @else
                      data-visible-in-table="{{ var_export($column['visibleInTable'] ?? false) }}"
                      data-visible="{{ var_export($column['visibleInTable'] ?? true) }}"
                      data-can-be-visible-in-table="true"
                      data-visible-in-modal="{{ var_export($column['visibleInModal'] ?? true) }}"
                      @if (isset($column['visibleInExport']))
                         @if ($column['visibleInExport'] === false)
                           data-visible-in-export="false"
                           data-force-export="false"
                         @else
                           data-visible-in-export="true"
                           data-force-export="true" @endif
                            @else data-visible-in-export="true" data-force-export="false" @endif
                        @endif
                        >
                        {!! $column['label'] !!}
                        </th>
                        @endforeach

                        @if ($crud->buttons()->where('stack', 'line')->count())
                            <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}"
                                data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        {{-- Table columns --}}
                        @foreach ($crud->columns() as $column)
                            <th>{!! $column['label'] !!}</th>
                        @endforeach

                        @if ($crud->buttons()->where('stack', 'line')->count())
                            <th>{{ trans('backpack::crud.actions') }}</th>
                        @endif
                    </tr>
                </tfoot>
            </table>

            @if ($crud->buttons()->where('stack', 'bottom')->count())
                <div id="bottom_buttons" class="d-print-none text-sm-left">
                    @include('crud::inc.button_stack', ['stack' => 'bottom'])
                    <div id="datatable_button_stack" class="dt-buttons float-right text-right hidden-xs"></div>
                </div>
            @endif

        </div>

    </div>

    @php
    $modalType = 'update';
    $title = 'Edit Status';
    @endphp

    @component('partials.modals.default', [
        'title' => $title ?? 'Status',
        'modalSize' => 'modal-md',
        'modalIdentify' => 'modal-status',
        'modalType' => $modalType ?? 'update',
        ])
        @slot('body')
            <select class="form-control mb-2" id="invoice-status">
                <option value="Receive">Receive</option>
                <option value="Pending">Pending</option>
                <option value="Partial Receive">Partial Receive</option>
            </select>
            <textarea class="form-control" id="discription" placeholder="Description"></textarea>
        @endslot
    @endcomponent

    @component('partials.modals.default', [
        'title' => $title ?? 'Status',
        'modalSize' => 'modal-md',
        'modalIdentify' => 'modal-qoutation',
        'modalType' => $modalType ?? 'update',
        ])
        @slot('body')
            <select class="form-control mb-2" id="qoutation-status">
                <option value="Sent">Sent</option>
                <option value="Pending">Pending</option>
            </select>
            <textarea class="form-control" id="qoutation-note" placeholder="Description"></textarea>
        @endslot
    @endcomponent
@endsection

@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/crud.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/form.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/list.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    @include('crud::inc.datatables_logic')
    <script src="{{ asset('packages/backpack/crud/js/crud.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/form.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/list.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    {{-- UPDATE STATUS CODE --}}
    <script>
        $(function() {
            var dataId = null;
            var type = null;
            var baseURL = '';
            $('body').on('click', '.change-status', function() {
                dataId = $(this).attr('data-id');
                type = $(this).attr('name');
                $("#invoice-status option[value='" + $(this).attr('data-value') + "']").prop(
                    "selected", "selected");
                $("#discription").val($(this).attr('data-reason'));
                $('#modal-status').modal('show');
            });

            $('body').on('click', '.change-qoutation-status', function() {
                dataId = $(this).attr('data-id');
                type = $(this).attr('name');
                $("#qoutation-status option[value='" + $(this).attr('data-value') + "']").prop(
                    "selected", "selected");
                $("#qoutation-note").val($(this).attr('data-reason'));
                $('#modal-qoutation').modal('show');
            });

            $('body').on('click', '#btn-update', function() {
                if(type == 'invoice return') {
                    baseURL = '{{ URL('admin/invoicereturn/edit-status') }}'
                } 
                if (type == 'invoice'){
                    baseURL = '{{ URL('admin/invoice/edit-status') }}'
                }
                if (type == 'purchase'){
                    baseURL = '{{ URL('admin/purchase/edit-status') }}'
                }
                if (type == 'purchase return'){
                    baseURL = '{{ URL('admin/purchasereturn/edit-status') }}'
                }
                if (type == 'qoutation status'){
                    baseURL = '{{ URL('admin/qoutationstatus/edit-status') }}'
                }
                axios.post(baseURL, {
                    status: $('#invoice-status').val(),
                    discription: $('#discription').val(),
                    qoute_status: $('#qoutation-status').val(),
                    qoute_discription: $('#qoutation-note').val(),
                    dataId: dataId
                }).then(function(response) {
                    new Noty({
                        text: response.data.message,
                        type: response.data.messageType,
                        icon: true
                    }).show();
                    if (typeof crud !== 'undefined') {
                        crud.table.ajax.reload();
                    }
                    $('#modal-status').modal('hide');
                    $('#modal-qoutation').modal('hide');
                });
            });

            $('body').on('click', '.btn-hidden-modal', function() {
                $('#modal-status').modal('hide');
                $('#modal-qoutation').modal('hide');
            })
        });
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).appendTo('body');
        });
    </script>
    {{-- END UPDATE DISBURSMENT STATUS --}}
    @stack('crud_list_scripts')
@endsection
