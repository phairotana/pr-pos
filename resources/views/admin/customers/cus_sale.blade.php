<div class="table-responsive">
    <table id="cus-sale-table" class="table w-100">
        <thead>
            <tr class="text-nowrap">
                <th scope="">No</th>
                <th scope="">Invoice Date</th>
                <th scope="">Reference</th>
                <th scope="">Payment Status</th>
                <th scope="">Grand Total</th>
                <th scope="">Paid Amount</th>
                <th scope="">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_amount_payable = $total_amount_paid = $total_amount_due = 0;
            @endphp
            @foreach ($sale_data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->invoice_date)->format('d-m-Y') }}</td>
                    <td>{{ $item->ref_id }}</td>
                    <td>{{ $item->payment_status }}</td>
                    <td>{{ App\Helpers\Helper::formatCurrency($item->amount_payable, '$') }}</td>
                    <td>{{ App\Helpers\Helper::formatCurrency($item->received_amount, '$') }}</td>
                    <td>{{ App\Helpers\Helper::formatCurrency($item->due_amount, '$') }}</td>
                </tr>
                @php
                    $total_amount_payable += $item->amount_payable;
                    $total_amount_paid += $item->received_amount;
                    $total_amount_due += $item->due_amount;
                @endphp
            @endforeach
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <th scope="" colspan="4" class="text-center">Total: </th>
                <th scope="">{{ App\Helpers\Helper::formatCurrency($total_amount_payable, '$') }}</th>
                <th scope="">{{ App\Helpers\Helper::formatCurrency($total_amount_paid, '$') }}</th>
                <th scope="">{{ App\Helpers\Helper::formatCurrency($total_amount_due, '$') }}</th>
            </tr>
        </tfoot>
    </table>
</div>
