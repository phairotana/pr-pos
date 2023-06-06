<script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
<script>
    function select2SingleOption(id, url, return_obj, method = 'GET', placeholder = '-') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(id).select2({
            theme: 'bootstrap',
            multiple: false,
            placeholder: placeholder,
            minimumInputLength: "0",
            ajax: {
                url: url,
                method: method,
                dataType: 'json',
                quietMillis: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;

                    return {
                        results: [{
                            id: 0,
                            text: "-"
                        }, ...$.map(data.data, function(item) {
                            return return_obj(item);
                        })],
                        more: data.current_page < data.last_page
                    };
                },
                cache: true
            },
        });
    }
</script>
