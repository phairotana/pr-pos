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
                <a href="/admin/stockadjust/export/excel/{{ $entry->id }}" class="btn-slide">
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
                    <label for="colFormLabelSm" class="col-sm-2 col-form-label fw-bold">ADJUST NO</label>
                    <div class="col-sm-10">
                        <p id="colFormLabelSm"><span>: </span> {{ $entry->adjust_no}}</p>
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
    <div class="table-responsive">
        <table id="stockadjusts">

            <thead>
                <tr class="text-nowrap">
                    <th>#</th>
                    <th>ITEM</th>
                    <th>QTY BEFORE</th>
                    <th>SI BEFORE</th>
                    <th>SO BEFORE</th>
                    <th>QTY MOVEMENT</th>
                    <th>SI MOVEMENT</th>
                    <th>SO MOVEMENT</th>
                    <th>QTY AFTER</th>
                    <th>SI AFTER</th>
                    <th>SO AFTER</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details ?? [] as $index=>$item)
                <tr>
                    <td class="col col-1 fw-bold" data-label="#">{{ $index + 1 }}</td>
                    <td class="col col-2 fw-bold" data-label="ITEM">{{ $item->itemName($item->product_id) }}</td>
                    <td class="col col-3 text-info" data-label="QTY BEFORE">{{ $item->qty_before }}</td>
                    <td class="col col-4 text-info" data-label="SI BEFORE">{{ $item->si_before }}</td>
                    <td class="col col-5 text-info" data-label="SO BEFOR">{{ $item->so_before }}</td>
                    <td class="col col-7 text-danger" data-label="QTY MOVEMENT">{{ $item->qty_movement > 0 ? "+$item->qty_movement" : $item->qty_movement }}</td>
                    <td class="col col-8 text-danger" data-label="SI MOVEMENT">{{ $item->si_movement }}</td>
                    <td class="col col-9 text-danger" data-label="SO MOVEMENT">{{ $item->so_movement }}</td>
                    <td class="col col-10 text-success" data-label="QTY AFTER">{{ $item->qty_after }}</td>
                    <td class="col col-11 text-success" data-label="SI AFTER">{{ $item->si_after }}</td>
                    <td class="col col-12 text-success" data-label="SO AFTER">{{ $item->so_after }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection

@push('after_styles')
<style>
    body {
        font-family: "lato", sans-serif;
    }

    /* S1 */
    #stockadjusts {
        border-collapse: collapse;
        width: 100%;
    }

    #stockadjusts th,
    #stockadjusts td {
        font-size: small;
        border: 1px solid #ddd;
    }

    #stockadjusts tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #stockadjusts tr:hover {
        background-color: #ddd;
    }

    #stockadjusts th {
        font-size: small;
        background-color: #467fd0;
        color: white;
        padding: 10px;
    }

    /* S2 */
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
    $('body').removeClass('sidebar-lg-show');
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