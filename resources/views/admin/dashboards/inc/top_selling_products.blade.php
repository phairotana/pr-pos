<div class="card">
    <div class="card-header bg-info"><strong>Top Selling Products (This Month)</strong></div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dash-top-selling-product" class="table w-100">
                <thead>
                    <tr class="text-nowrap">
                        <th scope="">Nº</th>
                        <th scope="">Products</th>
                        <th scope="">Quantities</th>
                        <th scope="">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top_ten_selling_products_dash as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>{{ App\Helpers\Helper::formatCurrency($item->grand_total, '$') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
