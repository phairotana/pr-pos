<!DOCTYPE html>
<html lang="en">

<head>
    <title>MR HANG::PRINT DELIVERY</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <style>
        .table-border {
            white-space: nowrap;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-border td,
        .table-border th {
            white-space: nowrap;
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-border th {
            white-space: nowrap;
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #0C4CA2;
            color: white;
        }

        .total-info {
            background-color: #4ec4d6;
        }

        .total-last-tr {
            color: white;
            font-weight: bold;
        }

        .footer-description {
            bottom: 0;
            text-align: justify;
            font-size: 13px;
        }

        .footer-description .text-muted {
            color: #777;
        }

        .footer-p2 {
            padding: 5px;
            color: #fff;
            background-color: #0c5aae;
        }

        .khbattambang {
            font-family: 'khmer_siemreap', sans-serif;
        }

        body,
        .screen-footer {
            padding-top: 20px;
        }

        .column {
            float: left;
            width: 25%;
            height: 100px;
            text-align: center;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    @php
    $i = 1;
    @endphp
    <div>
        <h2 style="font-family: kh_moul, sans-serif; text-align:center;">
            ដឹកជញ្ជូន / <strong>DELIVERY</strong>
        </h2>

        <div class="row">
            <div class="col-lg-6 head-right" style="float: right; width: 49%;">
                <table class="table table-border font-battambang" aria-hidden="true">
                    <tr>
                        <td class="khbattambang" style="width: 35%;">លេខបញ្ជាទិញ</td>
                        <td class="khbattambang" style="word-break: break-all;">
                            {{ $entry->code }}
                        </td>
                    </tr>
                    <tr>
                        <td class="khbattambang">អ្នកលក់</td>
                        <td class="khbattambang" style="word-break: break-all;">
                            {{ optional($entry->seller)->name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="khbattambang">កាលបរិច្ឆេទ</td>
                        <td class="khbattambang" style="word-break: break-all;">
                            {{ \Carbon\Carbon::parse($entry->invoice_date)->format('d-m-Y h:i A') }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6 head-left" style="float: left; width: 49%;">
                <table class="table table-border" aria-hidden="true" style="word-break: break-all;">
                    <tr style="word-break: break-all;">
                        <td class="khbattambang" style="width: 35%; word-break: break-all;">
                            អតិថិជន</td>
                        <td class="khbattambang" style="word-break: break-all;">
                            {{ optional($entry->customer)->customer_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="khbattambang">លេខទូរស័ព្ទ</td>
                        <td class="khbattambang">
                            {{ optional($entry->customer)->customer_phone }}
                        </td>
                    </tr>
                    <tr>
                        <td class="khbattambang">អាសយដ្ឋាន</td>
                        <td class="khbattambang" style="word-break: break-all;">
                            {{ optional($entry->customer)->customer_address }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <br>
        <div class="table-content">
            <table class="table table-border font-battambang" aria-hidden="true">

                <tbody>
                    <tr>
                        <td class="khbattambang" style="background: aliceblue;"><strong>Nº</strong></td>
                        <td class="khbattambang" style="background: aliceblue;"><strong>មុខទំនិញ</strong>
                        </td>
                        <td class="khbattambang" style="background: aliceblue;"><strong>ខ្នាត</strong></td>
                        <td class="khbattambang" style="background: aliceblue;"><strong>ចំនួន</strong></td>
                        <td class="khbattambang" style="background: aliceblue;"><strong>ចំណាំ</strong></td>
                    </tr>

                    @php
                    $totalQty = 0;
                    @endphp

                    @for ($item = $i - 1; $item < count($details); $item++) <tr>
                        <td class="khbattambang">{{ $i++ }}</td>
                        <td class="khbattambang">{{ $details[$item]->product_name }}</td>
                        <td class="khbattambang">
                            @if (!empty($details[$item]->product) && !empty($details[$item]->product->productUnit))
                            {{ $details[$item]->product->productUnit->name }}
                            @endif
                        </td>
                        <td class="khbattambang">{{ $details[$item]->qty }}</td>
                        <td class="khbattambang"></td>

                        </tr>
                        @php
                        $totalQty += $details[$item]->qty;
                        @endphp
                        @endfor
                        <tr>
                            <td class="khbattambang" style="text-align: right; font-weight: bold;" colspan="3">សរុបរួម
                            </td>
                            <td class="khbattambang" style="text-align: center; font-weight: bold;" colspan="2">{{$totalQty}}</td>
                        </tr>

                </tbody>
            </table>
        </div>

        <div class="screen-footer">
            <div class="row">
                <div class="col-6 mb-5" style="float: left; width: 49%;">
                    <div class="pageNumber"></div>
                    <ul class="khbattambang">
                        <li>ទំនិញដែលបានជាវរួចពុំអាចប្ដូរវិញបានទេ។</li>
                        <li>ទំនិញនៅតែជាកម្មសិទ្ធរបស់ក្រុមហ៊ុនក្នុងករណីអតិថិជនមិនទាន់ទូទាត់អស់។
                        </li>
                    </ul>

                </div>
            </div>

            <div class="row khbattambang">
                <div class="column">
                    <p>អ្នកលក់</p>
                </div>
                <div class="column">
                    <p>អ្នកកាន់ឃ្លាំង</p>
                </div>
                <div class="column">
                    <p>អ្នកដឹក</p>
                </div>
                <div class="column">
                    <p>អ្នកទិញ</p>
                </div>
            </div>
        </div>

    </div>
</body>



</html>