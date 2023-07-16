@extends(backpack_view('layouts.plain'))
@section('content')
    <div class="row justify-content-center" id="verify-wrapper">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="d-flex justify-content-center mb-3">
                @if (env('APP_PROJECT_LOGO'))
                    <img src="{{ asset('images/flag/cic-logo.png') }}" alt="vtrust" height="100">
                @endif
            </div>

            {{-- @if ($errors->count())
                <div class="col-lg-8">
                    <div class="alert alert-danger">
                        <ul class="mb-1">
                            @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif --}}

            <h3>@lang('flexi.reset_password')</h3>
            <p>Please enter your phone number. You will received a verification code via SMS to create a new password.</p>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('web.forget.password') }}">
                        @csrf
                        <div class="form-group required">
                            <label class="control-label" for="phone">@lang('flexi.phone_number')</label>
                            <div>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    autocomplete="off"
                                    id="phone"
                                    data-init-function="bpFieldInitFlexiPhone"
                                >

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    @lang('flexi.continue')
                                </button>
                                <p class="text-center mt-2">
                                    <a href="{{ backpack_url('logout') }}">@lang('flexi.back_to_login')</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_styles')
    <link rel="stylesheet" href="{{ asset('assets/libraries/intl-tel-input/css/intlTelInput.min.css') }}?v=0.0.2">
    <style>
        .intl-tel-input {
            width: 100%;
        }
        .form-group.required label:not(:empty)::after {
            content: ' *';
            color: red;
        }
        .verify-content .text-default {
            color: #0EB8E2!important;
            letter-spacing: 5px;
        }
        .btn-validate button {
            background-color: #0CB7E1!important;
            border-color: #0CB7E1!important;
        }
        .intl-tel-input{
            width: 100%;
        }
    </style>
@endpush

@push('after_scripts')
    <script src="{{ asset('assets/libraries/intl-tel-input/js/utils.js') }}?v=0.0.1"></script>
    <script src="{{ asset('assets/libraries/intl-tel-input/js/intlTelInput.min.js') }}?v=0.0.1"></script>
    <script>
        function rmInitializeFieldsWithJavascript(container) {
            var selector;
            if (container instanceof jQuery) {
                selector = container;
            } else {
                selector = $(container);
            }
            selector.find("[data-init-function]").not("[data-initialized=true]").each(function () {
                var element = $(this);
                var functionName = element.data('init-function');

                if (typeof window[functionName] === "function") {
                window[functionName](element);

                // mark the element as initialized, so that its function is never called again
                element.attr('data-initialized', 'true');
                }
            });
        }

        function itiCallback(elem, iti) {
            // console.log(iti.getNumber())
            elem.val(iti.getNumber());
        }

        function bpFieldInitFlexiPhone(element) {
            // console.log(element[0])
            // must be define as var instead of const
            var iti = window.intlTelInput(element[0], {
                preferredCountries: ['kh'],
                autoFormat: false,
                // formatOnInit:true,
                formatOnDisplay: false,
                customPlaceholder: function () {
                    return '{{ trans('flexi.phone_number') }}';
                },
            })

            // register country change select
            element.on('countrychange', function () {
                itiCallback(element, iti)
            })

            // register any keypress event
            element.on('keyup', function () {
                itiCallback(element, iti)
            })
        }

        rmInitializeFieldsWithJavascript('body');
    </script>

@endpush