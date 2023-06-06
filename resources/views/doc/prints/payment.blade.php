<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Battambang&family=Moul&family=Siemreap&family=Source+Sans+Pro:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/crud.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <link rel="stylesheet"
        href="{{ asset('packages/backpack/crud/css/show.css') . '?v=' . config('backpack.base.cachebusting_string') }}">
    <style>
        .image-logo {
            width: 100%;
        }
        body, .border-zero, .table td {
            font-size: 13px;
        }
        .page {
            background-color: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .page[size="A4"] {
            width: 21cm;
            min-height: 29.7cm;
        }

        .box-title {
            width: 16rem;
            background-color: rgb(0, 204, 255);
        }

        .title {
            color: rgb(0, 204, 255);
            font-size: 18px;
        }

        .text-rgb {
            color: rgb(0, 204, 255);

        }

        .tp-0 {
            padding-top: 0 !important;

        }

        .moul {
            font-family: 'Moul', cursive;
        }

        .siemreap {
            font-family: 'Siemreap', cursive;
        }

        .battambang {
            font-family: 'Battambang', cursive;
        }


        .f-12 {
            font-size: 24px;
        }

        .san-pro {
            font-family: 'Source Sans Pro', sans-serif;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                background-color: white;
            }

            .head-left {
                width: 50% !important;
            }

            .head-right {
                width: 50% !important;
            }

            .page-break {
                page-break-before: always;
            }

            .col-lg-4 {
                flex: 0 0 auto;
                width: 33.33333333%;
            }
        }

        @media screen {
            /* .page {
                padding-top: 50px !important;
            } */
        }

    </style>
</head>

<body>
    <div class="page p-2" size="A4">
        <div class="col-md-12 text-center margin-screen mb-4">
            @if (!empty($data) && !empty($data[0]->branch))
                <?php
                $branch = $data[0]->branch;
                ?>
            @endif
            @if (!empty($data) && !empty($data[0]->customer))
                <?php
                $customer = $data[0]->customer;
                ?>
            @endif
        </div>
        <div class="col-md-12 text-center margin-screen mb-4">
            <h4 class="moul">
                @if (Session::has('customer'))
                    <u>របាយការណ៍ស្នើរសុំទូទាត់</u>
                @else
                    <u>របាយការណ៍ទូទាត់</u>
                @endif
            </h4>
        </div>

        @foreach ($data as $index => $first_entry)
            @if($index == 1) 
                @break;
            @endif

            <div class="row">
                <div class="col-lg-6 head-left">
                    <table class="table table-bordered border-dark col-12 font-battambang" aria-hidden="true">
                        <tr>
                            <td style="white-space: nowrap;">ក្រុមហ៊ុន</td>
                            <td style="white-space: nowrap;">{{ optional($first_entry->customer)->company }}</td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap;">ឈ្មោះអតិថិជន</td>
                            <td style="white-space: nowrap;">{{ optional($first_entry->customer)->customer_name }}</td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap;">លេខទូរស័ព្ទ</td>
                            <td style="white-space: nowrap;">{{ optional($first_entry->customer)->customer_phone }}</td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap;">អាសយដ្ឋាន</td>
                            <td>{{ optional($first_entry->customer)->customer_address }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 head-right">
                    <table class="table table-bordered border-dark font-battambang" aria-hidden="true">
                        <tr>
                            <td style="white-space: nowrap;">លេខវិក័យបត្រ</td>
                            <td style="white-space: nowrap;">{{ $first_entry->code }}</td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap;">កាលបរិច្ឆេទ</td>
                            <td style="white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($first_entry->invoice_date)->format('d-m-Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap;">អ្នកលក់</td>
                            <td style="white-space: nowrap;">{{ optional($first_entry->seller)->name }}</td>
                        </tr>
                        <tr>
                            <td style="white-space: nowrap;">លេខបញ្ជាទិញ</td>
                            <td>
                                <p
                                    style="width: 100%; border-bottom: 1px dashed #000; margin-bottom: 0; padding-bottom: 0; margin-top: 18px;">
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="colxl-12">
            <table class="table table-bordered border-dark text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $total = 0;
                    $dueAmount = 0;
                    $receivedAmount = 0;
                    ?>
                    @foreach ($data as $invoice)
                        <?php
                        $total += $invoice->amount_payable;
                        $dueAmount += $invoice->due_amount;
                        $receivedAmount += $invoice->received_amount;
                        ?>
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                            <td>{{ $invoice->code }}</td>
                            <td>
                                <table class="san-pro" style="width: 100%;" aria-hidden="true">
                                    <tr>
                                        <td style="width: 25px;"><strong>$</strong></td>
                                        <td style="text-align: right;">
                                            <strong>{{ number_format($invoice->amount_payable, 2) }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    <tr style="border-bottom: none !important">
                        <td style='border:none !important;'></td>
                        <td style='border:none;'></td>
                        <td style="text-align:right;  border-width: 1px 1px; font-size:14px;text-align: center;">
                            <strong class="text-danger">TOTAL</strong>
                        </td>
                        <td style="border-width: 1px 1px;">
                            <table class="text-danger san-pro" style="width: 100%; font-size:14px;" aria-hidden="true">
                                <tr>
                                    <td style="width: 25px;"><strong>$</strong></td>
                                    <td style="text-align: right">
                                        <strong>{{ number_format($total, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: none !important; border-top: 0;">
                        <td style='border:none !important;'></td>
                        <td style='border:none;'></td>
                        <td style="text-align:right;  border-width: 1px 1px; font-size:14px;text-align: center;">
                            <strong class="text-danger">PAID</strong>
                        </td>
                        <td style="border-width: 1px 1px;">
                            <table class="text-danger san-pro" style="width: 100%; font-size:14px;" aria-hidden="true">
                                <tr>
                                    <td style="width: 25px;"><strong>$</strong></td>
                                    <td style="text-align: right">
                                        <strong>{{ number_format($receivedAmount, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: none !important; border-top: 0;">
                        <td style='border:none !important;'></td>
                        <td style='border:none;'></td>
                        <td style="text-align:right;  border-width: 1px 1px; font-size:14px;text-align: center;">
                            <strong class="text-danger">DUE</strong>
                        </td>
                        <td style="border-width: 1px 1px;">
                            <table class="text-danger san-pro" style="width: 100%; font-size:14px;" aria-hidden="true">
                                <tr>
                                    <td style="width: 25px;"><strong>$</strong></td>
                                    <td style="text-align: right">
                                        <strong>{{ number_format($dueAmount, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mt-5 mb-5">
            <div class="col-lg-4 text-center">
                <p style="border-bottom: 1px dashed;padding-bottom: 70px;">Prepared By</p>
            </div>
            <div class="col-lg-4 text-center">
                <p style="border-bottom: 1px dashed;padding-bottom: 70px;">Approved By</p>
            </div>
            <div class="col-lg-4 text-center">
                <p style="border-bottom: 1px dashed;padding-bottom: 70px;">Customer</p>
            </div>
        </div>

        {{-- //------------ Print Invoice ----------------\\ --}}
        <div class="colxl-12">
            @foreach ($data as $entry)
                <div class="row page-break">
                    <div class="col-lg-12 screen-margin">
                        <div class="main-page seller page-break">
                            {{-- <hr> --}}
                            <div class="body-content" style="position:relative">
                                <div class="text-center m-4">
                                    <h3 style="font-family: Moul, var(--font-family-sans-serif) !important">វិក័យបត្រ /
                                        <strong>Invoice</strong>
                                    </h3>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 head-left">
                                        <table class="table table-bordered col-12 font-battambang" aria-hidden="true">
                                            <tr>
                                                <td style="white-space: nowrap;">ក្រុមហ៊ុន</td>
                                                <td style="white-space: nowrap;">
                                                    {{ optional($entry->customer)->company }}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">ឈ្មោះអតិថិជន</td>
                                                <td style="white-space: nowrap;">
                                                    {{ optional($entry->customer)->customer_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">លេខទូរស័ព្ទ</td>
                                                <td style="white-space: nowrap;">
                                                    {{ optional($entry->customer)->customer_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">អាសយដ្ឋាន</td>
                                                <td>{{ optional($entry->customer)->customer_address }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-6 head-right">
                                        <table class="table table-bordered font-battambang" aria-hidden="true">
                                            <tr>
                                                <td style="white-space: nowrap;">លេខវិក័យបត្រ</td>
                                                <td style="white-space: nowrap;">{{ $entry->code }}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">កាលបរិច្ឆេទ</td>
                                                <td style="white-space: nowrap;">
                                                    {{ \Carbon\Carbon::parse($entry->invoice_date)->format('d-m-Y h:i A') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">អ្នកលក់</td>
                                                <td style="white-space: nowrap;">{{ optional($entry->seller)->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">លេខបញ្ជាទិញ</td>
                                                <td>
                                                    <p
                                                        style="width: 100%; border-bottom: 1px dashed #000; margin-bottom: 0; padding-bottom: 0; margin-top: 18px;">
                                                    </p>
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
                                                <td style="background: aliceblue;"><strong>តម្លៃ</strong></td>
                                                <td style="background: aliceblue;"><strong>ចុះថ្លៃ</strong></td>
                                                <td style="background: aliceblue;"><strong>សរុប</strong></td>
                                            </tr>
                                            @foreach ($entry->invoiceDetails as $key => $detail)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $detail->product_name }}</td>
                                                    <td>
                                                        @if (!empty($detail->product) && !empty($detail->product->productUnit))
                                                            {{ $detail->product->productUnit->name }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $detail->qty }}</td>
                                                    <td>
                                                        {{ \Str::numberFormatDollar($detail->sell_price) }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->DisFtTypeShow }}
                                                    </td>
                                                    <td>
                                                        <strong>{{ \Str::numberFormatDollar($detail->total_payable) ?? 0 }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-danger p-2">
                                    <h3
                                        style="font-family: Moul,var(--font-family-sans-serif) !important; text-align:right;">
                                        @if ($entry->payment_status == 'Paid')
                                            ទូទាត់រួច
                                        @elseif($entry->payment_status == 'Partial')
                                            ទូទាត់ខ្លះ
                                        @else
                                            មិនទាន់ទូទាត់
                                        @endif
                                    </h3>

                                </div>
                                <div class="screen-footer col-md-12 p-1">
                                    <div class="col-12"
                                        style="font-family:Battambang-Regular, var(--font-family-sans-serif) !important">
                                        <div class="row mt-2">
                                            <div class="col-6 mb-5">
                                                <div class="pageNumber"></div>
                                                <ul>
                                                    <li>ទំនិញដែលបានជាវរួចពុំអាចប្ដូរវិញបានទេ។</li>
                                                    <li>ទំនិញនៅតែជាកម្មសិទ្ធរបស់ក្រុមហ៊ុនក្នុងករណីអតិថិជនមិនទាន់ទូទាត់អស់។
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>សរុប</td>
                                                        <td style="background: aliceblue;"><strong>
                                                                {{ \Str::numberFormatDollar($entry->amount_payable + $entry->discount_amount) }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>បញ្ចុះតម្លៃ</td>
                                                        <td style="background: aliceblue;"><strong>
                                                                {{ \Str::numberFormatDollar($entry->discount_amount) }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ប្រាក់ត្រូវបង់</td>
                                                        <td style="background: aliceblue;"><strong>
                                                                {{ \Str::numberFormatDollar($entry->amount_payable) }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>កក់មុន</td>
                                                        <td style="background: aliceblue;"><strong>
                                                                {{ \Str::numberFormatDollar($entry->received_amount) }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>នៅខ្វះ</td>
                                                        <td style="background: aliceblue;">
                                                            <strong>
                                                                {{ \Str::numberFormatDollar($entry->due_amount) }}</strong>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 50px;">
                                            <div class="col-4" style="text-align: left;">
                                                <p class="ml-5" style="margin-left: 20%;">អ្នកលក់</p>
                                            </div>
                                            <div class="col-4" style="text-align: center;">
                                                <p class="mr-5" style="margin-left: 20%;">អ្នកដឹក</p>
                                            </div>
                                            <div class="col-4" style="text-align: right;">
                                                <p class="mr-5" style="margin-right: 20%;">អ្នកទិញ</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(function() {
            window.print();
        });
    </script>

</body>

</html>
