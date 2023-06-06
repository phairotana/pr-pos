<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delivery</title>
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
                padding: 0px 15px 15px 15px;
            }

            div.print-header,
            div.print-footer {
                display: none;
            }

            .table-content {
                min-height: 15cm;
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
                width: 70%
            }

            .font-battambang {
                font-family: Battambang-Regular, var(--font-family-sans-serif) !important;
            }

            .page-break {
                page-break-before: left;
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
            .head-left{
                width: 50% !important;
            }
            .head-right{
                width: 50% !important;
            }
        }

        .print-header,
        .screen-header {
            /* border-bottom: 1px solid #aaa; */
        }



        .print-footer,
        .screen-footer {
            /* border-top: 1px solid #aaa; */
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
</head>

<body>
    @php
        $i = 1;
        $index = 0;
        $next_page = false;
        $p_empty_19 = false;
        $p_empty_11 = false;
        $foot = false;
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

                            {{-- <hr> --}}
                            <div class="body-content" style="position:relative">
                                <div class="text-center m-4">
                                    <h2 style="font-family: Moul, var(--font-family-sans-serif) !important">ដឹកជញ្ជូន / <strong>Delivery</strong></h2>

                                    </h2>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 head-left">
                                        <table class="table table-bordered col-12 font-battambang" aria-hidden="true">
                                            <tr>
                                                <td style="width: 10%">អតិថិជន</td>
                                                <td style="word-break: break-all;">{{ optional($entry->customer)->customer_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>លេខទូរស័ព្ទ</td>
                                                <td style="word-break: break-all;">{{ optional($entry->customer)->customer_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>អាសយដ្ឋាន</td>
                                                <td style="word-break: break-all;">{{ optional($entry->customer)->customer_address }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-6 head-right">
                                        <table class="table table-bordered font-battambang" aria-hidden="true">
                                            <tr>
                                                <td>លេខបញ្ជាទិញ</td>
                                                <td style="word-break: break-all;">{{ $entry->code }}</td>
                                            </tr>
                                            <tr>
                                                <td>អ្នកលក់</td>
                                                <td style="word-break: break-all;">{{ optional($entry->seller)->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>កាលបរិច្ឆេទ</td>
                                                <td style="word-break: break-all;">
                                                    {{ \Carbon\Carbon::parse($entry->invoice_date)->format('d-m-Y h:i A') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 table-content">
                                    <table class="table table-bordered font-battambang" aria-hidden="true">

                                        <tbody>
                                            <tr>
                                                <td style="background: aliceblue;"><strong>Nº</strong></td>
                                                <td style="background: aliceblue;"><strong>មុខទំនិញ</strong></td>
                                                <td style="background: aliceblue;"><strong>ខ្នាត</strong></td>
                                                <td style="background: aliceblue;"><strong>ចំនួន</strong></td>
                                                <td style="background: aliceblue;"><strong>ចំណាំ</strong></td>
                                            </tr>
                                           
                                            @for ($item = $i -1; $item < count($details); $item++)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $details[$item]->product_name }}</td>
                                                    <td>
                                                        @if (!empty($details[$item]->product) && !empty($details[$item]->product->productUnit))
                                                            {{ $details[$item]->product->productUnit->name }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $details[$item]->qty }}</td>
                                                    <td></td>

                                                </tr>
                                                @if ($i == count($details) && $p == $page)
                                                    @php
                                                        $foot = true;
                                                    @endphp
                                                @endif
                                                @if ($item == 19 * $p - 1)
                                                    @php
                                                        break;
                                                    @endphp
                                                @elseif ($p < $page && $item == 10 + 19 * ($p - 1))
                                                    @php
                                                        $p_empty_19 = true;
                                                    @endphp
                                                @elseif($item < 11 + 19 * ($p - 1))
                                                    @php
                                                        $p_empty_11 = true;
                                                    @endphp
                                                @endif
                                            @endfor

                                            @if ($p_empty_19)
                                                @php
                                                    $p_empty_19 = false;
                                                    
                                                @endphp
                                                @for ($j = $i; $j <= 19 * $p; $j++)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                       
                                                       
                                                    </tr>
                                                @endfor
                                            @elseif($p_empty_11)
                                                @php
                                                    $p_empty_11 = false;
                                                    $foot = true;
                                                @endphp
                                                @for ($j = $i; $j <= 11 + 19 * ($p - 1); $j++)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
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

                                @if (!$foot)
                                    {{-- <div style="position: absolute;">Page {{ $p . ' of ' . $page }}</div> --}}
                                    <div class="page-break"></div>
                                @elseif ($foot)
                                    <div class="screen-footer col-md-12 p-1">
                                        <div class="col-12"
                                            style="font-family:Battambang-Regular, var(--font-family-sans-serif) !important">
                                            {{-- Page {{ $p . ' of ' . $page }} --}}
                                            <div class="row">
                                                <div class="col-12 mb-5">
                                                    <div class="pageNumber"></div>
                                                    <ul>
                                                        <li>ទំនិញដែលបានជាវរួចពុំអាចប្ដូរវិញបានទេ។</li>
                                                        <li>ទំនិញនៅតែជាកម្មសិទ្ធរបស់ក្រុមហ៊ុនក្នុងករណីអតិថិជនមិនទាន់ទូទាត់អស់។</li>
                                                    </ul>
                                                    <div class="row">
                                                        <div class="col-6 text-right">
                                                            <p>អ្នកលក់</p>
                                                        </div>
                                                        <div class="col-6 text-right" style="text-align: right; padding-right: 20%;">
                                                            <p>អ្នកទទួល</p>
                                                        </div>
                                                    </div>
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
