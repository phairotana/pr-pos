<div class="card card-border-top">
    <div class="card-header"><strong>Top Selling Products</strong></div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table text-nowrap overflow-auto mt-3" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="">Products</th>
                            <th scope="">Quantities</th>
                            <th scope="">Grand Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@section('after_styles')
    @include('inc.datatable_styles')
@endsection

@section('after_scripts')
    @include('inc.datatable_scripts')
@endsection
