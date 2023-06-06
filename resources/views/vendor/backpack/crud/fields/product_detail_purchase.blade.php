@php
$fieldLabel = $field['label'] ?? \Str::title($filed['name']);
$fieldOldValue = old('product_detail') ?? json_decode(old('product_detail'));
@endphp

<div id="vue_app_product" class="form-group col-md-12 div-search">

    <div class="form-group has-search">
        <b>@{{ label_field }}</b>
        @csrf
        <span class="la la-search form-control-feedback"></span>
        <div class="form-group-action">
            <input v-model="search_term" @keyup="searchProduct" type="search"
                class="form-control form-control-load typeahead with-loading-action" id="searchDropdownMenuLink"
                placeholder="Search">
            <div v-if="!is_loaded">@include('component.loading_spinner')</div>
        </div>
        <ul class="list-group mt-1 w-full">
            <li v-for="{product_name, product_code, id} in product_list" @click="selectProduct(id)"
                class="list-group-item list-group-item-action" style="cursor: pointer">@{{ product_name }} <font
                    color='grey'>
                    #@{{ product_code }}</font>
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
                        <th scope="col">Product code</th>
                        <th scope="col">Name</th>
                        <th scope="col">Note</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Cost price</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Total</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <td v-if="!product_action.length" class="text-center" valign="top" colspan="8"
                        class="dataTables_empty">Please search and select product</td>
                    <tr
                        v-for="{ product_name, product_code, id, qty, note, cost_price, t_total, discount } in product_action">
                        <th>@{{ product_code }}</th>
                        <td>@{{ product_name }}</td>
                        <td><input class="form-control form-md" @keyup="updateContext($event.target.value, 'note', id)"
                                :value="note"></td>
                        <td><input class="form-control form-sm" @keyup="updateContext($event.target.value, 'qty', id)"
                                :value="qty" type="number" min="1"></td>
                        <td><input class="form-control form-sm"
                                @keyup="updateContext($event.target.value, 'cost_price', id)" :value="cost_price"></td>
                        <td><input class="form-control form-sm"
                                @keyup="updateContext($event.target.value, 'discount', id)" :value="discount"
                                :disabled="disable_discount_input" type="number"></td>
                        <td>@{{ numberFormat(t_total) }}</td>
                        <td><a class="btn btn-sm btn-danger" @click="removeFromList(id)"><em
                                    class="la la-close text-white"></em></a> </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="offset-md-8 col-md-4 mt-4">
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td class="bold">Discount</td>
                        <td class="text-right">@{{ numberFormat(discount_amount) }}</td>
                        <td class="text-right">@{{ numberFormat(discount_amount_kh).replace('$', '៛ ') }}</td>
                    </tr>
                    <tr>
                        <td><span class="font-weight-bold">Grand Total</span></td>
                        <td class="text-right"><span class="font-weight-bold">@{{ numberFormat(total) }}</span></td>
                        <td class="text-right">@{{ numberFormat(total_kh).replace('$', '៛ ') }}</td>
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
        let khValue = " {{ convertKhUnit() }} "

        const vueProductDetail = new Vue({
            el: "#vue_app_product",
            data() {
                return {
                    label_field: "{{ $fieldLabel }}" ?? 'Product details',
                    product_list: [],
                    search_term: '',
                    product_action: [],
                    total: 0,
                    total_kh: 0,
                    discount_all_type: 'per_invoice',
                    discount_amount: 0,
                    product_json: null,
                    amount_recieve: 0,
                    paymentObjectStatus: {
                        paid: 'Paid',
                        partially_paid: "Partially paid",
                        pending: 'Pending'
                    },
                    disable_discount_input: false,
                    discount: 0,
                    error: false,
                    discount_amount_kh: 0,
                    is_loaded: true,
                }
            },
            watch: {
                total: function(val) {
                    this.$set(this, 'total_kh', val * parseFloat(khValue))
                },
                discount_amount: function(val) {
                    this.$set(this, 'discount_amount_kh', val * parseFloat(khValue))
                },
                amount_recieve(val) {
                    this.setAmountRecieveStatus(val)
                },
                discount_all_type(val) {
                    if (val != "per_invoice") {
                        this.$set(this, 'disable_discount_input', false)
                    } else {
                        this.$set(this, 'disable_discount_input', true)
                    }
                },
                amount_recieve(val) {
                    this.setAmountRecieveStatus(val)
                },
                discount_all_type(val) {
                    if (val != "per_invoice") {
                        this.$set(this, 'disable_discount_input', false)
                    } else {
                        this.$set(this, 'disable_discount_input', true)
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
                            amount += el.t_total
                        });

                        vm.$set(vm, 'total', amount)

                        if (this.discount != 0) vm.$set(vm, 'discount_amount', (this.total * this.discount) / 100);

                        vm.$set(vm, 'product_json', JSON.stringify(val))
                        vm.$set(vm, 'total', amount - this.discount_amount)
                        vm.setAmountRecieveStatus()
                    },
                    deep: true,
                },
                search_term(val) {
                    !val && this.searchProduct()
                },

            },
            methods: {
                async searchProduct() {
                    const vm = this
                    vm.is_loaded = false
                    try {
                        const response = await axios.get(
                            `{{ route('admin.api.product_search') }}?search_term=${this.search_term}&show_all=true`
                        )
                        if (response.status == 200) {
                            const {
                                data: {
                                    data
                                }
                            } = response
                            vm.$set(vm, 'product_list', data)
                            vm.$set(vm, 'is_loaded', true)
                        }
                    } catch (error) {
                        vm.$set(vm, error, true)
                        vm.notifyError('somthing went wrong')
                        vm.$set(vm, 'is_loaded', true)
                    }
                },
                updateContext(text = '', context = null, id = null) {
                    const vm = this
                    var mapProduct = vm.product_action.map((el, index) => {
                        if (el.id == id || id == null) {
                            if (context) el[context] = text
                            el.t_total = el.qty * el.cost_price - el.discount
                            return el
                        }

                        return el;
                    })

                    /* discount */

                    vm.$set(vm, 'product_action', mapProduct)
                },
                removeFromList(id) {
                    this.alertConfirmation((val => {
                        if (val) {
                            let product = this.product_action.filter(el => el.id !== id)
                            this.$set(this, 'product_action', product)
                            this.updateContext()
                        }
                    }))
                },
                setAmountRecieveStatus(val = null) {
                    $('#payment-status-alter').val(this.paymentObjectStatus[this.getAmountRecieveStatus(val)])
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
                    selected_product.t_total = selected_product.qty * selected_product.cost_price
                    vm.product_action.push(selected_product)

                    vm.updateContext()

                },
                resetDiscountProductDetail() {
                    this.updateContext(0, 'discount')
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
            },
            mounted() {
                const vm = this
                @if ($fieldOldValue)
                    vm.$set(vm, 'product_action', JSON.parse(@json($fieldOldValue)))
                @endif
                if (vm.discount_all_type == "per_invoice") vm.disable_discount_input = true
            }
        })
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

    </style>
@endpush
