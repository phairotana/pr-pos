<!-- This file is used to store topbar (right) items -->
<li class="nav-item">
    @php
        $productOutOfStock = \App\Models\Product::whereHas('stock', function ($q) {
            $q->where('quantity', '<=', \DB::raw('stock_alert'));
        })->count();
        $due = \App\Models\Invoice::whereDate('credit_date', '<=', \Carbon\Carbon::today())
            ->where('due_amount', '>', \DB::raw('received_amount'))
            ->count();
    @endphp
    <a class="nav-link" href="{{ URL('admin/invoice?only_pass_due=true') }}">
        <button type="button" class="btn btn-primary">
            <i class="la la-dollar-sign"></i> <span class="badge bg-danger ms-1">{{ $due }}</span>
        </button>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ URL('admin/stock?only_out_stock=true') }}">
        <button type="button" class="btn btn-primary">
            <i class="la la-cart-arrow-down"></i> <span class="badge bg-danger ms-1">{{ $productOutOfStock }}</span>
        </button>
    </a>
</li>
