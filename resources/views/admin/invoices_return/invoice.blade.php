@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.preview') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('content')
    @php
    use Carbon\Carbon;
    $i = 1;
    @endphp
    <div class="container screen font-battambang">
        <div class="col-lg-12 parent">
            <div class="row">
                <div class="col-md-6">
                    <div class=" btn-print-seller">
                        <span class="btn btn-info btn-sm">
                            <em class="la la-print"></em>
                        </span>
                    </div>
                    <div class="main-page seller">
                        <div class="screen-header p-2">
                            @if (!empty($entry->branch->profile_image))
                                <img src="{{ asset($entry->branch->profile_image) }}" alt="logo" class='image-logo ml-1'>
                            @endif

                        </div>

                        <div class="body-content">
                            <div class="text-center m-4">
                                <h2 style="font-family: Moul !important">វិក័យបត្រ / <strong>Invoice</strong></h2>
                            </div>

                            <table style="width: 100%; margin-bottom:15px;">
                                <tr>
                                    <td class="td-width">
                                        <div class="col-sm-12">
                                            <div style="width: 100%; padding-left:10px;" class="border border-secondary">
                                                <table class="col-12 font-battambang">
                                                    <tr>
                                                        <td style="width: 10%">អតិថិជន</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->customer)->customer_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ទូរស័ព្ទ</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->customer)->customer_phone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>អាសយដ្ឋាន</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->customer)->customer_address }}</td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>

                                    </td>
                                    <td>
                                        <div class="col-sm-12">
                                            <div style="width: 100%; padding-left:10px;" class="border border-secondary">

                                                <table class="font-battambang">
                                                    <tr>
                                                        <td>Nº</td>
                                                        <td style="width: 10%;">:</td>
                                                        <td>{{ $entry->ref_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>កាលបរិច្ឆេទ</td>
                                                        <td>:</td>
                                                        <td>
                                                            {{ Carbon::parse($entry->invoice_date)->format('d-m-Y') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>អ្នកលក់</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->seller)->name }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>

                                    </td>
                                </tr>
                            </table>
                            <div class="col-lg-12 table-content">
                                <table class="table table-bordered font-battambang">

                                    <tbody>
                                        <tr class="trth">
                                            <td class="td-border"><strong>Nº</strong></td>
                                            <td class="td-border"><strong>មុខទំនិញ</strong></td>
                                            <td class="td-border"><strong>ខ្នាត</strong></td>
                                            <td class="td-border"><strong>ចំនួន</strong></td>
                                            <td class="td-border"><strong>តម្លៃ($)</strong></td>
                                            <td class="td-border"><strong>ចុះថ្លៃ</strong></td>
                                            <td class="td-border"><strong>សរុប($)</strong></td>
                                        </tr>

                                        @foreach ($details as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ optional($item->product)->product_name }}</td>
                                                <td>
                                                    @if(!empty($item->product) && !empty($item->product->productUnit))
                                                        {{ $item->product->productUnit->name }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ $item->sell_price ?? 0 }}</td>

                                                <td>{{ $item->discount_amount ?? 0 }}</td>
                                                <td><strong>1{{ $item->total ?? 0 }}</strong> </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="text-danger p-2" style="float: right;">
                                <h2 style="font-family: Moul !important;"">
                                                                  @if ($entry->payment_status == 'Paid')
                                    ទូទាត់រួច
                                @elseif($entry->payment_status == 'Partial')
                                    ទូទាត់ខ្លះ
                                @else
                                    មិនទាន់ទូទាត់
                                    @endif
                                </h2>

                            </div>

                        </div>
                        <div class="screen-footer col-md-12 p-1">

                            <div class="col-12" style="font-family:Battambang-Regular !important">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="pageNumber"></div>
                                        <p>សូមពិនិត្យ និងរាប់អោយបានត្រឹមត្រូវ មុននឹងចុះហត្ធលេខាទទួល</p>

                                        <div class="row">
                                            <div class="col-6 text-right">
                                                <p>អ្នកលក់</p>
                                            </div>
                                            <div class="col-6 text-right mb-5">
                                                <p>អ្នកដឹក</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <table class="float-r" style="min-width:200px">
                                            <tr>
                                                <td>ចុះថ្លៃ :</td>
                                                <td class="border border-secondary pl-1"><strong> $
                                                        {{ number_format($entry->discount_amount, 2, '.', ',') ?? '0.00' }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>សរុប :</td>
                                                <td class="border border-secondary pl-1"><strong> $
                                                        {{ number_format($entry->amount, 2, '.', ',') ?? '0.00' }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>កក់មុន :</td>
                                                <td class="border border-secondary pl-1"><strong> $
                                                        {{ number_format($entry->received_amount, 2, '.', ',') ?? '0.00' }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>នៅខ្វះ :</td>
                                                <td class="border border-secondary pl-1" style="min-width:100px;"><strong> $
                                                        {{ number_format($entry->amount - $entry->received_amount, 2, '.', ',') ?? '0.00' }}</strong>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-md-6 ">
                    <div class="btn-print-delivery">

                        <span class=" btn btn-info btn-sm">
                            <em class="la la-print"></em>
                        </span>

                    </div>
                    <div class="main-page delivery">
                        <div class="screen-header p-2">
                            @if (!empty($entry->branch->profile_image))
                                <img src="{{ asset($entry->branch->profile_image) }}" alt="logo" class='image-logo ml-1'>
                            @endif
                        </div>

                        <div class="body-content">
                            <div class="text-center m-4">
                                <h2 style="font-family: Moul !important">វិក័យបត្រ / <strong>Invoice</strong></h2>
                            </div>

                            <table style="width: 100%; margin-bottom:15px;">
                                <tr>
                                    <td class="td-width">
                                        <div class="col-sm-12">
                                            <div style="width: 100%; padding-left:10px;" class="border border-secondary">
                                                <table class="col-12 font-battambang">
                                                    <tr>
                                                        <td style="width: 10%">អតិថិជន</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->customer)->customer_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ទូរស័ព្ទ</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->customer)->customer_phone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>អាសយដ្ឋាន</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->customer)->customer_address }}</td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>

                                    </td>
                                    <td>
                                        <div class="col-sm-12">
                                            <div style="width: 100%; padding-left:10px;" class="border border-secondary">

                                                <table class="font-battambang">
                                                    <tr>
                                                        <td>Nº</td>
                                                        <td style="width: 10%;">:</td>
                                                        <td>{{ $entry->ref_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>កាលបរិច្ឆេទ</td>
                                                        <td>:</td>
                                                        <td>
                                                            {{ Carbon::parse($entry->invoice_date)->format('d-m-Y') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>អ្នកលក់</td>
                                                        <td>:</td>
                                                        <td>{{ optional($entry->seller)->name }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>

                                    </td>
                                </tr>
                            </table>
                            <div class="col-lg-12 table-content">
                                @php
                                    $i = 1;
                                @endphp
                                <table class="table table-bordered font-battambang">
                                    <tbody>
                                        <tr class="trth">
                                            <td class="td-border"><strong>Nº</strong></td>
                                            <td class="td-border"><strong>មុខទំនិញ</strong></td>
                                            <td class="td-border"><strong>ខ្នាត</strong></td>
                                            <td class="td-border"><strong>ចំនួន</strong></td>
                                          
                                        </tr>

                                        @foreach ($details as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->product->product_name }}</td>
                                                <td>{{ $item->product->productUnit->name ?? '' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="text-danger p-2" style="float: right;">
                                <h2 style="font-family: Moul !important;"">
                                                   @if ($entry->payment_status == 'Paid')
                                    ទូទាត់រួច
                                @elseif($entry->payment_status == 'Partial')
                                    ទូទាត់ខ្លះ
                                @else
                                    មិនទាន់ទូទាត់
                                    @endif
                                </h2>

                            </div>

                        </div> --}}
                        <div class="screen-footer col-md-12 p-1">
                            <div class="col-12" style="font-family:Battambang-Regular !important">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="pageNumber"></div>

                                        <p>សូមពិនិត្យ និងរាប់អោយបានត្រឹមត្រូវ មុននឹងចុះហត្ធលេខាទទួល</p>

                                        <div class="row">
                                            <div class="col-4 text-right">
                                                <p>អ្នកទទួល</p>
                                            </div>
                                            <div class="col-8 text-right mb-5">
                                                <p>អ្នកដឹក</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('after_styles')
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/crud.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/show.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <style>
        .image-logo {
            width: 100%;
        }

        .main-page {
            /* min-height: 200mm; */
            background: white;
            box-shadow: 0 0 0.5cm rgba(202, 202, 202, 0.5);
            position: relative;

        }

        .print-header,
        .screen-header .footer {
            padding: 10px;
            width: 100%;
            background: #eee;
        }

        @media screen {
            div.print {
                display: none;
            }

            .td-width {
                width: 55%
            }


            div.print-header,
            div.print-footer {
                display: none;
            }

            .table-content {
                min-height: 100mm;
            }
            .font-battambang{
                font-family: Battambang-Regular !important;
            }

        }

        a,
        p,
        h1,
        .h1,
        h2,
        .h2,
        h4,
        .h4,
        h5,
        .h5,
        h6,
        .h6 {
            font-family: "Battambang-Regular" !important;
        }


        @media print {
            .table-content {
                min-height: 200mm;
            }

            .td-width {
                width: 70%
            }

            .pageNumber:after {
                counter-increment: page;
                content: "Page "counter(page) "of 1";

            }
            .font-battambang{
                font-family: Battambang-Regular !important;
            }


            a,
            p,
            h1,
            .h1,
            h2,
            .h2,
            h4,
            .h4,
            h5,
            .h5,
            h6,
            .h6 {
                font-family: "Battambang-Regular" !important;
            }

        }


        .print-header,
        .screen-header {
            border-bottom: 1px solid #aaa;
        }



        .print-footer,
        .screen-footer {
            border-top: 1px solid #aaa;
        }

        .parent {
            position: relative;
        }

        .screen-footer {
            display: flex;
            /* position: absolute; */
            bottom: 0;
        }

        table.float-r {
            float: right;
            margin-right: 15px;
        }

        .body-content {
            z-index: 2;
        }

        .trth {
            background-color: #f1f4f8 !important;
            border: 2px solid #aaa !important;
        }

        .td-border {
            border-right: 2px solid #aaa !important;
            border-left: 2px solid #aaa !important;
        }

    </style>

@endsection

@section('after_scripts')
    <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <script>
        $(".btn-print-seller").on('click', function() {
            $(".seller").print();
        });
        $(".btn-print-delivery").on('click', function() {
            $(".delivery").print();
        });
        jQuery(document).bind("keyup keydown", function(e) {
            if (e.ctrlKey && e.keyCode == 80) {
                // $(".to-print").print();
                return false;
            }
        });
    </script>
@endsection
