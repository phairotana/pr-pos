<!-- field_type_name -->
<div class="form-group col-md-12">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr style="background: #626161; color: #fff;">
                        <td style="border-right: 1px solid #fff;">#</td>
                        <td style="border-right: 1px solid #fff;">Product Code</td>
                        <td style="border-right: 1px solid #fff;">Product Name</td>
                        <td style="border-right: 1px solid #fff;">Qty</td>
                    </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) --}}
{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

@push('after_styles')
    <!-- no styles -->
    {{-- Remove style and class when enable all field show error --}}
    <style>
        .label-required { color:#ff0000; }
        .no-error-border { border-color: #d2d6de !important; }
        .no-error-label { color: #333 !important; }
    </style>
@endpush


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('after_scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $(function(){
            $('body').on('change', 'select[name="ref_id"]', function(){
                var invoiceId = $(this).val();
                axios.get('{{ URL("api/invoice-detail") }}',{
                    params: {
                        invoice_id: invoiceId
                    }
                }).then(function(response) {
                    var html = '';
                    $.each(response.data, function(key, value){
                        html += '<tr>';
                            html += '<td>'+(key+1)+'</td>';
                            html += '<td>'+value.product.product_code+'</td>';
                            html += '<td>'+value.product.product_name+'</td>';
                            html += '<td>';
                                html += '<input type="hidden" value="'+value.product.id+'" name="product_id[]"/>';
                                html += '<input type="hidden" value="'+value.product.product_code+'" name="product_code[]"/>';
                                html += '<input type="number" data-product-id="'+value.product.id+'" data-invoice-id="'+invoiceId+'" value="0" name="qty[]" class="form-control change-qty" style="width: 100px;background-color: #f2f2f2 !important;"/>';
                            html += '</td>';
                        html += '</tr>';
                    });
                    $('#tbody').html(html);
                });
            });
            $('body').on('keyup', '.change-qty', function(){
                var me = $(this);
                if($(this).val() != '' && $(this).val() > 0){
                    axios.get('{{ URL("api/invoice-detail") }}',{
                        params: {
                            check_qty: true,
                            current_value: $(this).val(),
                            invoice_id: $(this).attr('data-invoice-id'),
                            product_id: $(this).attr('data-product-id')
                        }
                    }).then(function(response) {
                        if(response.data){
                            me.val(response.data);
                        }
                    }); 
                }
            })
        });
    </script>

@endpush
{{-- @endif --}}
{{-- Note: most of the times you'll want to use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load CSS/JS once, even though there are multiple instances of it. --}}
