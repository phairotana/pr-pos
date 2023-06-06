@php
use Carbon\Carbon;
use App\Helpers\Helper;
@endphp
@extends('layouts.app')
@section('header')
    <section class="container-fluid d-print-none">
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
    <div class="card-header bg-white"><strong>Customer Info</strong>
        {{-- <a href="{{ backpack_url('invoice-return/' . $entry->id . '/print') }}" class="float-right"><span
                class="btn btn-info btn-sm mb-1"><em class="la la-print"></em></span></a> --}}
    </div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Code</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->customer)->customer_code }}</td>
                <td class="border-0" style="width:20%">Name</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->customer)->customer_name }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Phone</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->customer)->customer_phone }}</td>
                <td class="border-0" style="width:20%">Email</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->customer)->customer_email }}</td>
            </tr>
        </table>
    </div>
    <div class="card-header bg-white"><strong>Invoice Return Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Reference</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->ref_id }}</td>
                <td class="border-0" style="width:20%">Return Date</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    {{ !empty($entry->invoice_return_date) ? Carbon::parse($entry->invoice_return_date)->format('d-m-Y') : '' }}
                </td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">QTY</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $details->sum('quantity') }}</td>
                <td class="border-0" style="width:20%">Invoice Status</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    <div class='border-outline-status'>
                        @if ($entry->invoice_status == 'Pending')
                            <span class='order'>Pending</span>
                        @elseif ($entry->invoice_status == 'Partial Receive')
                            <span class='pending'>Partial Received</span>"
                        @elseif ($entry->invoice_status == 'Receive')
                            <span class='success'>Received</span>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Note</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->noted }}</td>
                <td class="border-0" style="width:20%">Branch</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_name }}</td>
                    {{ !empty($entry->credit_date) ? Carbon::parse($entry->credit_date)->format('d-m-Y') : '' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="card-header bg-white"><strong>Invoices Detial</strong></div>
    <div class="box bg-white">
        <table class="table table-bordered mt-2" aria-hidden="true">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->product_code }}</td>
                        <td>{{ optional($item->product)->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('after_styles')
    <style>
        .status {
            border: 1px solid var(success)
        }

    </style>
@endsection

@section('after_scripts')
    <script>
        var status = $('.status').text();

        if (status == 'Paid') $('.status').addClass('success');
        else if (status == 'Partial') $('.status').addClass('pending');
        else $('.status').addClass('order');
    </script>
@endsection
