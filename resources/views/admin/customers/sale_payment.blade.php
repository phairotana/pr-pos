<div class="table-responsive">
    <table id="cus-sale-payment-table" class="table w-100">
    <thead>
        <tr class="text-nowrap">
            <th scope="">No</th>
            <th scope="">Date</th>
            <th scope="">Reference</th>
            <th scope="">Received By</th>
            <th scope="">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_amount = 0;
        @endphp
        @foreach ($sale_payment_data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                <td>{{ optional($item->invoice)->ref_id }}</td>
                <td>{{ optional($item->receivedBy)->name }}</td>
                <td>{{ App\Helpers\Helper::formatCurrency($item->amount, '$') }}</td>
            </tr>
            @php
                $total_amount += $item->amount;
            @endphp 
        @endforeach
    </tbody>
    <tfoot class="bg-light">
        <tr>
            <th scope="" colspan="4" class="text-center">Total: </th>
            <th scope="">{{ App\Helpers\Helper::formatCurrency($total_amount, '$') }}</th>
        </tr>
    </tfoot>
</table>
</div>
