{{-- @if ($crud->hasAccess('delete')) --}}
	<a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey()) }}?force_delete=true" class="btn btn-sm btn-danger" data-button-type="delete" data-toggle="tooltip" title="Delete"><i class="la la-times"></i></a>
{{-- @endif --}}

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof deleteEntry != 'function') {
	  $("[data-button-type=delete]").unbind('click');

	  function deleteEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var button = $(button);
		var route = button.attr('data-route');
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');
		swal({
		  title: "Warning",
		  text: "Delete",
		  icon: "warning",
		  buttons: {
		  	cancel: {
			  text: "Cancel",
			  value: null,
			  visible: true,
			  className: "bg-secondary",
			  closeModal: true,
			},
		  	delete: {
			  text: " Comfirm Delete",
			  value: true,
			  visible: true,
			  className: "bg-danger",
			}
		  },
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'DELETE',
			      success: function(result) {
			          if (result == 1) {
			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "The item has been deleted successfully.",
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');

						  	// RELOAD DATA
						  	crud.table.ajax.reload();
			          } else {// Show an error alert
				              swal({
				              	title: "NOT deleted",
	                            text: "There's been an error. Your item might not have been deleted.",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }
			      },
			      error: function(result) {
			          // Show an alert with the result
			          swal({
		              	title: "NOT deleted",
                        text: "There's been an error. Your item might not have been deleted.",
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
