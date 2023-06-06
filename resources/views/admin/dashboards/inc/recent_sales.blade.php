<div class="card">
    <div class="card-header bg-primary"><strong>Recent Sales</strong></div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dash-recent-sles" class="table w-100">
                <thead>
                    <tr class="text-nowrap">
                        <th scope="">Nº</th>
                        <th scope="">Reference</th>
                        <th scope="">Customer</th>
                        <th scope="">Invoice Status</th>
                        <th scope="">Payment Status</th>
                        <th scope="">Grand Total</th>
                        <th scope="">Amount Paid</th>
                        <th scope="">Amount Due</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recent_sales_dash as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->ref_id }}</td>
                            <td>{{ $item->customer_name }}</td>
                            <td>{{ $item->invoice_status }}</td>
                            <td>{{ $item->payment_status }}</td>
                            <td>{{ App\Helpers\Helper::formatCurrency($item->amount_payable, '$') }}</td>
                            <td>{{ App\Helpers\Helper::formatCurrency($item->received_amount, '$') }}</td>
                            <td>{{ App\Helpers\Helper::formatCurrency($item->due_amount, '$') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
