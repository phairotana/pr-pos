@extends('layouts.app')

@section('content')
    @if ($crud->hasAccess('list'))
        <a href="{{ url($crud->route) }}" class="hidden-print"><em class="la la-angle-double-left"></em>
            Back to all <span>{{ $crud->entity_name_plural }}</span></a><br><br>
        <h3>Print barcode </h3>
    @endif
    <div class="box bg-white" id="app">
        <div class="box-body">
            <div>
                <div class="p-3">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-md-6">
                                @component('inc.select2_from_array', [
                                    'name' => 'branchs',
                                    'options' => $branch,
                                    'class' => 'co',
                                    'placeholder' => 'Select a branch',
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                <div class="form-group col-12 col-sm-6 col-md-12">
                                    <label for="Product"> Product</label>
                                    <select type="text" id="itemName" name="itemName" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <th scope="">Product</th>
                                <th scope="">Code Product</th>
                                <th scope="">Quantity</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('after_styles')

@endsection

@section('after_scripts')
    <script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('.itemName').select2({
            placeholder: 'Select an item',
            ajax: {
                url: 'data-ajax',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.product_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endsection
