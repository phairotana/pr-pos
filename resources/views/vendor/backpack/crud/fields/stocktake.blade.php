@php
$dependencies = $field['dependencies'] ?? [];
$sourceRoute = $field['source_route'] ?? route('admin.api.product_search');
$model = $field['model'] ?? '';
@endphp

<div id="vueApp" class="form-group col-md-12 div-search">
    <input id="scanbarcode" class="toggle" type="checkbox">
    <label for="scanbarcode" class="lbl-toggle" tabindex="0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
            <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z" />
            <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z" />
            <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z" />
            <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z" />
            <path d="M12 9h2V8h-2v1Z" />
        </svg> <span>Scan QR/Barcode Code</span>
    </label>
    <div class="collapsible-content">
        <div id="reader"></div>
    </div>

    <div class="form-group has-search mt-3">
        @csrf

        <div class="CardInner customInput">
            <h5>Search for your item?</h5>
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
            <li v-for="{product_name, product_code, id, qty} in product_list" @click="selectProduct(id,qty)" class="list-group-item list-group-item-action" style="cursor: pointer;">
                <span v-text="product_name"></span><span class="text-muted" v-text="' #' + product_code"></span>
            </li>
        </ul>
        <input type="hidden" name="product_detail" :value="product_json">

        <div class="table-responsive mt-2 mb-3">
            <table class="table table-stripe">
                <thead class="bg-light">
                    <tr>
                        <th class="text-nowrap" scope="col">#</th>
                        <th class="text-nowrap" scope="col">Categories</th>
                        <th class="text-nowrap" scope="col">Item Name</th>
                        <th class="text-nowrap" scope="col">Expected</th>
                        <th class="text-nowrap" scope="col">Counted</th>
                        <th class="text-nowrap" scope="col">Difference</th>
                        <th class="text-nowrap" scope="col">Noted</th>
                        <th class="text-nowrap" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <td v-if="!product_action.length" class="text-center" valign="top" colspan="8" class="dataTables_empty">Please search and select product</td>
                    <tr scope="row" v-for="({ product_name, product_code, stock_qty , id, qty,qty_counted, note},index) in product_action">

                        <td class="font-weight-bold" v-text="(index + 1)"></td>
                        <td v-text="product_code"></td>
                        <td v-text="product_name"></td>
                        <td v-text="stock_qty" class="font-weight-bold"></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button onclick="incrementQty(this)" :data-id="id" type="button" class="btn btn-outline-primary btn-sm"><i class="la la-plus"></i></button>
                                <button onclick="decrementQty(this)" :data-id="id" type="button" class="btn btn-outline-success btn-sm"><i class="la la-minus"></i></button>
                                <input type="number" pattern="[0-9]" onkeydown="return event.keyCode !== 190" class="form-control form-control-sm pr-keyinput input-outline" onkeyup="counted(this)" :data-id=id :value=qty>
                            </div>
                        </td>
                        <td v-if="qty_counted == 0" class="font-weight-bold text-primary" data-label="Difference">
                            <em class="la la-check la-lg fw-bolder" aria-hidden="true"></em>
                        </td>
                        <td v-else-if="qty_counted > 0" class="font-weight-bold text-warning" data-label="Difference" v-text="qty_counted"></td>
                        <td v-else class="font-weight-bold text-danger" data-label="Difference" v-text="qty_counted">
                        </td>
                        <td><input class="form-control form-control-sm input-outline" @keyup="updateContext($event.target.value, 'note', id)" :value="note"></td>
                        <td>
                            <a @click="removeFromList(id)"><i class="la la-trash-o la-lg text-danger" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @php
        $id = Auth::user()->id;
        $name = Auth::user()->name;
        $actiondate = Carbon\Carbon::now();
        @endphp
        <div class="CardInner p-3">
            <div class="row mb-2">
                <label for="colFormLabelSm" class="col-sm-2 col-form-label">Action By</label>
                <div class="col-sm-10">
                    <input readonly disabled type="text" class="form-control" id="colFormLabelSm" value="{{ $name }}">
                </div>
            </div>
            <div class="row mb-2">
                <label for="colFormLabel" class="col-sm-2 col-form-label">Action Date</label>
                <div class="col-sm-10">
                    <input readonly disabled type="text" class="form-control" id="colFormLabel" value="{{ $actiondate }}">
                </div>
            </div>
            <div class="row">
                <label for="colFormLabelLg" class="col-sm-2 col-form-label">Remarts</label>
                <div class="col-sm-10">
                    <textarea name="remarks" class="form-control" rows="1" placeholder="Enter remarks"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after_styles')
<style>
    input[type="number"].pr-keyinput {
        width: 100px;
    }

    #reader {
        width: 30%;
    }

    #reader>div:nth-of-type(1) {
        display: none !important;
    }

    .input-outline {
        border-color: #96d3ec !important
    }

    .form-control:focus {
        border-color: #96d3ec;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

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

    #reader__dashboard {
        line-height: 35px;
    }

    #reader__dashboard_section_csr button {
        background-color: #fff;
        border: 1px solid #d5d9d9;
        border-radius: 8px;
        box-shadow: rgba(213, 217, 217, .5) 0 2px 5px 0;
        box-sizing: border-box;
        color: #0f1111;
        cursor: pointer;
        display: inline-block;
        font-family: "Amazon Ember", sans-serif;
        font-size: 13px;
        line-height: 29px;
        padding: 0 10px 0 11px;
        position: relative;
        text-align: center;
        text-decoration: none;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        vertical-align: middle;
    }

    #reader__dashboard_section_csr button:hover {
        background-color: #008296;
    }

    #reader__dashboard_section_csr button:focus {
        border-color: #008296;
        box-shadow: rgba(213, 217, 217, .5) 0 2px 5px 0;
        outline: 0;
    }

    input[type='checkbox'] {
        display: none;
    }

    .lbl-toggle {
        display: block;
        font-weight: bold;
        font-family: monospace;
        font-size: 1.2rem;
        text-transform: uppercase;
        padding: 0.4rem;
        color: #5e6b76;
        background: #e9ecef;
        cursor: pointer;
        border-radius: 2px;
        transition: all 0.25s ease-out;
    }

    .lbl-toggle:hover {
        color: #0071b4;
    }

    .lbl-toggle::before {
        content: ' ';
        display: inline-block;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-left: 5px solid currentColor;
        vertical-align: middle;
        margin-right: .7rem;
        transform: translateY(-2px);
        transition: transform .2s ease-out;
    }

    .toggle:checked+.lbl-toggle::before {
        transform: rotate(90deg) translateX(-3px);
    }

    .collapsible-content {
        max-height: 0px;
        overflow: hidden;
        transition: max-height .25s ease-in-out;
    }

    .toggle:checked+.lbl-toggle+.collapsible-content {
        max-height: 350px;
    }

    .toggle:checked+.lbl-toggle {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .collapsible-content .content-inner {
        background: #fff;
        margin-bottom: 15px;
        padding: .5rem 1rem;
        border-bottom-left-radius: 2px;
        border-bottom-right-radius: 3px;
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

@push('crud_fields_scripts')
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.esm.js.map"></script>
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

<!-- Vue Script -->
<script type="text/javascript">
    var element = $(`input[name="{{ $field['name'] }}"]`)
    var dependencies = <?php echo json_encode($dependencies); ?>;
    var form = element.closest('form');
    var app = new Vue({
        el: "#vueApp",
        data() {
            return {
                product_list: [],
                search_term: '',
                product_action: [],
                is_error: false,
                product_json: null,
                error: false,
                is_loaded: true,
                search_route: "{{ $sourceRoute }}",
                model: "{{ $model }}",
                dependencies: dependencies,
            }
        },
        watch: {
            product_action: {
                handler(val) {
                    const vm = this
                    vm.$set(vm, 'search_term', '')
                    vm.$set(vm, 'product_list', [])
                    vm.$set(vm, 'product_json', JSON.stringify(val))
                },
                deep: true,
            },
            search_term(val) {
                !val && this.searchProduct()
            },

        },
        computed: {
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
                var mapProduct = vm.product_action.map((el, index) => {
                    if (el.id == id || id == null) {
                        if (context) el[context] = text
                        return el
                    }
                    return el;
                })
                vm.$set(vm, 'product_action', mapProduct)
            },
            selectProduct(id) {
                const vm = this
                /* check if already present */
                if (vm.product_action.find(el => el.id == id)) {
                    if (selected_product = vm.product_action.find(el => el.id == id)) {
                        selected_product.qty += 1;
                        selected_product.qty_counted = selected_product.qty - selected_product.stock_qty
                        vm.updateContext()
                        return;
                    }
                }
                var selected_product = this.product_list.find(el => el.id == id)
                selected_product.qty = 0
                selected_product.note = null
                selected_product.qty_counted = selected_product.qty - selected_product.stock_qty
                vm.product_action.push(selected_product)
                vm.updateContext()
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
            notifyError(text) {
                new Noty({
                    text,
                    type: "error"
                }).show();
            },
            notifyError(text) {
                new Noty({
                    text,
                    type: "error"
                }).show();
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
    })
</script>

<!-- JQuery Script -->
<script>
    $('body').removeClass('sidebar-lg-show');
    let myLabels = document.querySelectorAll('.lbl-toggle');
    Array.from(myLabels).forEach(label => {
        label.addEventListener('keydown', e => {
            if (e.which === 32 || e.which === 13) {
                e.preventDefault();
                label.click();
            };
        });
    });

    var vm = app;

    function counted(field) {
        let value = $.isNumeric(field.value) ? parseInt(field.value) : 0
        let id = $(field).attr('data-id')

        /* check if already present */
        if (selected_product = vm.product_action.find(el => el.id == id)) {
            selected_product.qty = value
            selected_product.qty_counted = value - selected_product.stock_qty
            vm.updateContext()
            return;
        }
    }

    function incrementQty(field) {
        let id = $(field).attr('data-id')
        if (selected_product = vm.product_action.find(el => el.id == id)) {
            selected_product.qty += 1;
            selected_product.qty_counted = selected_product.qty - selected_product.stock_qty
            vm.updateContext()
            return;
        }
    }

    function decrementQty(field) {
        let id = $(field).attr('data-id')
        if (selected_product = vm.product_action.find(el => el.id == id)) {
            selected_product.qty -= 1;
            selected_product.qty_counted = selected_product.qty - selected_product.stock_qty
            vm.updateContext()
            return;
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        axios.get("{{ route('product.fetching') }}", {
            params: {
                code: `${decodedText}`,
            }
        }).then(function(response) {
            if (selected_product = vm.product_action.find(el => el.id == response.data[0].id)) {
                selected_product.qty += 1;
                selected_product.qty_counted = selected_product.qty - selected_product.stock_qty
                vm.updateContext()
                return;
            } else {
                vm.product_action.push(vm.product_list)
                response.data[0].qty = 0
                response.data[0].qty_counted = 0
                response.data[0].note = null
                vm.$set(vm, 'product_action', response.data)
                vm.updateContext()
            }
        });
    }
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10,
            qrbox: {
                width: 300,
                height: 300
            }
        },
        /* verbose= */
        false);
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush