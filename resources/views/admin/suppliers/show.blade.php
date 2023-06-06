@php
use Carbon\Carbon;
use App\Helpers\Helper;
@endphp
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
    <div class="card-header bg-white"><strong>Supplier Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Code</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->supplier_code }}</td>
                <td class="border-0" style="width:20%">Supplier Name</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->supplier_name }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Contact Name</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->contact_name }}</td>
                <td class="border-0" style="width:20%">Contact Number</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->supplier_phone }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Email</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->supplier_email }}</td>
                <td class="border-0" style="width:20%">Address</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->address }}</td>
            </tr>
        </table>
    </div>
    <div class="box bg-white">
        <div class="card-header bg-white mb-3"><strong>Purchases</strong></div>
        <table class="table" id="purchase-data" aria-hidden="true">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Reference</th>
                    <th>Purchase Date</th>
                    <th>Qty</th>
                    <th>Purchase By</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->ref_id }}</td>
                        <td>{{ $item->purchase_date ? Carbon::parse($item->purchase_date)->format('d-m-Y') : '' }}
                        </td>
                        <td>{{ optional($item->purchaseDetail)->sum('qty') }}</td>
                        <td>{{ optional($item->purchaseBy)->name }}</td>
                        <td>{{ Helper::formatCurrency($item->amount, '$') }}</td>
                        <td>{{ Helper::formatCurrency($item->discount_amount, '$') }}</td>
                        <td>{{ Helper::formatCurrency($item->amount_payable, '$') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('after_styles')
    @include('inc.datatable_styles')
@endsection

@section('after_scripts')
    @include('inc.datatable_scripts')
    <script>
        $(document).ready(function() {
            $('#purchase-data').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
@endsection
