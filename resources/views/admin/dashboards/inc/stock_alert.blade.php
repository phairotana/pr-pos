<div class="card">
    <div class="card-header bg-success"><strong>Stock Alert</strong></div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dash-stock-alert" class="table w-100">
                <thead>
                    <tr class="text-nowrap">
                        <th scope="">Nº</th>
                        <th scope="">Product Code</th>
                        <th scope="">Product Name</th>
                        <th scope="">Stock Quantity</th>
                        <th scope="">Alert Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock_alert_dash as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product_code }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->stock_alert }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
