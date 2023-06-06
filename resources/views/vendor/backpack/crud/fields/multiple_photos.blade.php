@php
    $uuid = Str::uuid();
    $gallerisId = 'gallery-'.$uuid;
    // dd($uuid);
    $presetFiles = Str::camel('presetFiles'.$uuid);
    $fakeOldValueName = 'fake_has_old_value_'.$field['name'];
    // dd($presetFiles);
    $fileUrlArr = [];

    if (!isset($field['wrapperAttributes']) || !isset($field['wrapperAttributes']['data-init-function'])){
        $field['wrapperAttributes']['data-init-function'] = 'bpFieldInitUploadMultipleElement';
    }

    if (!isset($field['wrapperAttributes']) || !isset($field['wrapperAttributes']['data-field-name'])) {
        $field['wrapperAttributes']['data-field-name'] = $field['name'];
    }

    $setRequire = isset($field['set_required']) && $field['set_required'] ? '<span class="text-danger">*</span>' : '';
@endphp

<!-- upload multiple input -->
@include('crud::fields.inc.wrapper_start')
    @include('crud::fields.inc.translatable_icon')

    {{-- Show the file name and a "Clear" button on EDIT form. --}}
    <div class="well well-sm existing-file d-none" id="{{ $gallerisId }}">
        @if (isset($field['value']))
            @php
                if (is_string($field['value'])) {
                    $values = json_decode($field['value'], true) ?? [];
                } else {
                    $values = $field['value'];
                }
            @endphp
            @if (count($values))
                @foreach($values as $key => $file_path)
                    @php
                        $fileUrl = '';
                        if (isset($field['temporary'])) {
                            $fileUrl = isset($field['disk'])
                                ? asset(\Storage::disk($field['disk'])->temporaryUrl(
                                    $file_path,
                                    Carbon\Carbon::now()->addMinutes($field['temporary']))
                                )
                                : asset($file_path);
                        } else {
                            $fileUrl= isset($field['disk'])
                                ? asset(\Storage::disk($field['disk'])->url($file_path))
                                : asset($file_path);
                        }
                        if ($fileUrl) {
                            $fileUrlArr[] = $fileUrl;
                        }

                    @endphp

                    <div class="file-preview">
                        <a target="_blank" href="{{ $fileUrl }}">{{ $file_path }}</a>

                        <a href="#"
                            class="btn btn-light btn-sm float-right file-clear-button"
                            title="Clear file"
                            data-filename="{{ $file_path }}">
                            <i class="la la-remove"></i>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                @endforeach
            @endif
        @endif
        <div class="d-none fake_has_old_value" data-attribute="{{ $fakeOldValueName }}">
            <input type="hidden" name="{{ $fakeOldValueName }}" value="{{ count($fileUrlArr) ? 1 : 0 }}">
        </div>
    </div>

    {{-- Show the file picker on CREATE form. --}}
    <div class="custom-file-container mx-0" data-upload-id="{{ $gallerisId }}">
        @if(is_array($field['label']) && count($field['label']))
            @foreach ($field['label'] as $k => $v)
                @if($k > 0)
                    <label class="font-weight-normal">
                        {{ $v }} {!! $loop->last ? $setRequire : '' !!}
                    </label>
                @else
                    <label>{{ $v }} {!! count($field['label']) <= 1 ? $setRequire : '' !!}</label>
                @endif
            @endforeach
            <a href="javascript:void(0)" class="custom-file-container__image-clear d-none" title="Clear Image">&times;</a>
        @else
            <label>{!! $field['label'] !!} {!! $setRequire !!}<a href="javascript:void(0)" class="custom-file-container__image-clear d-none" title="Clear Image">&times;</a></label>
        @endif

        <label class="custom-file-container__custom-file">
            <input name="{{ $field['name'] }}[]" type="hidden" value="">
            <div class="backstrap-file mt-2">
                <input
                    type="file"
                    name="{{ $field['name'] }}[]"
                    class="custom-file-container__custom-file__custom-file-input"
                    accept="*"
                    multiple 
                    aria-label="Choose File"
                    value="@if (old(square_brackets_to_dots($field['name']))) old(square_brackets_to_dots($field['name'])) @elseif (isset($field['default'])) $field['default'] @endif"
                    @include('crud::fields.inc.attributes', [
                        'default_class' =>  isset($field['value']) && $field['value']!=null
                        ? 'file_input backstrap-file-input'
                        : 'file_input backstrap-file-input'])
                    multiple
                >
                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                <span class="custom-file-container__custom-file__custom-file-control"></span>
                <label class="backstrap-file-label d-none" for="customFile"></label>
            </div>
        </label>
        <div class="custom-file-container__image-preview mb-0" id="sortable-container"
        style="background-size:contain;background-color:#edede8;"></div>
    </div>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if($crud->fieldTypeNotLoaded($field))
    @php $crud->markFieldTypeAsLoaded($field); @endphp

    @push('crud_fields_styles')
        <link rel="stylesheet" type="text/css" href="{{asset('assets/libraries/file-upload-with-preview/css/file-upload-with-preview.min.css')}}">
    @endpush

    @push('crud_fields_scripts')
        <script src="{{asset('assets/libraries/file-upload-with-preview/js/file-upload-with-preview.min.js')}}"></script>

        <script>
            if (typeof mupUpdateHasOldValue != 'function') {
                function mupUpdateHasOldValue(mainDiv, remainData) {
                    mainDiv.find('input[name="'+mainDiv.data('attribute')+'"]').val(remainData.length ? 1 : 0)
                }
            }


            window.addEventListener('fileUploadWithPreview:imagesAdded', function(e) {
                // console.log(e.detail.uploadId)
                // e.detail.uploadId.clearPreviewPanel()
                    // console.log(e)
                if (e.detail.uploadId) {
                    var mainGallery = $('#'+e.detail.uploadId)
                    var mainInput = mainGallery.next().find('.custom-file-container__custom-file__custom-file-input')

                    var afterDeletedFiles = e.detail.cachedFileArray.map(value => value.name);

                    mupUpdateHasOldValue(
                        mainGallery.find('.fake_has_old_value'),
                        afterDeletedFiles
                    );

                    // console.log('e.detail.cachedFileArray')
                    // console.log(e.detail.cachedFileArray)
                    var getFileInputSelect = e.detail.cachedFileArray.filter(value => value instanceof File);
                    // console.log('getFileInputSelect')
                    // console.log(getFileInputSelect)
                    if (getFileInputSelect.length) {
                        let dt = new DataTransfer()
                        getFileInputSelect.forEach(element => {
                            dt.items.add(element)
                        });
                        // console.log('dt.files: '+e.detail.uploadId)
                        // console.log(dt.files)
                        // mainInput.get(0).onchange = null // remove event listener
                        mainInput.get(0).files = dt.files // this will trigger a change event
                    }
                    // console.log('mainInput.get(0).files')
                    // console.log(mainInput.get(0).files)
                }
            });

            window.addEventListener('fileUploadWithPreview:imageDeleted', function(e) {
                // console.log(e.detail.uploadId)
                // e.detail.uploadId.clearPreviewPanel()
                    // console.log(e)
                if (e.detail.uploadId) {
                    var mainGallery = $('#'+e.detail.uploadId)
                    var mainInput = mainGallery.next().find('.custom-file-container__custom-file__custom-file-input')
                    // console.log($mainInput)
                    // console.log('cachedFileArray')
                    // console.log(e.detail.cachedFileArray)
                    var afterDeletedFiles = e.detail.cachedFileArray.map(value => value.name);

                    // console.log(mainGallery)
                    mupUpdateHasOldValue(
                        mainGallery.find('.fake_has_old_value'),
                        afterDeletedFiles
                    );

                    var getFileInputSelect = e.detail.cachedFileArray.filter(value => value instanceof File);
                    // console.log('getFileInputSelect')
                    // console.log(getFileInputSelect)
                    if (getFileInputSelect.length) {
                        let dt = new DataTransfer()
                        getFileInputSelect.forEach(element => {
                            dt.items.add(element)
                        });
                        // console.log('dt.files: '+e.detail.uploadId)
                        // console.log(dt.files)
                        // mainInput.get(0).onchange = null // remove event listener
                        mainInput.get(0).files = dt.files // this will trigger a change event
                    }
                    // console.log('mainInput.get(0).files')
                    // console.log(mainInput.get(0).files)
                    // console.log('getFileInputSelect')
                    // console.log(getFileInputSelect)
                    // console.log('afterDeletedFiles')
                    // console.log(afterDeletedFiles)
                    // console.log('currentFileCount')
                    // console.log(e.detail.currentFileCount)

                    let allFiles = [];

                    mainGallery.children('.file-preview').each(function(key, val) {
                        let path = $(this).children('a').text()
                        if (path) {
                            allFiles.push(path.trim());
                        }
                    });

                    // console.log('allFiles')
                    // console.log(allFiles)
                    var pathFiles = allFiles.map(value => {
                        var arr = value.split('/');
                        var path = '';
                        for (var i = 0; i < arr.length - 1; i++) {
                            path += `${arr[i]}/`;
                        }
                        return {
                            path,
                            // file: arr[arr.length - 1]
                            file: arr.pop()
                        };
                        return file;
                    });
                    // console.log('pathFiles')
                    // console.log(pathFiles)
                    // console.log(afterDeletedFiles, pathFiles);
                    var paths = [];
                    var files = pathFiles.map(value => {
                        var { file, path } = value;
                        paths.push(path);
                        return file;
                    });

                    // console.log('files')
                    // console.log(files)

                    function arr_diff (a1, a2) {
                        var a = [], diff = [];

                        for (var i = 0; i < a1.length; i++) {
                            a[a1[i]] = true;
                        }

                        for (var i = 0; i < a2.length; i++) {
                            if (a[a2[i]]) {
                                delete a[a2[i]];
                            } else {
                                a2[i] = `${paths[i]}${a2[i]}`;
                                a[a2[i]] = true;
                            }
                        }

                        for (var k in a) {
                            diff.push(k);
                        }

                        return diff;
                    }
                    // console.log(arr_diff(afterDeletedFiles, files));
                    arr_diff(afterDeletedFiles, files).forEach(value => $(`a[data-filename="${value}"]`).trigger('click'));
                }
            });

        </script>
        {{-- Gallery custom --}}
        <script>
        	function bpFieldInitUploadMultipleElement(element) {
        		var fieldName = element.attr('data-field-name');
        		var clearFileButton = element.find(".file-clear-button");
        		var fileInput = element.find("input[type=file]");
                var inputLabel = element.find("label.backstrap-file-label");

		        clearFileButton.click(function(e) {
		        	e.preventDefault();
		        	var container = $(this).parent().parent();
		        	var parent = $(this).parent();
		        	// remove the filename and button
		        	parent.remove();
		        	// if the file container is empty, remove it
		        	if ($.trim(container.html())=='') {
		        		container.remove();
		        	}
		        	$("<input type='hidden' name='clear_"+fieldName+"[]' value='"+$(this).data('filename')+"'>").insertAfter(fileInput);
		        });

		        fileInput.change(function() {
	                inputLabel.html("Files selected. After save, they will show up above.");
		        	// remove the hidden input, so that the setXAttribute method is no longer triggered
		        	// $(this).next("input[type=hidden]").remove();
		        });
        	}
        </script>
    @endpush
@endif


{{-- @push('crud_fields_scripts')
    <script>
        new FileUploadWithPreview('{{ $gallerisId }}',  {
            showDeleteButtonOnImages: true,
            presetFiles: @json($fileUrlArr),
            text: {
                chooseFile: "{{ trans('backpack::crud.choose_file') }}",
                browse: "{{ trans('backpack::crud.browse_uploads') }}",
                selectedCount: "Files Selected",
            },
        });
    </script>
@endpush --}}
@push('crud_fields_scripts')
    <script>
        @if(isset($field['setScriptName'])) var {{ $field['setScriptName'] }} = @endif new FileUploadWithPreview('{{ $gallerisId }}',  {
            showDeleteButtonOnImages: true,
            presetFiles: @json($fileUrlArr),
            text: {
                chooseFile: "{{ trans('backpack::crud.choose_file') }}",
                browse: "{{ trans('backpack::crud.browse_uploads') }}",
                selectedCount: "Files Selected",
            },
        });
    </script>
@endpush
