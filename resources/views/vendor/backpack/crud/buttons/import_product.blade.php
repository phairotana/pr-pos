@if ($crud->hasAccess('create'))
    <a href="javascript:void(0)"
        class="btn btn-secondary"
        data-button-type="import" data-toggle="modal" data-target="#import-p-modal">
        <span class="ladda-label"><i class="la la-cloud-download-alt"></i> Import Excel </span>
    </a>


    @if (session()->has('success'))
        <input type="hidden" id="alert-success" value="{{ session()->get('success') }}">
    @endif

    @if ($errors->any())
        {{ dd($errors->all()) }}
    @endif

    <!-- Modal -->
    <div class="modal fade" id="import-p-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose excel file to import</h5>
                    <button type="button " class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <input id="import-bonus-excel-file" name="file" type="file" accept=".xls, .xlsx, .csv" class="form-control">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="start-progress-import-bonus-excel" required class="btn btn-primary submit-file">
                        <i class="la la-spinner la-spin d-none" id="spinner-border"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('after_scripts')
    <script>

        if (typeof importTransaction != 'function') {
            $("[data-button-type=import]").unbind('click');
            $('body').on('click', '#start-progress-import-bonus-excel', function(){
                var me = $(this);
                var jform = new FormData();

                if($('#import-bonus-excel-file').val() == ''){
                    new Noty({type: 'error',text: 'Please choose upload file.'}).show();
                    return false;
                }
                me.attr('disabled','disabled');
                 // stop progressing spinner
                $('#spinner-border').removeClass('d-none')
                jform.append('file',$('#import-bonus-excel-file').get(0).files[0]);
                $.ajax({
                    url: "{{ route('products-import') }}",
                    type: 'POST',
                    data: jform,
                    dataType: 'json',
                    mimeType: 'multipart/form-data', // this too
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(result) {
                        // stop progressing spinner
                        $('#spinner-border').addClass('d-none')
                        crud.table.ajax.reload();
                        new Noty({type: result.messageType,text: result.message}).show();
                    },
                    error: function(result) {
                        // Show an alert with the result

                        console.error("error")

                        new Noty({
                            text: "The new entry could not be created. Please try again.",
                            type: "warning"
                        }).show();
                    }
                })
            });
            $(document).on('show.bs.modal', '.modal', function() {
                $(this).appendTo('body');
            });
        }
    </script>
@endpush
