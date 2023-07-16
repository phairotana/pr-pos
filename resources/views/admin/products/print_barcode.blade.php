@extends('layouts.app')

@section('content')
    @php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    @endphp
    <span class="col-md-12 pl-0">
        <span class="font-weight-bold">
            <span class="btn-print-barcode btn btn-success btn-sm">
                <em class="la la-print"></em>
            </span>
            Print Barcode
        </span>
        <div class="mt-3 to-print">
            <div>{!! $generator->getBarcode($entry->product_code, $generator::TYPE_CODE_128) !!}</div>
            <p>CODE: {{ $entry->product_code ?? $entry->id }}</p>
        </div>
    </span>

@endsection
@section('after_styles')
    <style>
        @media print {
            body {
                color: #000;
                background: #fff;
            }
        }

    </style>
@endsection

@section('after_scripts')
    <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <script>
        $(".btn-print-barcode").on('click', function() {
            $(".to-print").print();
        });
    </script>
@endsection
