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
        @if ($crud->hasAccess('list'))
            <button class="btn btn-success convert-invoice-btn float-right btn-sm">Convert
                to invoice</button>
        @endif
        <a href="{{ backpack_url('quotation/' . $entry->id . '/print?mode=customer') }}" class="float-right"
            style="padding-right: 10px;" title="Print invoice for deliver" target="_blank"><span
                class="btn btn-info btn-sm mb-1"><em class="la la-print"></em></span></a>
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

    <div class="card-header bg-white"><strong>Company Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Branch</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_name }}</td>
                <td class="border-0" style="width:20%">Phone</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_phone }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Email</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_email }}</td>
                <td class="border-0" style="width:20%">Address</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ optional($entry->branch)->branch_address }}</td>
            </tr>
        </table>
    </div>

    <div class="card-header bg-white"><strong>Quotations Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Reference</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->ref_id }}</td>
                <td class="border-0" style="width:20%">Quotation Date</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    {{ Carbon::parse($entry->quotation_date)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Grand Total</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ Helper::formatCurrency($entry->amount, '$') }}</td>
                <td class="border-0" style="width:20%">Status</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">
                    <a class="border-outline-status"> <span
                            class="{{ $entry->status == 'Sent' ? 'success' : 'order' }}">{{ $entry->status }}</span></a>
                </td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Reason</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->status_reason }}</td>
            </tr>
        </table>
    </div>

    <div class="card-header bg-white"><strong>Quotation Detial</strong></div>
    <div class="box bg-white">
        <table class="table table-bordered mt-2" aria-hidden="true">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Product unit</th>
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
                        <td>{{ optional(optional($item->product)->productUnit)->name }}</td>
                        <td>{{ Helper::formatCurrency(($item->qty*$item->sell_price), '$') }}</td>
                        <td>
                            @if($item->dis_type == 'percent' && $item->discount > 0)
                                {{ $item->discount }}%
                            @elseif($item->discount > 0)
                                {{ Helper::formatCurrency($item->discount, '$') }}
                            @endif
                        </td>
                        <td>
                            {{ Helper::formatCurrency($item->total_payable, '$') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('after_styles')
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/crud.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/show.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
@endsection

@section('after_scripts')
    <script src="{{ asset('packages/backpack/crud/js/crud.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script src="{{ asset('packages/backpack/crud/js/show.js') . '?v=' . config('backpack.base.cachebusting_string') }}">
    </script>
    <script>
        $(document).ready(function() {
            $convertButton = $('.convert-invoice-btn')
            $isDisableEntryConvert = "{{ $entry->is_already_convert }}"



            $convertButton.on('click', function() {
                $quoatId = "{{ $entry->id }}"
                $button = this

                if ($isDisableEntryConvert == 1) {
                    swal({
                        text: "Whoops, This quotaion've already been converted, cannot convert again",
                        title: "Warning!",
                        icon: "error"
                    })
                } else {

                    swal({
                            title: "Warning!",
                            text: "Are you sure to convert this Quotations?",
                            icon: "warning",
                            buttons: {
                                cancel: {
                                    text: "No",
                                    value: null,
                                    visible: true,
                                    className: "bg-secondary",
                                    closeModal: true,
                                },
                                restore: {
                                    text: "Yes, convert now",
                                    value: true,
                                    visible: true,
                                    className: "bg-success",
                                }
                            },
                        })
                        .then(async (value) => {
                            if (value) {
                                const response = await axios.post(
                                    `{{ backpack_url('quotations/convert/${$quoatId}') }}`)
                                if (response.status == 200) {
                                    swal({
                                        title: "Success",
                                        text: "You already convert quotation to invoice!",
                                        icon: "success"
                                    });
                                    $($button).prop('disabled', true)
                                }
                            } else {
                                return;
                            }
                        });
                }
            })
        })
    </script>
@endsection
