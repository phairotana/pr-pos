<div class="form-group col-md-12" id="invoice-container-repeatable-elements">
    <div class="row invoice-repeatable-element repeatable-element mt-3">
        <button type="button" class="close invoice-delete-element delete-element"><span
                aria-hidden="true">×</span></button>
        <input type="hidden" name="invoice_id[]" />
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Product Name</label>
            <select name="product_name[]" class="form-control product_name" aria-label=".form-select-sm example"
                onchange="selectProduct(this)">
                <option value="" disabled selected>Select a product</option>
                <?php
                $products = \App\Models\Product::all();
                ?>
                @foreach ($products as $product)
                    <option value="{{ $product->product_id }}" data-product-code={{ $product->product_code }}
                        data-product-cost-price={{ $product->cost_price }}
                        data-product-sell-price={{ $product->sell_price }}>{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Product Code</label>
            <input type="text" class="form-control product-code" name="product_code[]" readonly>
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Note</label>
            <input type="number" class="form-control product-note" name="note[]" step="any">
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>QTY</label>
            <input type="number" class="form-control qty" name="qty[]" step="any" onkeyup="qtyKeyup(this)"
                onchange="qtyChange(this)">
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Discount</label>
            <input type="number" class="form-control discount" name="discount[]" step="any"
                onkeyup="dicountKeyup(this)">
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Cost Price</label>
            <input type="number" class="form-control cost-price" name="cost_price[]" readonly step="any">
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Sell Price</label>
            <input type="number" class="form-control sell-price" name="sell_price[]" readonly step="any">
        </div>
        <div class="col-sm-3 col-md-3 mb-3">
            <label>Total</label>
            <input type="number" class="form-control total" name="total[]" readonly step="any">
        </div>
    </div>

</div>
<div class="form-group col-md-12">
    <button type="button" class="btn btn-success btn-sm ml-1 add-repeatable-element-button" id="addInvoice">+ Add New
    </button>
</div>

<div class="col-md-12 mt-3">
    <div class="row">
        <div class="col-sm-4">
            <label>Discount Type</label>
            <select name="discount_type" class="form-control discount_type">
                <option value="" disabled selected>Select a product</option>
                <<option value="Fixed value">Fixed value</option>
                    <option value="Percent">Percent</option>
            </select>
        </div>
        <div class="col-sm-4">
            <div class="fix-dis" style="display: none">
                <label>Discount amount</label>
                <input type="number" class="form-control fix-discount-amount" name="fix_discount_amount" step="any">

            </div>
            <div style="display: none" class="percent-dis">
                <label>Discount amount</label>
                <input type="number" class="form-control percent-discount-amount" name="percent_discount_amount"
                    step="any">

            </div>

        </div>
        <div class="col-sm-4" style="margin-top: auto; margin-bottom:0;">
            <div style="float: right;">
                <table>
                    <tr>
                        <td><strong>Grand total</strong></td>
                        <td> : </td>
                        <td class="grand-total text-right">$ 0.00

                            <input type="hidden" nam='grand_total'>
                        </td>

                    </tr>
                    <tr>
                        <td><strong>Total discount</strong></td>
                        <td> : </td>
                        <td class="discount-total text-right">$ 0.00
                            <input type="hidden" nam='discount_total'>

                        </td>
                    </tr>
                    <tr>
                        <td><strong>Amount payable</strong></td>
                        <td> : </td>
                        <td class="amount-pay-total text-right">$ 0.00
                            <input type="hidden" name="amount_payable" class='input-hidden-amount-payable'>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
    </div>
</div>
@push('after_styles')
    {{-- Remove style and class when enable all field show error --}}
    <style>
        .label-required {
            color: #ff0000;
        }

        .no-error-border {
            border-color: #d2d6de !important;
        }

        .no-error-label {
            color: #333 !important;
        }

        .repeatable-element {
            padding: 10px;
            border: 1px solid rgba(0, 40, 100, .12);
            border-radius: 5px;
            background-color: #f0f3f94f;
            margin-right: 0px;
            margin-left: 0;
        }

        .delete-element {
            z-index: 2;
            position: absolute !important;
            margin-left: -25px;
            margin-top: 0px;
            height: 30px;
            width: 30px;
            border-radius: 15px;
            text-align: center;
            background-color: #e8ebf0 !important;
        }

    </style>
@endpush

@push('after_scripts')
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        let number_format = new Intl.NumberFormat('en-IN')

        $(function() {
            $('.invoice-delete-element').hide();
            $('body').on('click', '#addInvoice', function() {
                $('.invoice-delete-element').show();

                $('.invoice-repeatable-element:first').clone().appendTo(
                    '#invoice-container-repeatable-elements');
                var lastRepeatableElement = $('.invoice-repeatable-element:last');
                var input = lastRepeatableElement.find('input');
                input.val('');
            });
            $('body').on('click', '.invoice-delete-element', function() {
                $(this).closest('.invoice-repeatable-element').remove();
            });

            $('.discount_type').change(function() {
                var type = $(this).find(':selected');
                if (type.text() == 'Fixed value') {
                    $('.fix-dis').css('display', 'block')
                    $('.percent-dis').css('display', 'none')
                } else {
                    $('.fix-dis').css('display', 'none')
                    $('.percent-dis').css('display', 'block')
                }
            });
            // $('.total').keyup(function() {
            //     console.log('ok');
            // });

            $('.fix-discount-amount').keyup(function() {
                $('.percent-discount-amount').val(0);
                var totals = calDiscountFix($(this).val());
                $('.discount-total').text("$ " + totals[0]);
                $('.amount-pay-total').text("$ " + totals[1]);
                $('input[name="discount_total"]').val(totals[0]);
                $('input[name="amount_payable"]').val(totals[1]);
            });

            $('.percent-discount-amount').keyup(function() {
                $('.fix-discount-amount').val(0);

                var totals = calDiscountPer($(this).val());

                $('.discount-total').text("$ " + totals[0]);
                $('.amount-pay-total').text("$ " + totals[1]);
                $('input[name="discount_total"]').val(totals[0]);
                $('input[name="amount_payable"]').val(totals[0]);
            })
        });


        function selectProduct(event) {
            var form = $(event).closest('.invoice-repeatable-element')
            var product_name = $(event).find(':selected');
            var product_code = form.find('.product-code');
            var product_note = form.find('.product-note');
            var qty = form.find('.qty');
            var cost_price = form.find('.cost-price');
            var product_discount = form.find('.discount');
            var sell_price = form.find('.sell-price');
            var total = form.find('.total');
            var discount = form.find('.discount');

            product_code.val(product_name.attr('data-product-code'));
            qty.val(1);
            cost_price.val(parseFloat(product_name.attr('data-product-cost-price')));
            sell_price.val(parseFloat(product_name.attr('data-product-sell-price')))

            var total_pay = parseFloat(product_name.attr('data-product-sell-price')) - discount.val();

            if (total_pay > 0) {
                total.val(total_pay);
            } else {
                total.val(0);
            }
            var totals = calAllTotal();

            $('.grand-total').text("$ " + totals[0]);
            $('.discount-total').text("$ " + totals[1]);
            $('.amount-pay-total').text("$ " + totals[2]);
        }

        function qtyKeyup(event) {
            var form = $(event).closest('.invoice-repeatable-element');
            var total = form.find('.total');
            var qty = $(event).val();
            var sell_price = form.find('.sell-price').val();
            var discount = form.find('.discount').val();
            var total_pay = (qty * sell_price) - discount;
            if (total_pay > 0) {
                total.val(total_pay);
            } else {
                total.val(0);

            }
            var totals = calAllTotal();
            $('.grand-total').text("$ " + totals[0]);
            $('.discount-total').text("$ " + totals[1]);
            $('.amount-pay-total').text("$ " + totals[2]);

        }

        function discountAmount() {
            var type = $('.discount_type').find(':selected');
            var discount_amount = 0;
            if (type == "Fixed value")
            {
                discount_amount = $('.fix-dis').val();
            }else{
                var dis_value = $('.percent-dis').val();
                var amount_pay = $('.input-hidden-amount-payable').val();
            }
            return type.text();

        }

        function dicountKeyup(event) {
            var form = $(event).closest('.invoice-repeatable-element');
            var total = form.find('.total');
            var qty = form.find('.qty').val();
            var sell_price = form.find('.sell-price').val();
            var discount = $(event).val();
            var total_pay = (qty * sell_price) - discount;
            if (total_pay > 0) {
                total.val(total_pay);
            } else {
                total.val(0);
            }
            var totals = calAllTotal();
            $('.grand-total').text("$ " + totals[0]);
            $('.discount-total').text("$ " + totals[1]);
            $('.amount-pay-total').text("$ " + totals[2]);

        }


        function calAllTotal() {
            var grands = discounts = payables = 0;
            var discount_type = discountType();

                $('.invoice-repeatable-element').map(function() {
                    var qty = parseFloat($(this).find('.qty').val());
                    var sell = parseFloat($(this).find('.sell-price').val());

                    var discount = parseFloat($(this).find('.discount').val());
                    var total = parseFloat($(this).find('.total').val())
                    if (!discount) discount = 0;
                    if (!sell) sell = 0;
                    if (!total) total = 0;
                    if (!qty) qty = 0;

                    var grand = qty * sell;
                    grands += grand;
                    payables += total;
                    if (total > 0) discounts += discount;
                });

            if (grands < 0) grands = 0;
            if (payables < 0) payables = 0;
            if (discounts < 0) discounts = 0;
            console.log(discount);
            return [grands, discounts, payables];

        }

        function calDiscountFix(amount) {
            var details = calAllTotal();
            var payables = details[2];

            var payable = payables - amount;

            if (payable < 0) {
                amount = details[0];
                payable = 0;
            }

            return [amount, payable];

        }


        function calDiscountPer(amount) {
            var details = calAllTotal();
            var payables = details[2];
            var grand = details[0];


            var discount = payables * amount / 100;
            var payable = payables - discount;

            if (payable < 0) {
                discount = details[0];
                payable = 0;
            }
            return [discount, payable];
        }

        function qtyChange(event) {
            if ($(event).val() < 0) {
                $(event).val(0);
            }
        }
    </script>
@endpush
