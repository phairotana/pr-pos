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
    <div class="card-header bg-white"><strong>Customer Info</strong>
        <a href="{{ backpack_url('invoice/' . $entry->id . '/print?mode=deliver') }}" class="float-right"
            title="Print invoice for customer" target="_blank"><span class="btn btn-primary btn-sm mb-1"><em
                    class="la la-truck"></em></span></a>
        <a href="{{ backpack_url('invoice/' . $entry->id . '/print?mode=customer') }}" class="float-right"
            style="padding-right: 10px;" title="Print invoice for deliver" target="_blank"><span class="btn btn-info btn-sm mb-1"><em
                    class="la la-print"></em></span></a>

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
    <div class="card-header bg-white"><strong>Invoice Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Code</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->code }}</td>
                <td class="border-0" style="width:20%">Reference</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->ref_id }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">QTY</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $details->sum('qty') }}</td>
                <td class="border-0" style="width:20%">Discount Type</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    @if ($entry->discount_all_type == 'per_item')
                        Per Item
                    @endif
                    @if ($entry->discount_all_type == 'per_invoice')
                        Per Invoice
                    @endif
                </td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Total</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ \Str::numberFormatDollar($entry->amount) }}</td>
                <td class="border-0" style="width:20%">Discount</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ \Str::numberFormatDollar($entry->discount_amount) }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Paid</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ \Str::numberFormatDollar($entry->received_amount) }}</td>
                <td class="border-0" style="width:20%">Due</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ \Str::numberFormatDollar($entry->due_amount) }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Branch</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_name }}</td>
                <td class="border-0" style="width:20%">Credit Date</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    {{ !empty($entry->credit_date) ? Carbon::parse($entry->credit_date)->format('d-m-Y') : '' }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Sale Date</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    {{ !empty($entry->invoice_date) ? Carbon::parse($entry->invoice_date)->format('d-m-Y') : '' }}</td>
                <td class="border-0" style="width:20%">Note</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->noted }}</td>
            </tr>
            <tr>
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
                <td class="border-0" style="width:20%">Payment Status</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    <div class='border-outline-status'>
                        @if ($entry->payment_status == 'Partial')
                            <span class='pending'>Partial</span>
                        @elseif ($entry->payment_status == 'Pending')
                            <span class='order'>Pending</span>
                        @elseif ($entry->payment_status == 'Paid')
                            <span class='success'>Paid</span>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Reason</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->status_reason }}</td>
            </tr>
        </table>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#detail" data-toggle="tab">
                            Invoice Detail
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#return" data-toggle="tab">
                            Invoice Return
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="detail">
                    <table class="table table-bordered mt-2" aria-hidden="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Discount</th>
                                <th>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->product_code }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ Helper::formatCurrency($item->total_amount, '$') }}</td>
                                    <td>{{ Helper::formatCurrency($item->discount, '$') }}</td>
                                    <td>{{ Helper::formatCurrency($item->total_payable, '$') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="return">
                    @php 
                        $returns = \App\Models\InvoiceReturn::where('invoice_id', $entry->id)->get();
                    @endphp
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
                            @foreach ($returns as $key => $return)
                                @foreach ($return->productDetails as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ optional($item->product)->product_code }}</td>
                                        <td>{{ optional($item->product)->product_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script>
        var status = $('.status').text();
        if (status == 'Paid') $('.status').addClass('success');
        else if (status == 'Partial') $('.status').addClass('pending');
        else $('.status').addClass('order');
    </script>
@endsection
