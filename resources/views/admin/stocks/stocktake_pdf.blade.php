<!DOCTYPE html>
<html lang="en">

<head>
    <title>STOCK TAKE :: MR HANG</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <style>
        .transaction-line-height {
            font-size: 14px;
            line-height: 28px
        }

        .logo-cic {
            width: 140px;
            margin-top: 0px;
            margin-bottom: 6px;
        }

        #sale-transaction {
            white-space: nowrap;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #sale-transaction td,
        #sale-transaction th {
            white-space: nowrap;
            border: 1px solid #ddd;
            padding: 8px;
        }

        #sale-transaction th {
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
    </style>
</head>

<body>
    <div class="">
        <div class="transaction">
            <div class="transaction-line-height">
                <img src="images/CiC-Privilege-Final-Logo.png" alt="" class="logo-cic">

                <div style="float: right; width: 28%; margin-right: 0px;;">
                    <span style="color: #0f6bcc;"><strong>SUMMARY</strong></span><br>
                    <span>STK NO</span> : &nbsp;<span id="total_transaction">{{ $entry->stk_no }}</span>
                    <br>
                    <span>ACTION BY</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
                    &nbsp;<span class="mvp-amount">{{$entry->createdBy }}</span>
                    <br>
                    <span>ACTION DATE</span>&nbsp;: <span id="period">
                        {{ $entry->created_at ? Carbon\Carbon::parse($entry->created_at)->format('d-m-Y, h:i:s A') : "" }}</span>
                    <span>REMARKS</span>&nbsp;: <span id="period">{{ $entry->note }}</span>
                </div>
            </div>
            <hr>
            <h4 style="margin-top: 0px">STOCK TAKE</h4>
        </div>
</body>

</html>