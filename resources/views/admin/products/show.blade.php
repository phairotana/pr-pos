@extends('layouts.app')

@section('content')
    @if ($crud->hasAccess('list'))
        <a href="{{ url($crud->route) }}" class="hidden-print"><em class="la la-angle-double-left"></em>
            Back to all <span>{{ $crud->entity_name_plural }}</span></a><br><br>
    @endif
    <div class="box bg-white" id="app">
        <div class="box-body">
            <div>
                <div class="p-3">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-md-8">
                                @component('admin.products.product_info', compact('entry'))
                                @endcomponent
                            </div>
                            <div class="col-md-4">
                                @component('admin.products.product_barcode', compact('entry'))
                                @endcomponent
                                <br />
                                @component('admin.products.product_slide_image', compact('entry'))
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('after_styles')
    <style>
        @media print {
            body {
                color: #000;
                background: #fff;
            }
        }
    </style>
@endsection

@section('after_scripts')
    <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
    <script>
        $(".btn-print-barcode").on('click', function() {
            $(".to-print").print();
        });
    </script>
@endsection
