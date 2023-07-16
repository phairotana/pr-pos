<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ config('backpack.base.html_direction') }}">

<head>
    @include(backpack_view('inc.head'))

</head>

<body class="{{ config('backpack.base.body_class') }}">
    <div id="main-header" class="{{ config('backpack.base.main_header') }}">
        @include(backpack_view('inc.main_header'))
    </div>

    <div class="app-body">

        @include(backpack_view('inc.sidebar'))

        <main class="{{ config('backpack.base.content_class') }}">
            @yield('before_breadcrumbs_widgets')

            @includeWhen(isset($breadcrumbs), backpack_view('inc.breadcrumbs'))

            @yield('after_breadcrumbs_widgets')

            @yield('header')

            <div class="container-fluid animated fadeIn row">

                @yield('before_content_widgets')

                @yield('content')

                @yield('after_content_widgets')

            </div>

        </main>

    </div><!-- ./app-body -->

    <footer class="{{ config('backpack.base.footer_class') }}">
        @include(backpack_view('inc.footer'))
    </footer>

    @yield('before_scripts')
    @stack('before_scripts')

    @include(backpack_view('inc.scripts'))

    @yield('after_scripts')
    @stack('after_scripts')

    <script>
        $(document).ready(function() {

            /* Vue app controller reactive */
            var $discountPercent = $('#purchase_discount_percent')
            var $discountAmount = $('#purchase_discount_amount')
            var $discountAllType = $('.purchase_discount_all_type')
            var $purchaseDiscountType = $('.purchase_discount_type')
            var $amountRecieve = $("#received-amount")
            /* old value  */
            var $oldValdiscountAllType = "{{ old('discount_all_type', '') }}";
            var $oldValdiscountPerCent = "{{ old('discount_percent', '') }}";
            var $oldValdiscountAmount = "{{ old('discount_fixed_value', '') }}";
            var $oldValAmountRecieve = "{{ old('received_amount', '') }}";
            var $oldDiscountType = "{{ old('discount_type') }}"

            if ($('.purchase_discount_all_type').val() == 'fixed_value') {
                $discountAmount.parent().parent().show()
                $discountPercent.parent().parent().hide()
                vueProductDetail.$set(vueProductDetail, 'discount', $('#purchase_discount_amount').val() ? parseFloat($('#purchase_discount_amount').val()) : 0);
                vueProductDetail.updateContext()
            } else if ($('.purchase_discount_type').val() == 'percent') {
                $discountAmount.parent().parent().hide()
                $discountPercent.parent().parent().show()
                vueProductDetail.$set(vueProductDetail, 'discount', $('#purchase_discount_percent').val() ? parseFloat($('#purchase_discount_percent').val()) : 0);
                vueProductDetail.updateContext()
            } else {
                $discountAmount.parent().parent().hide()
                $discountPercent.parent().parent().hide()
            }

            if ($('.purchase_discount_all_type').val() == 'per_item') {
                vueProductDetail.$set(vueProductDetail, 'disable_discount_input', false)

                $purchaseDiscountType.val('').trigger('')
                vueProductDetail.$set(vueProductDetail, 'discount_all_type', $('.purchase_discount_all_type').val())

                $purchaseDiscountType.parent().hide()
                $discountAmount.parent().parent().hide();
                $discountPercent.parent().parent().hide()

                $purchaseDiscountType.trigger('change');
                vueProductDetail.updateContext();
            }

            if ($('.purchase_discount_type').val() == 'fixed_value') {
                $discountAmount.parent().parent().show()
                $discountPercent.parent().parent().hide()
                vueProductDetail.$set(vueProductDetail, 'discount', $('#purchase_discount_amount').val() ? parseFloat($('#purchase_discount_amount').val()) : 0);
                vueProductDetail.updateContext()
            } else if ($('.purchase_discount_type').val() == 'percent') {
                $discountAmount.parent().parent().hide()
                $discountPercent.parent().parent().show()
                vueProductDetail.$set(vueProductDetail, 'discount', $('#purchase_discount_percent').val() ? parseFloat($('#purchase_discount_percent').val()) : 0);
                vueProductDetail.updateContext()
            } else {
                $discountAmount.parent().parent().hide()
                $discountPercent.parent().parent().hide()
            }

            $purchaseDiscountType.on('change', function() {
                if ($(this).val() == 'fixed_value') {
                    $discountAmount.parent().parent().show()
                    $discountPercent.parent().parent().hide()
                } else if ($(this).val() == 'percent') {
                    $discountAmount.parent().parent().hide()
                    $discountPercent.parent().parent().show()
                } else {
                    $discountAmount.parent().parent().hide()
                    $discountPercent.parent().parent().hide()
                }
            })

            $discountAllType.on('change', function() {
                $purchaseDiscountType.val('').trigger('')
                vueProductDetail.$set(vueProductDetail, 'discount_all_type', $(this).val())
                if ($(this).val() != 'per_item') {
                    vueProductDetail.resetDiscountProductDetail()
                    $purchaseDiscountType.parent().show()
                    $discountAmount.parent().parent().show()
                    $discountPercent.parent().parent().show()
                } else {
                    $purchaseDiscountType.parent().hide()
                    vueProductDetail.resetDiscountProductDetail()
                    $discountAmount.parent().parent().hide();
                    $discountPercent.parent().parent().hide()
                }
                $purchaseDiscountType.trigger('change');
            })

            $('.purchase_discount_type').on('change', function() {
                $discountAmount.val(0);
                $discountPercent.val(0);
                vueProductDetail.$set(vueProductDetail, 'discount', 0.0);
                vueProductDetail.$set(vueProductDetail, 'discount_amount', 0.0);
                vueProductDetail.resetDiscountProductDetail();
                vueProductDetail.updateContext();
            });

            $discountPercent.on('keyup change', function() {
                vueProductDetail.$set(vueProductDetail, 'discount', $(this).val() ? parseFloat($(this)
                    .val()) : 0);
                vueProductDetail.updateContext()
            })
            $discountAmount.on('keyup change', function() {
                vueProductDetail.$set(vueProductDetail, 'discount', $(this).val() ? parseFloat($(
                    this).val()) : 0);
                vueProductDetail.updateContext()
            })

            $purchaseDiscountType

            /* handle old value perspective */

            if ($oldValdiscountAllType) {
                $discountAllType.val($oldValdiscountAllType).trigger('change')
            }

            if ($oldValdiscountAmount) {
                $discountAmount.val($oldValdiscountAmount).trigger('change')
            }
            if ($oldValdiscountPerCent) {
                $discountPercent.val($oldValdiscountPerCent).trigger('change')
            }

            if ($oldDiscountType) {
                $purchaseDiscountType.val($oldDiscountType).trigger('change')
            }

            /* change status amount */
            $('#received-amount').on('keyup', function() {
                vueProductDetail.$set(vueProductDetail, 'amount_recieve', $(this).val())
            })

            if ($oldValAmountRecieve) {
                $("#received-amount").trigger('keyup');
            }
        });


        // *** Disallow anything not matching the regex pattern (A to Z uppercase, a to z lowercase and white space)
        $(".pr-keyinput").on("keypress", function(event) {
            var englishAlphabetAndWhiteSpace = /[A-Za-z ]/g;
            // Retrieving the key from the char code passed in event.which
            var key = String.fromCharCode(event.which);
            //alert(event.keyCode);
            // For the keyCodes, look here: http://stackoverflow.com/a/3781360/114029
            // keyCode == 8  is backspace
            // keyCode == 37 is left arrow
            // keyCode == 39 is right arrow
            // englishAlphabetAndWhiteSpace.test(key) does the matching, that is, test the key just typed against the regex pattern
            if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || englishAlphabetAndWhiteSpace
                .test(key)) {
                return true;
            }

            // If we got this far, just return false because a disallowed key was typed.
            return false;
        });
        $('.pr-keyinput').on("paste", function(e) {
            e.preventDefault();
        });
    </script>
</body>
<style>
</style>

</html>