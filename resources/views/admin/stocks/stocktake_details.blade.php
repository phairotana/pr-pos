@extends('layouts.app')
@php
$defaultBreadcrumbs = [
'Admin' => url(config('backpack.base.route_prefix'), 'dashboard'),
$crud->entity_name_plural => url($crud->route),
trans('flexi_crud.list') => false,
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

<div class="container-fruit">
    <div class="p-0"> <input id="information" class="toggle" type="checkbox"> <label for="information" class="lbl-toggle" tabindex="0">
            Information
            <span class="float-end">
                <a href="/admin/stocktake/export/excel/{{ $entry->id }}" class="btn-slide">
                    <span class="circle"><i class="la la-download"></i></span>
                    <span class="title">EXCEL</span>
                    <span class="title-hover">CLICK</span>
                </a>
                <!-- <a href="/admin/stocktake/export/pdf/{{ $entry->id }}" class="btn-slide2">
                    <span class="circle2"><i class="la la-download"></i></span>
                    <span class="title2">PDF</span>
                    <span class="title-hover2">CLICK</span>
                </a> -->
            </span>

        </label>
        <div class="collapsible-content">
            <div class="content-inner">
                <div class="row">
                    <label for="colFormLabelSm" class="col-sm-2 col-form-label fw-bold">STK NO</label>
                    <div class="col-sm-10">
                        <p id="colFormLabelSm"><span>: </span> {{ $entry->stk_no}}</p>
                    </div>
                </div>
                <div class="row">
                    <label for="colFormLabelSm" class="col-sm-2 col-form-label fw-bold">Action By</label>
                    <div class="col-sm-10">
                        <p id="colFormLabelSm"><span>: </span> {{ $entry->createdBy}}</p>
                    </div>
                </div>
                <div class="row">
                    <label for="colFormLabel" class="col-sm-2 col-form-label fw-bold">Action Date</label>
                    <div class="col-sm-10">
                        <p id="colFormLabelSm"><span>: </span> {{ Carbon\Carbon::parse($entry->created_at)->format('d-m-Y, h:i:s A') }}</p>
                    </div>
                </div>
                <div class="row">
                    <label for="colFormLabelLg" class="col-sm-2 col-form-label fw-bold">Remarts</label>
                    <div class="col-sm-10">
                        <p id="colFormLabelSm"><span>: </span> {{ $entry->remarks }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul class="responsive-table">
        <li class="table-header fw-bold">
            <div class="col col-1">#</div>
            <div class="col col-2">Categories</div>
            <div class="col col-3">Items</div>
            <div class="col col-4">Expected</div>
            <div class="col col-5">Counted</div>
            <div class="col col-6">Difference</div>
            <div class="col col-7">Noted</div>
        </li>

        @foreach($details ?? [] as $index=>$item)
        <li class="table-row fw-bold">
            <div class="col col-1" data-label="#">{{ $index + 1 }}</div>
            <div class="col col-2" data-label="Categories">{{ $item->CategoryName }}</div>
            <div class="col col-3" data-label="Items">{{ $item->itemName($item->product_id) }}</div>
            <div class="col col-4 text-success" data-label="Expected">{{ $item->expected }}</div>
            <div class="col col-5 text-info" data-label="Counted">{{ $item->counted }}</div>
            @if($item->difference == 0)
            <div class="col col-6 text-primary" data-label="Difference">
                <em class="la la-check la-lg fw-bolder" aria-hidden="true"></em>
            </div>
            @elseif($item->difference > 0)
            <div class="col col-6 text-warning" data-label="Difference">+{{ $item->difference }}</div>
            @else
            <div class="col col-6 text-danger" data-label="Difference">{{ $item->difference }}</div>
            @endif
            <div class="col col-7" data-label="Noted">{{ $item->note }}</div>
        </li>
        @endforeach
    </ul>
</div>

@endsection

@push('after_styles')
<style>
    body {
        font-family: "lato", sans-serif;
    }

    input[type='checkbox'] {
        display: none;
    }

    .lbl-toggle {
        display: block;
        font-weight: bold;
        font-family: monospace;
        font-size: 1.2rem;
        text-transform: uppercase;
        padding: 0.4rem;
        color: #DDD;
        background: #467fd0;
        cursor: pointer;
        border-radius: 2px;
        transition: all 0.25s ease-out;
    }

    .lbl-toggle:hover {
        color: #FFF;
    }

    .lbl-toggle::before {
        content: ' ';
        display: inline-block;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 5px solid currentColor;
        vertical-align: middle;
        margin-right: .7rem;
        transform: translateY(-2px);
        transition: transform .2s ease-out;
    }

    .toggle:checked+.lbl-toggle::before {
        transform: rotate(90deg) translateX(-3px);
    }

    .collapsible-content {
        max-height: 0px;
        overflow: hidden;
        transition: max-height .25s ease-in-out;
    }

    .toggle:checked+.lbl-toggle+.collapsible-content {
        max-height: 350px;
    }

    .toggle:checked+.lbl-toggle {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .collapsible-content .content-inner {
        background: #fff;
        margin-bottom: 15px;
        padding: .5rem 1rem;
        border-bottom-left-radius: 2px;
        border-bottom-right-radius: 3px;
    }

    .responsive-table {
        padding: 0;
    }

    .responsive-table li {
        border-radius: 2px;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .responsive-table .table-header {
        color: #ffffff;
        background-color: #467fd0;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .responsive-table .table-row {
        background-color: #ffffff;
        box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.1);
    }

    .responsive-table .col-1 {
        flex-basis: 5%;
    }

    .responsive-table .col-2 {
        flex-basis: 30%;
    }

    .responsive-table .col-3 {
        flex-basis: 35%;
    }

    .responsive-table .col-4 {
        flex-basis: 15%;
    }

    .responsive-table .col-5 {
        flex-basis: 15%;
    }

    .responsive-table .col-6 {
        flex-basis: 15%;
    }

    .responsive-table .col-7 {
        flex-basis: 30%;
    }


    @media all and (max-width: 767px) {
        .responsive-table .table-header {
            display: none;
        }

        .responsive-table li {
            display: block;
        }

        .responsive-table .col {
            flex-basis: 100%;
        }

        .responsive-table .col {
            display: flex;
            padding: 10px 0;
        }

        .responsive-table .col:before {
            color: #6c7a89;
            padding-right: 10px;
            content: attr(data-label);
            flex-basis: 50%;
            text-align: right;
        }
    }


    /* Button Download Excle & PDF */
    .btn-slide,
    .btn-slide2 {
        position: relative;
        display: inline-block;
        height: 30px;
        width: 75px;
        padding: 2px;
        border-radius: 5px;
        background: #fdfdfd;
        border: 1px solid #ffc107;
        margin: 4px 0px;
        transition: .5s;
    }

    .btn-slide2 {
        border: 1px solid #efa666;
    }

    .btn-slide:hover {
        background-color: #ffc107;
    }

    .btn-slide2:hover {
        background-color: #efa666;
    }

    .btn-slide:hover span.circle,
    .btn-slide2:hover span.circle2 {
        left: 100%;
        margin-left: -25px;
        background-color: #fdfdfd;
        color: #0099cc;
    }

    .btn-slide2:hover span.circle2 {
        color: #efa666;
    }

    .btn-slide:hover span.title,
    .btn-slide2:hover span.title2 {
        left: 40px;
        opacity: 0;
    }

    .btn-slide:hover span.title-hover,
    .btn-slide2:hover span.title-hover2 {
        opacity: 1;
        left: 9px;
    }

    .btn-slide span.circle,
    .btn-slide2 span.circle2 {
        display: block;
        background-color: #ffc107;
        color: #fff;
        position: absolute;
        float: left;
        margin: 4px;
        line-height: 20px;
        height: 20px;
        width: 20px;
        top: 0;
        left: 0;
        transition: .5s;
        border-radius: 50%;
    }

    .btn-slide2 span.circle2 {
        background-color: #efa666;
    }

    .btn-slide span.title,
    .btn-slide span.title-hover,
    .btn-slide2 span.title2,
    .btn-slide2 span.title-hover2 {
        position: absolute;
        left: 30px;
        text-align: center;
        font-size: 12px;
        font-weight: bold;
        color: #ffc107;
        transition: .5s;
    }

    .btn-slide2 span.title2,
    .btn-slide2 span.title-hover2 {
        color: #efa666;
        left: 35px;
    }

    .btn-slide span.title-hover,
    .btn-slide2 span.title-hover2 {
        left: 40px;
        opacity: 0;
    }

    .btn-slide span.title-hover,
    .btn-slide2 span.title-hover2 {
        color: #fff;
    }
</style>
@endpush

@push('crud_fields_scripts')

<script>
    let myLabels = document.querySelectorAll('.lbl-toggle');
    Array.from(myLabels).forEach(label => {
        label.addEventListener('keydown', e => {
            if (e.which === 32 || e.which === 13) {
                e.preventDefault();
                label.click();
            };
        });
    });
</script>
@endpush