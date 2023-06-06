<label class="navbar-brand custom-navbar-brand font-weight-bold">Product Information</label>
<table class="table table-striped" aria-hidden="true">
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%"> Product Code</td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->product_code }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%">Product Name </td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->product_name }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%">Category </td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->CategoryName }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%">Brand </td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ optional($entry->rBrand)->name }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%">Unit </td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->UnitName }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%">Cost Price </td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->CostPriceFormat }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold"style="width:20%">Sell Price</td>
        <td class="border-0"style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{$entry->SellPriceFormat }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Item Location</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ $entry->LocationName }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Stock Alert</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{$entry->stock_alert ?? '' }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Pre Order</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{$entry->pre_order ?? '' }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Created At</td>
        <td class="border-0" style="width:5%">:</td>
        <td class="border-0" style="width:25%">{{ \Carbon\Carbon::parse($entry->created_at)->format('d-m-Y H:i:s A') }}</td>
    </tr>
    <tr>
        <td class="border-0 font-weight-bold" style="width:20%">Attributes</td>
        <td class="border-0" style="width:5%">:</td>

        <td class="border-0" style="width:25%">
            @foreach ($entry->attributes as $item)
                - {{ $item->name }} <br>
            @endforeach
        </td>
    </tr>
</table>