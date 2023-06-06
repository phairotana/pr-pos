<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/crud.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/show.css') . '?v=' . config('backpack.base.cachebusting_string') }}">

    <style>
        .image-logo {

            width: 100%;
            /* min-height: 0.9cm;
            max-height: 0.9cm */

        }

        .main-page {
            background: white;
            position: relative;
        }

        .print-header,
        .screen-header .footer {
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

            body,
            .screen-footer {
                padding-top: 20px;
            }

            .screen-margin {
                margin-top: 10px;
            }

            .main-page {
                min-height: 29.7cm;
                margin: 0 auto;
            }

            .body-content {
                padding: 15px;
            }

            div.print-header,
            div.print-footer {
                display: none;
            }

            .table-content {
                min-height: 18cm;
            }

            .font-battambang {
                font-family: Battambang-Regular, var(--font-family-sans-serif) !important;
            }

            .p-screen-2 {
                padding: 0.5rem !important;
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
            font-family: "Battambang-Regular", var(--font-family-sans-serif) !important;
        }


        @media print {

            body,
            .main-page {
                background-color: white;
            }

            .td-width {
                width: 65%
            }

            .font-battambang {
                font-family: Battambang-Regular, var(--font-family-sans-serif) !important;
            }

            .page-break {
                page-break-before: left;
            }

            .table-content {
                min-height: 16cm;
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
                font-family: "Battambang-Regular", var(--font-family-sans-serif) !important;
            }

            .p-screen-2 {
                padding: 0.1rem !important;
            }


        }



        .print-header,
        .screen-header {
            /* border-bottom: 1px solid #aaa; */
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
        .header-container {
            display: flex;
            /* flex-wrap: wrap; */
            gap: 5px;
            margin-bottom: 10px;
        }
        .header-left {
            padding: 10px;
            width: 50%;
            min-width: 305px;
            min-height: 100px;
            border: 1px solid #777;
        }
        .header-right {
            width: 50%;
            padding: 10px;
            min-height: 100px;
            min-width: 305px;
            border: 1px solid #777;
        }
        .detail {
            width: 100%;
            display: flex;
        }
        .detail > div:first-child {
            min-width: 90px;
            white-space: nowrap;
        }
        .detail > div:last-child {
            width: 200px;
            padding-left: 5px;
            word-break: break-all;
        }
    
    </style>
</head>

<body>
    @php
        $i = 1;
        $index = 0;
        $next_page = false;
    @endphp
    <div class="container screen font-battambang">
        <div class="col-lg-12 parent">
            <div class="row">
                @for ($p = 1; $p <= $page; $p++)
                    <div class="col-lg-6 screen-margin">
                        <div class="main-page seller page-break">
                            {{-- <div class="screen-header p-screen-2 mb-4" style="padding: 0 !important;">
                                @if (!empty($entry->branch->profile_image))
                                    <img src="{{ asset($entry->branch->profile_image) }}" alt="logo"
                                        class='image-logo ml-1'>
                                @endif

                            </div> --}}

                            <div class="body-content" style="position:relative">
                                <div class="text-center m-2">
                                    <h2 style="font-family: Moul, var(--font-family-sans-serif) !important">
                                        <strong>Quotation</strong>
                                    </h2>
                                </div>
                                <div class="header-container">
                                    <div class="header-left">
                                        <div class="detail">
                                            <div>អតិថិជន</div>
                                            <div>:</div>
                                            <div>{{ optional($entry->customer)->customer_name }}</div>
                                        </div>
                                        <div class="detail">
                                            <div>លេខទូរស័ព្ទ</div>
                                            <div>:</div>
                                            <div>{{ optional($entry->customer)->customer_phone }}</div>
                                        </div>
                                        <div class="detail">
                                            <div>អាសយដ្ឋាន</div>
                                            <div>:</div>
                                            <div>{{ optional($entry->customer)->customer_address }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header-right">
                                        <div class="detail">
                                            <div>លេខបញ្ជាទិញ</div>
                                            <div style="width: 10%;">:</div>
                                            <div>{{ $entry->ref_id }}</div>
                                        </div>
                                        <div class="detail">
                                            <div>កាលបរិច្ឆេទ</div>
                                            <div>:</div>
                                            <div>
                                                {{ \Carbon\Carbon::parse($entry->invoice_date)->format('d-m-Y') }}
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <div>អ្នកលក់</div>
                                            <div>:</div>
                                            <div>{{ optional($entry->seller)->name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 table-content">
                                    <table class="table table-bordered font-battambang" aria-hidden="true">

                                        <tbody>
                                            <tr class="trth">
                                                <td class="td-border"><strong>Nº</strong></td>
                                                <td class="td-border"><strong>មុខទំនិញ</strong></td>
                                                <td class="td-border"><strong>ខ្នាត</strong></td>
                                                <td class="td-border"><strong>ចំនួន</strong></td>
                                                <td class="td-border"><strong>តម្លៃ</strong></td>
                                                <td class="td-border"><strong>ចុះថ្លៃ</strong></td>
                                                <td class="td-border"><strong>សរុប</strong></td>
                                            </tr>

                                            @for ($item = $index; $item < count($details); $item++)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $details[$item]->product_name }}</td>
                                                    <td>
                                                        @if (!empty($details[$item]->product) && !empty($details[$item]->product->productUnit))
                                                            {{ $details[$item]->product->productUnit->name }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $details[$item]->qty }}</td>
                                                    <td>{{ \Str::numberFormatDollar($details[$item]->sell_price, 3) }}
                                                    </td>

                                                    <td>{{ $details[$item]->DisFtType ?? 0 }}</td>
                                                    <td><strong>{{ \Str::numberFormatDollar($details[$item]->total_payable, 3) }}</strong>
                                                    </td>

                                                </tr>
                                                @if ($p == $page && $i - 1 == count($details))
                                                    @php
                                                        $next_page = true;
                                                        break;
                                                    @endphp
                                                @elseif ($p < $page && $i - 1 == $p * 19)
                                                    @php
                                                        $index = $item + 1;
                                                        break;
                                                    @endphp
                                                @endif
                                            @endfor
                                            @if ($next_page)
                                                @for ($j = $i; $j <= $page * 19 - (9 - $p); $j++)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @if (!$next_page)
                                    <div style="position: absolute;">Page {{ $p . ' of ' . $page }}</div>
                                    <div class="page-break"></div>
                                @elseif ($next_page)
                                    <div class="screen-footer col-md-12 p-1">
                                        <div class="col-12"
                                            style="font-family:Battambang-Regular, var(--font-family-sans-serif) !important">
                                            {{-- Page {{ $p . ' of ' . $page }} --}}
                                            <div class="row mt-2">
                                                <div class="col-6 mb-5">
                                                    <div class="pageNumber"></div>
                                                    <p>សូមពិនិត្យ និងរាប់អោយបានត្រឹមត្រូវ មុននឹងចុះហត្ធលេខាទទួល</p>
                                                    <div class="row">
                                                        <div class="col-6 text-right">
                                                            <p>អ្នកលក់</p>
                                                        </div>
                                                        <div class="col-6 text-right" style="text-align: right;">
                                                            <p>អ្នកទិញ</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <table class="float-r" style="min-width:200px">
                                                        <tr>
                                                            <td>ចុះថ្លៃ :</td>
                                                            <td class="border border-secondary pl-1"><strong>
                                                                    {{ \Str::numberFormatDollar($entry->discount_amount, 3) }}</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>សរុប :</td>
                                                            <td class="border border-secondary pl-1"><strong>
                                                                    {{ \Str::numberFormatDollar($entry->amount_payable, 3) }}</strong>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <script>
        window.print();
    </script>


</body>

</html>
