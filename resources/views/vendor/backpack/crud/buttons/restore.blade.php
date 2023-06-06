{{-- @if ($crud->hasAccess('restore')) --}}
	<a href="javascript:void(0)" onclick="restoreEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/restore') }}" class="btn btn-sm btn-success" data-button-type="restore" data-toggle="tooltip" title="Restore"><i class="la la-undo"></i></a>
{{-- @endif --}}

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if(request()->ajax()) @endpush @endif
<script>

	if (typeof restoreEntry != 'function') {
	  $("[data-button-type=restore]").unbind('click');

	  function restoreEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var button = $(button);
		var route = button.attr('data-route');
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		swal({
		  title: "Warning!",
		  text: "Are you sure you want to restore this item?",
		  icon: "warning",
		  buttons: {
		  	cancel: {
			  text: "Cancel",
			  value: null,
			  visible: true,
			  className: "bg-secondary",
			  closeModal: true,
			},
		  	restore: {
			  text: "Confirm restore",
			  value: true,
			  visible: true,
			  className: "bg-success",
			}
		  },
		})
        .then((value) => {
			if (value) {
				$.ajax({
			        url: route,
			        type: 'GET',
			        success: function(result) {
			            if (result == 1) {
			          	    // Show a success notification bubble
			                new Noty({
		                        type: "success",
		                        text: "The item has been restored successfully."
		                    }).show();
			                // Hide the modal, if any
			                $('.modal').modal('hide');
                            // RELOAD DATA
                            crud.table.ajax.reload();
			            }
			        },
			        error: function(result) {
			            // Show an alert with the result
			            swal({
		              	    title: "NOT restore",
                            text: "There's been an error. Your item might not have been restored.",
		              	    icon: "error",
		              	    timer: 4000,
		              	    buttons: false,
		                });
			        }
			    });
			}
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@if (!request()->ajax()) @endpush @endif
