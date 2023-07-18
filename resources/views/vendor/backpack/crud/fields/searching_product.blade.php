@php

$fieldLabel = $field['label'] ?? \Str::title($field['name']);
$fieldOldValue = !empty($field['value']) ? $field['value'] : old('product_detail') ?? null;
$fieldHasEnableAlert = $field['is_enable_alert'] ?? 'true';
$dependencies = $field['dependencies'] ?? [];
$sourceRoute = $field['source_route'] ?? route('admin.api.product_search');
$model = $field['model'] ?? '';
$belongsTo = $field['belongs_to'] ?? 'Invoice';
@endphp

<div id="vue_app_product" class="form-group col-md-12 div-search">

    <div class="form-group has-search">
        @csrf
        <div class="CardInner customInput">
            <h5>Search item to sale.</h5>
            <div class="container">
                <div class="Icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#657789" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
                <div class="InputContainer">
                    <input v-model="search_term" @keyup="searchProduct" type="search" id="searchDropdownMenuLink" autocomplete="off" placeholder="Enter searching term..." />
                </div>
            </div>
        </div>
        <ul class="list-group mt-1 w-full">
            <li v-for="{product_name, product_code, id} in product_list" @click="selectProduct(id)" class="list-group-item list-group-item-action" style="cursor: pointer;">
                <span v-text="product_name"></span><span class="text-muted" v-text="' #' + product_code"></span>
            </li>
        </ul>
        {{-- Hidden input fields --}}
        <input type="hidden" name="product_detail" :value="product_json">
        <input type="hidden" name="amount" :value="total">
        <input type="hidden" name="amount_payable" :value="total">
        <input type="hidden" name="discount_amount" :value="discount_amount">
        {{-- End hidden input fields --}}
        <div class="table-responsive">

            <table class="table border mt-3">
                <thead>
                    <tr>
                        <th class="text-nowrap" scope="col">#</th>
                        <th class="text-nowrap" scope="col">Product code</th>
                        <th class="text-nowrap" scope="col">Name</th>
                        <th class="text-nowrap" scope="col">Note</th>
                        <th class="text-nowrap" scope="col">Qty</th>
                        <th class="text-nowrap" v-if="belongs_to_invoice" scope="col">Sell price</th>
                        <th class="text-nowrap" v-if="!belongs_to_invoice" scope="col">Cost price</th>
                        <th class="text-nowrap" scope="discoun_type">Discount type</th>
                        <th class="text-nowrap" scope="col">Discount</th>
                        <th class="text-nowrap" v-if="isEnable">Pre order</th>
                        <th class="text-nowrap" scope="col">Total</th>
                        <th class="text-nowrap" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <td v-if="!product_action.length" class="text-center" valign="top" colspan="8" class="dataTables_empty">Please search and select product</td>
                    <tr v-for="({ product_name, product_code, stock_qty , id, dis_type, qty, note, sell_price, cost_price ,t_total, discount , pre_order},index) in product_action">
                        <td class="font-weight-bold" v-text="(index + 1)"></td>
                        <th v-text="product_code"></th>
                        <td v-text="product_name"></td>
                        <td><input class="form-control form-md" @keyup="updateContext($event.target.value, 'note', id)" :value="note"></td>
                        <td><input class="form-control form-sm" min="1" @keyup="updateContext($event.target.value, 'qty', id, $event)" :value="qty" type="number"></td>
                        <td><input class="form-control form-sm" @keyup="updateContext($event.target.value, belongs_to_invoice ? 'sell_price': 'cost_price', id)" :value="belongs_to_invoice ? sell_price : cost_price"></td>
                        <td>
                            <select :value="dis_type" :disabled="disable_discount_input" @change="updateContext($event.target.value, 'dis_type', id)" class="form-control form-md select2_discount_option">
                                <option value="fix_val">Fixed value ($)</option>
                                <option value="percent">Percent (%)</option>
                            </select>
                        </td>
                        <td><input class="form-control form-sm" type="number" @keyup="updateContext($event.target.value, 'discount', id)" :value="discount" :max="dis_type == 'percent' ? 100 : ''" :disabled="disable_discount_input" step="any"></td>
                        <td v-if="isEnable" class="text-success">
                            <span v-if=" qty > stock_qty || pre_order == 'Yes'">Yes</span>
                            <span v-else>No</span>
                        <td v-text="numberFormat(t_total)"></td>
                        <td><a class="btn btn-sm btn-danger" @click="removeFromList(id)"><em class="la la-close text-white"></em></a> </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="offset-md-8 col-md-4 mt-4">
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td class="bold text-nowrap">Discount
                            <span v-if="discount_all_type == 'per_invoice' && discount_type == 'percent'" v-text="'(' + discount_percent_amount + ')'"></span>
                            <span v-else></span>
                        </td>
                        <td class="text-right text-nowrap" v-text="numberFormat(discount_amount)"></td>
                        <td class="text-right text-nowrap" v-text="numberFormat(discount_amount_kh).replace('$', '៛ ')"></td>
                    </tr>
                    <tr>
                        <td><span class="font-weight-bold text-nowrap">Grand Total</span></td>
                        <td class="text-right text-nowrap">
                            <span class="font-weight-bold" v-text="numberFormat(total)"></span>
                        </td>
                        <td class="text-right text-nowrap" v-text="numberFormat(total_kh).replace('$', '៛ ')"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('crud_fields_scripts')
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>

<script>
    $('body').removeClass('sidebar-lg-show');
    let khValue = " {{ convertKhUnit() }} "
    var element = $(`input[name="{{ $field['name'] }}"]`)

    var dependencies = <?php echo json_encode($dependencies); ?>;
    var fieldOld = <?php echo json_encode($fieldOldValue); ?>;

    var form = element.closest('form');

    const vueProductDetail = new Vue({
        el: "#vue_app_product",
        data() {
            return {
                label_field: "{{ $fieldLabel }}" ?? 'Product details',
                product_list: [],
                search_term: '',
                is_enable: "{{ $fieldHasEnableAlert }}",
                product_action: [],
                paymentObjectStatus: {
                    paid: 'Paid',
                    partially_paid: "Partially paid",
                    pending: 'Pending'
                },
                total: 0,
                total_kh: 0,
                discount_type: '',
                discount_all_type: 'per_invoice',
                amount_recieve: 0,
                discount: 0,
                discount_amount: 0,
                discount_percent_amount: 0,
                discount_amount_kh: 0,
                is_error: false,
                product_json: null,
                disable_discount_input: false,
                error: false,
                is_loaded: true,
                belongs_to: "{{ $belongsTo }}",
                search_route: "{{ $sourceRoute }}",
                model: "{{ $model }}",
                pre_order: false,
                dependencies: dependencies,
                old_value_detail: fieldOld
            }
        },
        mounted() {
            var vm = this;
            var oldValue = vm.old_value_detail;
            if (oldValue !== null && typeof(oldValue) == 'string') {
                vm.$set(vm, 'product_action', JSON.parse(JSON.parse(oldValue)))
            } else if (oldValue !== null && typeof(oldValue) == 'object') {
                oldValue = oldValue.map((el) => {
                    el['t_total'] = el.total_payable
                    el['note'] = el.product_note
                    return el
                })
                vm.$set(vm, 'product_action', oldValue)
            }
            if (vm.discount_all_type == "per_invoice") vm.disable_discount_input = true
        },
        watch: {
            total: function(val) {
                this.$set(this, 'total_kh', val * parseFloat(khValue))
            },
            discount_amount: function(val) {
                var discountType = $('.purchase_discount_type').val();
                this.discount_type = discountType == 'percent' ? 'percent' : discountType;
                this.$set(this, 'discount_amount_kh', val * parseFloat(khValue))
            },
            amount_recieve(val) {
                this.setAmountRecieveStatus(val)
            },
            discount_all_type(val) {
                if (val == "per_invoice") {
                    this.$set(this, 'disable_discount_input', true)
                } else {
                    this.$set(this, 'disable_discount_input', false)
                }
            },
            product_action: {
                handler(val) {
                    const vm = this
                    vm.$set(vm, 'search_term', '')
                    vm.$set(vm, 'product_list', [])

                    /* calculate total */
                    let amount = 0
                    val.forEach(el => {
                        amount += parseFloat(el.t_total)
                    });
                    vm.$set(vm, 'total', amount)

                    vm.discount_percent_amount = this.discount + '%';
                    var discountType = $('.purchase_discount_type').val();

                    if (this.discount_all_type == "per_invoice" && discountType == 'fixed_value') {
                        var disAmount = this.discount;
                    } else {
                        var disAmount = (this.total * this.discount) / 100;
                    }

                    if (this.discount != 0) vm.$set(vm, 'discount_amount', disAmount);

                    if (this.discount_all_type == "per_item") vm.$set(vm, 'discount_amount', val.map(el => el
                        .discount_amount).reduce((a, b) => a + b, 0))

                    vm.$set(vm, 'product_json', JSON.stringify(val))

                    if (this.discount_all_type == "per_invoice") vm.$set(vm, 'total', amount - this
                        .discount_amount)
                    vm.setAmountRecieveStatus()
                },
                deep: true,
            },
            search_term(val) {
                !val && this.searchProduct()
            },

        },
        computed: {
            belongs_to_invoice: function() {
                return this.belongs_to == "Invoice";
            },
            isEnable: function() {
                return this.is_enable == 'true' || this.is_enable == 1;
            },
            searchRoute: function() {
                /* check if it has dependencies */
                var valInputForm = {}
                if (this.dependencies) {
                    this.dependencies.forEach(el => {
                        let elForm = form.find(`select[name="${el}"]`) ?? form.find(
                            `input[name="${el}"]`) ?? form.find(`textarea[name="${el}"]`)
                        if (elForm.val()) {
                            valInputForm[el] = elForm.val()
                            this.is_error = false;
                        } else {
                            this.notifyError(`Please choose required field to continue order`);
                            this.is_error = true;
                        }
                    })
                }

                let val = this.search_route
                if (val.includes('?')) {
                    val += "&search_term=" + this.search_term

                } else {
                    val += "?search_term=" + this.search_term
                }
                Object.keys(valInputForm).forEach(el => {
                    val += `&${el}=${valInputForm[el]}`
                })
                if (this.model) {
                    val += "&model=" + this.model
                }
                return val;
            }
        },
        methods: {
            async searchProduct() {
                const vm = this
                vm.is_loaded = false
                try {
                    const response = await axios.get(vm.searchRoute)
                    if (response.status == 200) {
                        const {
                            data: {
                                data
                            }
                        } = response
                            !vm.is_error && vm.$set(vm, 'product_list', data)

                        vm.$set(vm, 'is_loaded', true)
                    }
                } catch (error) {
                    vm.$set(vm, error, true)
                    vm.notifyError('somthing went wrong')
                    vm.$set(vm, 'is_loaded', true)
                }
            },
            updateContext(text = '', context = null, id = null, el = null) {
                const vm = this

                if (vm.isEnable && !vm.checkIfProperProductQty(id, context, text)) {
                    vm.notifyError('We are unable to process this order if the product is out of stock.');
                    /* map actions */
                    vm.setSpecificObjectProduct(id, {
                        qty: 'stock_qty'
                    })
                    return;
                }
                /* check if return product */
                if (!vm.isEnable && vm.model != '') {
                    if (!vm.checkIfProductHasProperQtyToReturn(id, context, text)) {
                        vm.notifyError('The customer has only ordered this amount and cannot increase it.');
                        vm.setSpecificObjectProduct(id, {
                            qty: 'stock_qty'
                        })
                        return;
                    }
                }

                var mapProduct = vm.product_action.map((el, index) => {
                    const funDis = (amount) => el.dis_type == "percent" ? (amount - (amount * el
                        .discount) / 100) : amount - el.discount;

                    const calDiscountAmount = (amount) => el.dis_type == "percent" ? (amount * el
                        .discount) / 100 : el.discount;

                    if (el.id == id || id == null) {
                        if (context) el[context] = text
                        /* check if discount greater than 100 when discount type trgger */
                        if (el.dis_type == 'percent' && el.discount > 100 || el.discount < 0) {
                            el.discount = 0;
                        }

                        if (vm.belongs_to_invoice) {
                            el.t_total = parseFloat(funDis(el.sell_price * el.qty))
                            el.t_total_full_price = parseFloat(el.sell_price * el.qty)
                            el.discount_amount = parseFloat(calDiscountAmount(el.t_total_full_price))

                        } else {
                            el.t_total = parseFloat(funDis(el.cost_price * el.qty))
                            el.t_total_full_price = parseFloat(el.cost_price * el.qty)
                            el.discount_amount = parseFloat(calDiscountAmount(el.t_total_full_price))
                        }
                        return el
                    }
                    return el;
                })
                vm.$set(vm, 'product_action', mapProduct)
            },
            setSpecificObjectProduct(id, setTo) {
                const vm = this
                var afterQtyChanges = vm.product_action.map(element => {
                    if (element.id == id && typeof(setTo) == "object") {
                        Object.keys(setTo).forEach(el => {
                            element[el] = element[setTo[el]]
                        })
                    }
                    return element;
                })
                vm.$set(vm, 'product_action', afterQtyChanges)
            },
            setAmountRecieveStatus(val = null) {
                $('#payment-status-alter').val(this.paymentObjectStatus[this.getAmountRecieveStatus(val)])
            },
            checkIfProperProductQty(id, context, text) {
                if (context == 'qty') {
                    let selected_product = this.product_action.find(el => el.id == id)
                    if (selected_product.pre_order == "No" && (parseInt(text) > selected_product.stock_qty)) {
                        return false
                    }
                }
                return true;
            },
            checkIfProductHasProperQtyToReturn(id, context, text) {
                if (context == 'qty') {
                    let selected_product = this.product_action.find(el => el.id == id)
                    if (parseInt(text) > selected_product.stock_qty) {
                        return false;
                    }
                }
                return true;
            },
            getAmountRecieveStatus(val = null) {
                const vm = this
                var recieve_temp = val ?? vm.amount_recieve
                const value = vm.total - recieve_temp
                if (recieve_temp == 0 || recieve_temp == null) {
                    return 'pending'
                }
                if (value <= 0) {
                    return 'paid'
                } else if (value > 0) {
                    return 'partially_paid';
                }
                return ''
            },
            removeFromList(id) {
                this.alertConfirmation((val) => {
                    if (val) {
                        let product = this.product_action.filter(el => el.id !== id)
                        this.$set(this, 'product_action', product)
                        this.updateContext()
                    }
                }, "Are you sure to remove this product from the list?")
            },

            selectProduct(id) {
                const vm = this
                /* check if already present */
                if (vm.product_action.find(el => el.id == id)) {
                    vm.removeFromList(id)
                    return;
                }
                let selected_product = this.product_list.find(el => el.id == id)
                selected_product.qty = 1
                selected_product.discount = 0;
                selected_product.discount_amount = 0
                selected_product.dis_type = "fix_val"
                if (vm.belongs_to_invoice) {
                    selected_product.t_total = selected_product.qty * selected_product.sell_price
                } else {
                    selected_product.t_total = selected_product.qty * selected_product.cost_price
                }
                vm.product_action.push(selected_product)

                vm.updateContext()

            },

            notifyError(text) {
                new Noty({
                    text,
                    type: "error"
                }).show();
            },
            resetDiscountProductDetail() {
                this.updateContext(0, 'discount')
                this.updateContext(0, 'discount_amount')
            },
            convertUSDToKhmer(number) {
                // return
            },
            notifyError(text) {
                new Noty({
                    text,
                    type: "error"
                }).show();
            },
            numberFormat(number) {
                const opt = {
                    style: "currency",
                    currency: "USD",
                }
                var numberFormat = new Intl.NumberFormat("en-US", opt);
                return numberFormat.format(number)
            },

            alertConfirmation(cbSuccess, text = "") {
                swal({
                        title: "Warning!",
                        text: text ?? "Are you sure to continue?",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "No",
                                value: null,
                                visible: true,
                                className: "bg-secondary",
                                closeModal: true,
                            },
                            restore: {
                                text: "Yes",
                                value: true,
                                visible: true,
                                className: "bg-success",
                            }
                        },
                    })
                    .then(async (value) => {
                        cbSuccess(value)
                    });
            }
        }
    });
</script>
@endpush

@push('after_styles')
{{-- Remove style and class when enable all field show error --}}
<style>
    .has-search .form-control {
        padding-left: 2.375rem;
    }

    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }

    select:disabled {
        background: #8a8a8a21 !important;
    }


    :root {
        --border-radius: 10px;
    }

    .CardInner {
        padding: 7px 7px;
        background-color: #e9ecef;
        border-radius: 2px;
    }

    .customInput .container {
        display: flex;
    }

    .Icon {
        min-width: 46px;
        min-height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--border-radius);
        margin-right: 12px;
        box-shadow: -2px -2px 6px rgba(255, 255, 255, 0.6), 2px 2px 12px #c8d8e7;
    }

    .Icon svg {
        transform: translate(-1px, -1px);
    }

    .customInput h5 {
        color: #6c757d;
    }

    .InputContainer {
        width: 100%;
    }

    .customInput input {
        padding: 10px 15px;
        border: none;
        display: block;
        font-weight: 600;
        color: #a9b8c9;
        transition: all 240ms ease-out;
        width: 100%;
    }

    .customInput input::placeholder {
        color: #6d7f8f;
    }

    .customInput input:focus {
        outline: none;
        color: #6d7f8f;
        /* background-color: #eff5fa; */
    }

    .InputContainer {
        --top-shadow: inset 1px 1px 3px #c5d4e3, inset 2px 2px 6px #c5d4e3;
        --bottom-shadow: inset -2px -2px 4px rgba(255, 255, 255, 0.7);
        position: relative;
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .InputContainer:before,
    .InputContainer:after {
        left: 0;
        top: 0;
        display: block;
        content: "";
        pointer-events: none;
        width: 100%;
        height: 100%;
        position: absolute;
    }

    .InputContainer:before {
        box-shadow: var(--bottom-shadow);
    }

    .InputContainer:after {
        box-shadow: var(--top-shadow);
    }
</style>
@endpush