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

        {{-- End hidden input fields --}}
        <div class="table-responsive">

            <table class="table border mt-3">
                <thead>
                    <tr>
                        <th scope="col">Product code</th>
                        <th scope="col">Name</th>
                        <th scope="col">Note</th>
                        <th scope="col">Type</th>
                        <th scope="col">Qty</th>
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
                        <td>
                            <select @change="updateContext($event.target.value, 'type', id)" name="type" class="form-control form-md">
                                <option selected>Subtraction</option>
                                <option >Addition</option>
                            </select>
                        </td>

                        <td><input class="form-control form-sm" @keyup="updateContext($event.target.value, 'qty', id, 'int')"
                                :value="qty" type="number"></td>
                        <td><a class="btn btn-sm btn-danger" @click="removeFromList(id)"><em
                                    class="la la-close text-white"></em></a> </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="background-color: rgb(241, 241, 241)">
                        <th colspan="4" class="text-right">Total :</th>
                        <th colspan="2">@{{ parseInt(total) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>


    </div>
</div>

@push('crud_fields_scripts')
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        const vueProductDetail = new Vue({
            el: "#vue_app_product",
            data() {
                return {
                    label_field: "{{ $fieldLabel }}" ?? 'Product details',
                    product_list: [],
                    search_term: '',
                    product_action: [],
                    total: 0,
                    product_json: null,
                    error: false,
                    is_loaded: true
                }
            },
            watch: {

                product_action: {
                    handler(val) {
                        const vm = this
                        vm.$set(vm, 'search_term', '')
                        vm.$set(vm, 'product_list', [])

                        /* calculate total */
                        let amount = 0
                        val.forEach(el => {
                            amount += parseInt(el.qty);
                        });

                        vm.$set(vm, 'total', amount);
                        vm.$set(vm, 'product_json', JSON.stringify(val))
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
                            `{{ route('admin.api.product_search') }}?search_term=${this.search_term}`)
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
                updateContext(text = '', context = null, id = null, parser = null) {
                    const vm = this
                    var mapProduct = vm.product_action.map((el, index) => {
                        if (el.id == id || id == null) {
                            if (context) el[context] = vm.mapParser(parser, text)
                            return el
                        }
                        return el;
                    })
                    /* discount */
                    vm.$set(vm, 'product_action', mapProduct)
                },
                removeFromList(id) {
                    let product = this.product_action.filter(el => el.id !== id)
                    this.$set(this, 'product_action', product)
                    this.updateContext()
                },
                mapParser(parser, context){
                    if(parser == 'int'){
                        if(context == '' || context == null )  return 0;
                        return parseInt(context) ?? 0
                    }else if(parser == 'float'){
                        return parseFloat(context) ?? 0.0
                    }
                    return context;
                },
                selectProduct(id) {
                    const vm = this
                    /* check if already present */
                    if (vm.product_action.find(el => el.id == id)) {
                        return;
                    }
                    let selected_product = this.product_list.find(el => el.id == id)
                    selected_product.qty = 1
                    selected_product.type = 'Subtraction'
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
                },
                numberFormat(number) {
                    var numberFormat = new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: "USD",
                    });
                    return numberFormat.format(number)
                }
            },
            mounted() {
                const vm = this
                @if ($fieldOldValue)
                    vm.$set(vm, 'product_action', JSON.parse(@json($fieldOldValue)))
                @endif

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
