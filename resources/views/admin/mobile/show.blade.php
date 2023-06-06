@php
use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('header')
    <section class="container-fluid d-print-none py-3">
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
    <div class="box bg-white">
        <table class="table table-striped">
            <tr>
                <td class="border-0" colspan="3" style="background: #00000075; color: #fff; font-weight: bold;">CUSTOMER INFO</td>
            </tr>
            <tr>
                <td class="border-0">Name</td>
                <td class="border-0">:</td>
                <td class="border-0">{{ optional($entry->user)->name.' '.optional($entry->user)->last_name }}</td>
            </tr>
            <tr>
                <td class="border-0">Phone</td>
                <td class="border-0">:</td>
                <td class="border-0">{{ optional($entry->user)->phone }}</td>
            </tr>
            <tr>
                <td class="border-0">Email</td>
                <td class="border-0">:</td>
                <td class="border-0">{{ optional($entry->user)->email }}</td>
            </tr>
            <tr>
                <td class="border-0">Address</td>
                <td class="border-0">:</td>
                <td class="border-0">{{ optional($entry->user)->address }}</td>
            </tr>
            <tr>
                <td class="border-0" colspan="3" style="background: #00000075; color: #fff; font-weight: bold;">ORDER INFO</td>
            </tr>
            <tr>
                <td class="border-0" colspan="3">
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                        <?php $tQty = 0; $tPrice = 0;?>
                        @foreach ($entry->details as $key => $detail)
                            <?php 
                                $tQty+=$detail->qty;
                                $tPrice += ($detail->price*$detail->qty);
                            ?>
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>Product Name</td>
                                <td>{{ '$'.number_format($detail->price,2) }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>{{ '$'.number_format(($detail->price*$detail->qty),2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <th>{{ $tQty }}</th>
                            <th>{{ '$'.number_format($tPrice,2) }}</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
@endsection