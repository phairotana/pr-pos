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
                <td class="border-0" style="width:25%">{{ $supplier->supplier_code }}</td>
                <td class="border-0" style="width:20%">Name</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $supplier->supplier_name }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Phone</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $supplier->supplier_phone }}</td>
                <td class="border-0" style="width:20%">Email</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $supplier->supplier_email }}</td>
            </tr>
        </table>
    </div>
    <div class="card-header bg-white"><strong>Purchase Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Reference</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->ref_id }}</td>
                <td class="border-0" style="width:20%">QTY</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $purchase_detail->sum('qty') }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Return Date</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    {{ !empty($entry->purchase_return_date) ? Carbon::parse($entry->purchase_return_date)->format('d-m-Y') : '' }}
                </td>
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
                <td class="border-0" style="width:20%">Paid Amount</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ \Str::numberFormatDollar($entry->amount_payable) }}</td>
                <td class="border-0" style="width:20%">Due Amount</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ \Str::numberFormatDollar($entry->due_amount) }}</td>
            </tr>

            <tr>
                <td class="border-0" style="width:20%">Status</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">

                    <div class='border-outline-status'>
                        @if ($entry->purchase_status == 'Pending')
                            <span class='order'>Pending</span>
                        @elseif ($entry->purchase_status == 'Partial Receive')
                            <span class='pending'>Partial Received</span>"
                        @elseif ($entry->purchase_status == 'Receive')
                            <span class='success'>Received</span>
                        @endif
                    </div>
                </td>
                <td class="border-0" style="width:20%">Note</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->purchase_return_note }}</td>
            </tr>
        </table>
    </div>

    <div class="card-header bg-white"><strong>Return Detial</strong></div>
    <div class="box bg-white">
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
                @foreach ($purchase_detail as $key => $item)
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
@endsection


@section('after_styles')
@endsection

@section('after_scripts')
@endsection
