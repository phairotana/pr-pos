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
        <div class="table-responsive">

            <table class="table border mt-3">
                <thead>
                    <tr>
                        <th scope="col">Actions</th>
                        <th scope="col">Product code</th>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <td v-if="!product_action.length" class="text-center" valign="top" colspan="8"
                        class="dataTables_empty">Please search and select product</td>
                    <tr v-for="{ product_name, product_code, id, qty } in product_action">
                        <td>
                            <a class="btn btn-sm btn-success kjm" :href="'/admin/product/print-barcode/' + id"
                                target="_blank">
                                <em class="las la-barcode"></em>
                            </a>
                            <a class="btn btn-sm btn-danger" @click="removeItem(id)"><em
                                    class="la la-trash"></em></a>
                        </td>
                        <th>@{{ product_code }}</th>
                        <td>@{{ product_name }}</td>
                        <td>@{{ qty }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('crud_fields_scripts')
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        $('#saveActions').remove();
        const vueProductDetail = new Vue({
            el: "#vue_app_product",
            data() {
                return {
                    label_field: "{{ $fieldLabel }}" ?? 'Product details',
                    product_list: [],
                    search_term: '',
                    product_action: [],
                    product_json: null,
                    error: false,
                    is_loaded: true,
                }
            },
            watch: {

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
                            // console.log(data);
                            vm.$set(vm, 'product_list', data)
                            vm.$set(vm, 'is_loaded', true)
                        }
                    } catch (error) {
                        vm.$set(vm, error, true)
                        vm.notifyError('somthing went wrong')
                        vm.$set(vm, 'is_loaded', true)
                    }
                },

                removeItem(id) {
                    let product = this.product_action.filter(el => el.id !== id)
                    this.$set(this, 'product_action', product)
                },

                selectProduct(id) {
                    const vm = this
                    console.log(this.discount, this.discount_amount);
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
                },

                notifyError(text) {
                    new Noty({
                        text,
                        type: "error"
                    }).show();
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

@section('before_scripts')

@endsection
