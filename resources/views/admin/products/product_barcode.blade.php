<div class="row">
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    @endphp
    <span class="col-md-12 pl-0">
        <span class="navbar-brand custom-navbar-brand font-weight-bold">
            <span class="btn-print-barcode btn btn-success btn-sm">
                <em class="la la-print"></em>
            </span>
            Print Barcode
        </span>
        <div class="mt-3 to-print">
            <div>{!! $generator->getBarcode($entry->product_code, $generator::TYPE_CODE_128) !!}</div>
            <p>code: {{ $entry->product_code ?? $entry->id }}</p>
        </div>
    </span>
</div>
