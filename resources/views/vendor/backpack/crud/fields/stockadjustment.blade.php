@php
$dependencies = $field['dependencies'] ?? [];
$sourceRoute = $field['source_route'] ?? route('admin.api.product_search');
$model = $field['model'] ?? '';
@endphp

<div id="vueApp" class="form-group col-md-12 div-search" loading="lazy">
    <input id="scanbarcode" class="toggle" type="checkbox">
    <label for="scanbarcode" class="lbl-toggle" tabindex="0">
        <svg t="1689504106856" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3168" width="22" height="22">
            <path d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448 448-200.6 448-448S759.4 64 512 64z m0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372 372 166.6 372 372-166.6 372-372 372z" p-id="3169" fill="#1296db"></path>
            <path d="M623.6 316.7C593.6 290.4 554 276 512 276s-81.6 14.5-111.6 40.7C369.2 344 352 380.7 352 420v7.6c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V420c0-44.1 43.1-80 96-80s96 35.9 96 80c0 31.1-22 59.6-56.1 72.7-21.2 8.1-39.2 22.3-52.1 40.9-13.1 19-19.9 41.8-19.9 64.9V620c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-22.7c0-19.7 12.4-37.7 30.9-44.8 59-22.7 97.1-74.7 97.1-132.5 0.1-39.3-17.1-76-48.3-103.3z" p-id="3170" fill="#1296db"></path>
            <path d="M512 732m-40 0a40 40 0 1 0 80 0 40 40 0 1 0-80 0Z" p-id="3171" fill="#1296db"></path>
        </svg><span> READ NOTE</span>
    </label>
    <div class="collapsible-content">
        <div class="content-inner">
            <div class="row">
                <label for="colFormLabelSm" class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    QTY</label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Quantity</strong> <small>is quantity in stock is the number of units of a product that are currently available for sales.</small></p>
                </div>
            </div>
            <div class="row">
                <label for="colFormLabelSm" class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    SI</label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Stock-In</strong> <small>involves receiving goods from suppliers, inspecting them, and recording them into the inventory.</small></p>
                </div>
            </div>
            <div class="row">
                <label for="colFormLabel" class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    SO</label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Stock-Out</strong> <small>is when a business runs out (sell out) of a product that a customer is ready to buy.</small></p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    QTY AFTER</label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Quantity After</strong> <small>refers to the quantity of a product that has been adjusted into or out of inventory.</small></p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    SI AFTER</label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Stock-In After </strong> <small>refers to the quantity of a purchase-in that has been adjusted into or out of inventory.</small></p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    SO AFTER</label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Stock-Out After</strong> <small>refers to the quantity of a sell-out that has been adjusted into or out of inventory.</small></p>
                </div>
            </div>

            <div class="row">
                <label class="col-sm-2"> <svg t="1689504791821" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3786" width="18" height="18">
                        <path d="M460.8 627.2c0-34.133333 4.266667-59.733333 12.8-81.066667 8.533333-21.333333 25.6-42.666667 46.933333-64 25.6-25.6 38.4-38.4 46.933334-51.2 8.533333-17.066667 17.066667-29.866667 17.066666-51.2s-4.266667-42.666667-17.066666-55.466666c-12.8-8.533333-29.866667-12.8-55.466667-12.8-21.333333 0-38.4 4.266667-51.2 17.066666-12.8 12.8-21.333333 29.866667-21.333333 51.2H349.866667c0-42.666667 17.066667-81.066667 46.933333-106.666666 29.866667-25.6 68.266667-38.4 115.2-38.4 51.2 0 89.6 12.8 119.466667 38.4 29.866667 25.6 42.666667 59.733333 42.666666 106.666666 0 42.666667-17.066667 81.066667-55.466666 119.466667l-46.933334 46.933333c-17.066667 17.066667-25.6 46.933333-25.6 81.066667h-85.333333z m-8.533333 115.2c0-12.8 4.266667-25.6 12.8-34.133333 8.533333-8.533333 21.333333-12.8 38.4-12.8s29.866667 4.266667 38.4 12.8 12.8 21.333333 12.8 34.133333c0 12.8-4.266667 25.6-12.8 34.133333-8.533333 8.533333-21.333333 12.8-38.4 12.8s-29.866667-4.266667-38.4-12.8c-8.533333-8.533333-12.8-21.333333-12.8-34.133333z" fill="#1296db" p-id="3787"></path>
                    </svg>
                    (+/-) M of QTY, SI, SO </label>
                <div class="col-sm-10">
                    <p id="colFormLabelSm"><span>: </span> <strong>Movements</strong> <small>This involves manually adding or subtracting the appropriate quantities from the inventory records.</small></p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group has-search mt-3">
        @csrf
        <div class="CardInner customInput">
            <h5>Search item to adjustment.</h5>
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
                        <th class="text-nowrap" scope="col">Action</th>

                        <th class="text-nowrap" scope="col">ITEMS</th>
                        <th class="text-nowrap" scope="col">QTY</th>
                        <th class="text-nowrap" scope="col">SI</th>
                        <th class="text-nowrap" scope="col">SO</th>

                        <th class="text-nowrap" scope="col">M QTY (+/-)</th>
                        <th class="text-nowrap" scope="col">M SI (+/-)</th>
                        <th class="text-nowrap" scope="col">M SO (+/-)</th>

                        <th class="text-nowrap" scope="col">QTY AFTER</th>
                        <th class="text-nowrap" scope="col">SI AFTER</th>
                        <th class="text-nowrap" scope="col">SO AFTER</th>

                    </tr>
                </thead>
                <tbody>
                    <td v-if="!product_action.length" class="text-center" valign="top" colspan="11" class="dataTables_empty">Please search and select product</td>
                    <tr scope="row" v-for="({ product_name, product_code, stock_qty , id, qty_movement,
                        si_movement, so_movement, qty_after, si_after, so_after, stock, },index)
                        in product_action">
                        <td>
                            <a @click="removeFromList(id)"><i class="la la-trash-o la-lg text-danger" aria-hidden="true"></i></a>
                        </td>
                        <td v-text=product_name></td>

                        <td class="font-weight-bold" v-text="stock_qty"></td>
                        <td class="font-weight-bold" v-text="stock.purchase"></td>
                        <td class="font-weight-bold" v-text="stock.sale_out"></td>

                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button onclick="incrementQty(this,'movement qty')" :data-id="id" type="button" class="btn btn-outline-primary btn-sm"><i class="la la-plus"></i></button>
                                <button onclick="decrementQty(this,'movement qty')" :data-id="id" type="button" class="btn btn-outline-success btn-sm"><i class="la la-minus"></i></button>
                                <input type="number" pattern="/^(|-?\d+)$/g" onkeydown="return event.keyCode !== 190" class="form-control form-control-sm enter-qty input-outline" onkeyup="movement(this, 'movement qty')" :data-id=id :value=qty_movement>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button onclick="incrementQty(this, 'movement si')" :data-id="id" type="button" class="btn btn-outline-primary btn-sm"><i class="la la-plus"></i></button>
                                <button onclick="decrementQty(this, 'movement si')" :data-id="id" type="button" class="btn btn-outline-success btn-sm"><i class="la la-minus"></i></button>
                                <input type="number" pattern="/^(|-?\d+)$/g" onkeydown="return event.keyCode !== 190" class="form-control form-control-sm enter-qty input-outline" onkeyup="movement(this, 'movement si')" :data-id=id :value=si_movement>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button onclick="incrementQty(this, 'movement so')" :data-id="id" type="button" class="btn btn-outline-primary btn-sm"><i class="la la-plus"></i></button>
                                <button onclick="decrementQty(this, 'movement so')" :data-id="id" type="button" class="btn btn-outline-success btn-sm"><i class="la la-minus"></i></button>
                                <input type="number" pattern="/^(|-?\d+)$/g" onkeydown="return event.keyCode !== 190" class="form-control form-control-sm enter-qty input-outline" onkeyup="movement(this, 'movement so')" :data-id=id :value=so_movement>
                            </div>
                        </td>
                        <td class="font-weight-bold text-danger" data-label="after" v-text="qty_after"></td>
                        <td class="font-weight-bold text-danger" data-label="after" v-text="si_after"></td>
                        <td class="font-weight-bold text-danger" data-label="after" v-text="so_after"></td>
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
                <label for="colFormLabelSm" class="col-sm-2">Action By</label>
                <div class="col-sm-10">
                    <input readonly disabled type="text" class="form-control" id="colFormLabelSm" value="{{ $name }}">
                </div>
            </div>
            <div class="row mb-2">
                <label for="colFormLabel" class="col-sm-2">Action Date</label>
                <div class="col-sm-10">
                    <input readonly disabled type="text" class="form-control" id="colFormLabel" value="{{ $actiondate }}">
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2">Remarts</label>
                <div class="col-sm-10">
                    <textarea name="remarks" class="form-control" rows="1" placeholder="Enter remarks"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after_styles')
<style>
    input[type="number"].enter-qty {
        width: 70px;
        text-align: center;
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
        max-height: 510px;
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

                        return;
                    }
                }
                var selected_product = this.product_list.find(el => el.id == id)
                var stock = selected_product.stock;

                selected_product.qty_movement = 0
                selected_product.si_movement = 0
                selected_product.so_movement = 0
                selected_product.qty_after = selected_product.stock_qty + selected_product.qty_movement;
                selected_product.si_after = stock.purchase + selected_product.si_movement;
                selected_product.so_after = stock.sale_out + selected_product.so_movement;

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
    var vm = app;
    let myLabels = document.querySelectorAll('.lbl-toggle');
    Array.from(myLabels).forEach(label => {
        label.addEventListener('keydown', e => {
            if (e.which === 32 || e.which === 13) {
                e.preventDefault();
                label.click();
            };
        });
    });

    function movement(field, type) {
        // let value = field.value
        let id = $(field).attr('data-id')
        var value = Number(field.value)
        if (selected_product = vm.product_action.find(el => el.id == id)) {
            var stock = selected_product.stock;

            if (type == 'movement qty') {
                selected_product.qty_movement = value;
                selected_product.qty_after = selected_product.stock_qty + selected_product.qty_movement;
            }
            if (type == 'movement si') {
                selected_product.si_movement = value;
                selected_product.si_after = stock.purchase + selected_product.si_movement;
            }
            if (type == 'movement so') {
                selected_product.so_movement = value;
                selected_product.so_after = stock.sale_out + selected_product.so_movement;
            }
            vm.updateContext()
            return;
        }
    }

    function incrementQty(field, type) {
        let id = $(field).attr('data-id')
        if (selected_product = vm.product_action.find(el => el.id == id)) {
            var stock = selected_product.stock;

            if (type == 'movement qty') {
                var value = Number(selected_product.qty_movement)
                selected_product.qty_movement = value + 1;
                selected_product.qty_after = selected_product.stock_qty + selected_product.qty_movement;
            }
            if (type == 'movement si') {
                var value = Number(selected_product.si_movement)
                selected_product.si_movement = value + 1;
                selected_product.si_after = stock.purchase + selected_product.si_movement;
            }
            if (type == 'movement so') {
                var value = Number(selected_product.so_movement)
                selected_product.so_movement = value + 1;
                selected_product.so_after = stock.sale_out + selected_product.so_movement;
            }
            vm.updateContext()
            return;
        }
    }

    function decrementQty(field, type) {
        let id = $(field).attr('data-id')
        if (selected_product = vm.product_action.find(el => el.id == id)) {
            var stock = selected_product.stock;

            if (type == 'movement qty') {
                var value = Number(selected_product.qty_movement)
                selected_product.qty_movement = value - 1;
                selected_product.qty_after = selected_product.stock_qty + selected_product.qty_movement;
            }
            if (type == 'movement si') {
                var value = Number(selected_product.si_movement)
                selected_product.si_movement = value - 1;
                selected_product.si_after = stock.purchase + selected_product.si_movement;
            }
            if (type == 'movement so') {
                var value = Number(selected_product.so_movement)
                selected_product.so_movement = value - 1;
                selected_product.so_after = stock.sale_out + selected_product.so_movement;
            }
            vm.updateContext()
            return;
        }
    }
</script>
@endpush