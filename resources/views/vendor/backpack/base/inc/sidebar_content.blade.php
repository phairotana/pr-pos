<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>
@if (Auth::user()->can('list invoices'))
    <li class='nav-item nav-dropdown'>
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-desktop"></i>App</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'>
                <a class='nav-link' href='{{ backpack_url('offer') }}'>
                    <i class='nav-icon la la-minus'></i>Offer
                </a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='{{ backpack_url('mobile/order') }}'>
                    <i class='nav-icon la la-minus'></i>Order
                </a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='{{ backpack_url('mobile/setting') }}'>
                    <i class='nav-icon la la-cog'></i>Setting
                </a>
            </li>
        </ul>
    </li>
@endif
{{-- Invoice --}}
@if (Auth::user()->can('list invoices'))
    <li class='nav-item nav-dropdown'>
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-shopping-cart"></i>Sale</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list invoices'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('invoice') }}"><i
                            class="nav-icon la la-list-alt"></i> <span>Lists</span></a></li>
            @endif
            @if (Auth::user()->can('list invocie return'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('invoice-return') }}"><i
                            class="nav-icon la la-undo-alt"></i> <span>Add Return</span></a></li>
            @endif
            @if (Auth::user()->can('create invoices'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('invoice/create') }}'><i
                            class='nav-icon la la-plus'></i> Add Sale</a></li>
            @endif
            <li class='nav-item'>
                <a class='nav-link' href='{{ backpack_url('mobile/order') }}'><i
                class='nav-icon la la-mobile'></i> Mobile Order</a>
            </li>
        </ul>
    </li>
@endif
{{-- Purchase --}}
@if (Auth::user()->can('list purchase'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cart-arrow-down"></i>Purchase</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list purchase'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('purchase') }}"><i
                            class="nav-icon la la-list-alt"></i> <span>Lists</span></a></li>
            @endif
            {{-- @if (Auth::user()->can('list purchase return'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('purchase-return') }}'><i
                            class="nav-icon la la-undo-alt"></i>Add Return</a></li>
            @endif --}}
            @if (Auth::user()->can('create purchase'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('purchase/create') }}'><i
                            class='nav-icon la la-plus'></i> Add Purchase</a></li>
            @endif
        </ul>
    </li>
@endif
@if (Auth::user()->can('list adjustment'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-edit"></i>Adjustments</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list adjustment'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('adjustment') }}'><i
                            class="nav-icon la la-list-alt"></i> Adjustments</a></li>
            @endif
            @if (Auth::user()->can('create adjustment'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('adjustment/create') }}'><i
                            class="nav-icon la la-plus"></i> Add Adjustment</a></li>
            @endif
        </ul>
    </li>
@endif
{{-- SupplierMenu --}}
@if (Auth::user()->can('list products'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon lab la-product-hunt"></i> Products</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list products'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('product') }}'><i
                            class="nav-icon las la-list-alt"></i> Lists</a></li>
            @endif
            @if (Auth::user()->can('create products'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('product/create') }}'><i
                            class="nav-icon la la-plus"></i> Add New</a></li>
            @endif
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('product/print-barcode/create') }}'><i
                        class="nav-icon las la-barcode"></i> Print Barcode</a></li>
        </ul>
    </li>
@endif
@if (Auth::user()->can('list payment'))
    <li class='nav-item nav-dropdown'>
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-comments-dollar"></i>Payments</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list payment'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ backpack_url('payment') }}">
                        <i class="nav-icon la la-list-alt"></i> <span>Lists</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('create payment'))
                <li class='nav-item'>
                    <a class='nav-link' href='{{ backpack_url('payment/create') }}'>
                        <i class='nav-icon la la-plus'></i> Add New
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
{{-- Customer Menu --}}
@if (Auth::user()->can('list customers'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Customers</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list customers'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('customers') }}'><i
                            class="nav-icon las la-list-alt"></i> Lists</a></li>
            @endif
            @if (Auth::user()->can('create customers'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('customers/create') }}'><i
                            class="nav-icon la la-plus"></i> Add New</a></li>
            @endif
        </ul>
    </li>
@endif

{{-- SupplierMenu --}}
@if (Auth::user()->can('list suppliers'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon las la-dolly"></i> Suppliers</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list suppliers'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('supplier') }}'><i
                            class='nav-icon las la-list-alt'></i> Lists</a></li>
            @endif
            @if (Auth::user()->can('create suppliers'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('supplier/create') }}'><i
                            class='nav-icon la la-plus'></i> Add New</a></li>
            @endif
        </ul>
    </li>
@endif

{{-- Expense --}}
@if (Auth::user()->can('list expenses'))
    <li class='nav-item nav-dropdown'>
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-file-import"></i>Expense</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list expenses'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('expense') }}"><i
                            class="nav-icon la la-list-alt"></i> <span>Lists</span></a></li>
            @endif
            @if (Auth::user()->can('create expenses'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('expense/create') }}'><i
                            class='nav-icon la la-plus'></i> Add New</a></li>
            @endif
        </ul>
    </li>
@endif

{{-- Quotation --}}
@if (Auth::user()->can('list quotation'))
    <li class='nav-item nav-dropdown'>
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-comments-dollar"></i>Quotation</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list quotation'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('quotations') }}"><i
                            class="nav-icon la la-list-alt"></i> <span>Lists</span></a></li>
            @endif
            @if (Auth::user()->can('create quotation'))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('quotations/create') }}'><i
                            class='nav-icon la la-plus'></i> Add New</a></li>
            @endif
        </ul>
    </li>
@endif
{{-- Stock Menu --}}
@if (Auth::user()->can('list stocks'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('stock') }}'><i class="nav-icon la la-clone"></i>
            Stock</a></li>
@endif

{{-- Report Menu --}}
@if (Auth::user()->can('list customer report'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-chart-bar"></i> Reports</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/customer') }}"><i
                        class="nav-icon la la-minus"></i> <span>Customer</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/supplier') }}"><i
                        class="nav-icon la la-minus"></i> <span>Supplier</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/purchased') }}"><i
                        class="nav-icon la la-minus"></i> <span>Purchased</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/invoice') }}"><i
                        class="nav-icon la la-minus"></i> <span>Invoice</span></a></li>
            {{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/invoice/return') }}"><i
                        class="nav-icon la la-minus"></i> <span>Invoice Return</span></a></li> --}}
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/product-alert') }}"><i
                        class="nav-icon la la-minus"></i> <span>Product Alert</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/expenses') }}"><i
                        class="nav-icon la la-minus"></i> <span>Expenses</span></a></li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-minus"></i> Statemenmt</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item" style="padding-left: 10px;"><a class="nav-link"
                        href="{{ backpack_url('report/payment') }}"><i class="nav-icon la la-minus"></i>
                        <span>Payment</span></a></li>
                    <li class="nav-item" style="padding-left: 10px;"><a class="nav-link"
                            href="{{ backpack_url('report/profit_and_loss') }}"><i class="nav-icon la la-minus"></i>
                            <span>Profit And Loss</span></a></li>
                </ul>
            </li>
        </ul>
    </li>
@endif

{{-- Setting Menu --}}
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Setting</a>
    <ul class="nav-dropdown-items">
        @if (Auth::user()->can('list categories'))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i
                        class="la la-minus nav-icon"></i> Categories</a></li>
        @endif
        @if (Auth::user()->can('list attributes'))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('attribute') }}"><i
                        class="la la-minus nav-icon"></i> Attributes</a></li>
        @endif
        @if (Auth::user()->can('list branches'))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('branch') }}"><i
                        class="la la-minus nav-icon"></i> Branches</a></li>
        @endif
        @if (Auth::user()->can('list brand'))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('brand') }}">
                    <i class="la la-minus nav-icon"></i> Brands</a>
            </li>
        @endif
        @if (Auth::user()->can('list item location'))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('storage') }}"><i
                        class="la la-minus nav-icon"></i> Storages</a></li>
        @endif


        @if (Auth::user()->can('list product unit'))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product-unit') }}"><i
                        class="la la-minus nav-icon"></i> Product units</a></li>
        @endif
        {{-- ADMINISTRATOR --}}
        @if (Auth::user()->hasAnyRole('ADMINISTRATOR|DEVELOPER'))
            <li class='nav-item'>
                <a class='nav-link' href='{{ backpack_url('setting') }}'>
                    <i class='nav-icon la la-hammer'></i> config
                </a>
            </li>
        @endif
    </ul>
</li>

{{-- Authentication Menu --}}
@if (Auth::user()->can('list authentications'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentications</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list users'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i
                            class="nav-icon la la-user"></i> <span>Users</span></a></li>
            @endif
            @if (Auth::user()->can('list roles'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i
                            class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
            @endif
            @if (Auth::user()->can('list permissions'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i
                            class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
            @endif
        </ul>
    </li>
@endif

{{-- Genderal Menu --}}
@if (Auth::user()->can('list options'))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Generalization</a>
        <ul class="nav-dropdown-items">
            @if (Auth::user()->can('list options'))
                <li class="nav-item"><a class="nav-link" href="{{ backpack_url('option') }}"><i
                            class="nav-icon la la-minus"></i> <span>Options</span></a></li>
            @endif
        </ul>
    </li>
@endif