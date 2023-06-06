{{-- @php --}}
{{-- $customAttribute = isset($field['name']) ? $field['name'] : ''; --}}
{{-- @endphp --}}

<!-- number input -->
@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

@if (isset($field['prefix']) || isset($field['suffix']))
    <div class="input-group">
@endif
@if (isset($field['prefix']))
    <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div>
@endif

<div class="input-group wrapper" data-init-function="bpFieldInitNumberElement">
    <button type="button" class="sub-btn plusminus" disabled="disabled" data-type="minus">-</button>
    <input type="number" name="{{ $field['name'] }}" style="text-align: center"
        value="{{ old(square_brackets_to_dots($field['name'])) ?? ($field['value'] ?? ($field['default'] ?? 1)) }}"
        @include('crud::fields.inc.attributes')>
    <button type="button" class="plus-btn plusminus" data-type="plus">+</button>
</div>

@if (isset($field['suffix']))
    <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div>
@endif

@if (isset($field['prefix']) || isset($field['suffix'])) </div> @endif

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')
@push('crud_fields_scripts')
    <style>
        .plusminus {
            border: none;
            outline: none !important;
        }

    </style>

@endpush()
@push('crud_fields_scripts')
    <script>
        function bpFieldInitNumberElement(element) {
            $(element.find('.sub-btn')).click(function() {
                var input = element.find('input');
                if (input.val() > 1) {
                    input.val(input.val() - 1)
                }
                if (element.find('input').val() == 1) {
                    element.find('.sub-btn').prop('disabled', true)
                }
                element.trigger('change');
            })
            $(element.find('.plus-btn')).click(function() {
                var input = element.find('input');
                if (input.val() > 0) {
                    input.val(parseInt(input.val()) + 1);
                } else {
                    input.val(1);
                }
                element.find('.sub-btn').prop('disabled', false)
                element.trigger('change');
            })

            $(element.find('input')).keyup(function() {
                if ($(this).val() > 1) {
                    element.find('.sub-btn').prop('disabled', false)
                } else {
                    element.find('.sub-btn').prop('disabled', true)
                }

            })
        }
    </script>
@endpush
