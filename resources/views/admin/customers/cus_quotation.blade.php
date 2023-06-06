<div class="table-responsive">
    <table id="cus-quotation-table" class="table w-100">
        <thead>
            <tr class="text-nowrap">
                <th scope="">No</th>
                <th scope="">Quotation Date</th>
                <th scope="">Reference</th>
                <th scope="">Status</th>
                <th scope="">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($qoutation_data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->quotation_date)->format('d-m-Y') }}</td>
                    <td>{{ $item->ref_id }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ App\Helpers\Helper::formatCurrency($item->amount, '$') }}</td>
                </tr>
                @php
                    $total += $item->amount;
                @endphp
            @endforeach
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <th scope="" colspan="4" class="text-center">Total: </th>
                <th scope="">{{ App\Helpers\Helper::formatCurrency($total, '$') }}</th>
            </tr>
        </tfoot>
    </table>
</div>