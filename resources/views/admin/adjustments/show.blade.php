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
    <div class="card-header bg-white"><strong>Adjustment Info</strong></div>
    <div class="box bg-white">
        <table class="table" aria-hidden="true">
            <tr>
                <td class="border-0" style="width:20%">Reference</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->reference }}</td>
                <td class="border-0" style="width:20%">Addition QTY</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $additionQty->sum('quantity') }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Subtraction QTY</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $subtractionQty->sum('quantity') }}</td>
                <td class="border-0" style="width:20%">Total QTY</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $adjustmentDetail->sum('quantity') }}</td>
            </tr>
            <tr>
                <td class="border-0" style="width:20%">Description</td>
                <td class="border-0" style="width:5%">:</td>
                <td class="border-0" style="width:25%">{{ $entry->description }}</td>
            </tr>
        </table>
    </div>
    <div class="card-header bg-white"><strong>Attachments</strong></div>
    <div class="box bg-white">
        <table aria-hidden="true">
            <tr>
                @foreach (json_decode($entry->attachments) ?? [] as $item)
                    <td class="border-0 p-2">
                        @if (Helper::isUrl($item))
                            <a class="example-image-link" href="{{ asset(config('const.filePath.original') . $item) }}"
                                data-lightbox="lightbox-{{ $entry->id }}">
                                <img src="{{ asset(config('const.filePath.original') . $item) }}" alt="" width="80"
                                    style="cursor:pointer" /></a>
                        @else
                            <a class="example-image-link" href="{{ asset(config('const.filePath.original') . $item) }}"
                                data-lightbox="lightbox-{{ $entry->id }}">
                                <img src="{{ asset(config('const.filePath.original') . $item) }}" alt="" width="80"
                                    style="cursor:pointer" /></a>
                        @endif
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
    <div class="card-header bg-white"><strong>Adjustment Detial</strong></div>
    <div class="box bg-white">
        <table class="table table-bordered mt-2" aria-hidden="true">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Type</th>
                    <th>QTY</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adjustmentDetail as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->product_code }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->noted }}</td>
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
