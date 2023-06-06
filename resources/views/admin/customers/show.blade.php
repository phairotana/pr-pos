@extends('layouts.app')
@section('header')
    <section class="container-fluid d-print-none mt-3">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')) . ' ' . $crud->entity_name !!}.</small>
            @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><em
                            class="la la-angle-double-left"></em> {{ trans('backpack::crud.back_to_all') }}
                        <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection
@section('content')
    <div class="box p-0">
        <div class="box-body">
            <div class="card-header bg-white"><strong>Overview</strong></div>
            @component('admin.customers.total_data', compact('entry', 'total_data'))
            @endcomponent
        </div>
    </div>
    <div class="box p-0">
        <div class="box-body mt-3">
            <div class="col-sm-12 p-0">
                {{-- Tab Menu --}}
                <div class="d-sm-flex justify-content-between bg-white card-header">
                    <ul class="nav nav-tabs pl-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-customer-info" data-bs-toggle="tab" href="#tab_customer_info"
                                role="tab"><strong>Customer Info</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-quotations" data-bs-toggle="tab" href="#tab_quotations"
                                role="tab"><strong>Quotations</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-sale" data-bs-toggle="tab" href="#tab_sales"
                                role="tab"><strong>Bought</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-return" data-bs-toggle="tab" href="#tab_return"
                                role="tab"><strong>Return</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-0" id="tab-sale-payment" data-bs-toggle="tab" href="#tab_sale_payment"
                                role="tab"><strong>Payments</strong></a>
                        </li>
                    </ul>
                </div>
                {{-- Tab Contents --}}
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_customer_info">
                        @component('admin.customers.customer_info', compact('entry'))
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab_quotations">
                        @component('admin.customers.cus_quotation', compact('qoutation_data'))
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab_sales">
                        @component('admin.customers.cus_sale', compact('sale_data'))
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab_return">
                        @component('admin.customers.cus_return', compact('return_data'))
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab_sale_payment">
                        @component('admin.customers.sale_payment', compact('sale_payment_data'))
                        @endcomponent
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
        $(document).ready(function() {
            $('#cus-quotation-table').DataTable();
            $('#cus-sale-table').DataTable();
            $('#cus-return-table').DataTable();
            $('#cus-sale-payment-table').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
@endsection
