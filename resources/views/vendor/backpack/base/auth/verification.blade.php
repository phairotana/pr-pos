@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center" id="verify-wrapper">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="d-flex justify-content-center mb-3">
                @if (env('APP_PROJECT_LOGO'))
                    <img src="{{ asset('assets/VTrust-Appraisal-logo-04.png') }}" alt="vtrust" height="100">
                @endif
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! session('message') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form id="verification" class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('web.forget.password.verification') }}">
                        @csrf
                        <div class="form-group required">
                            <label class="control-label" for="verifyCode">@lang('flexi.verification_code')</label>
                            <input
                                type="number"
                                class="form-control"
                                name="code"
                                id="verifyCode"
                                autocomplete="off"
                                placeholder="@lang('flexi.verification_code')"
                                @if (env('SERVER_IS') === 'PRODUCTION')
                                    value=""
                                @else
                                    value="1234"
                                @endif
                            >
                            @if($errors->has('code'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <p>@lang('flexi.didnt_get_verification_code')
                            <a href="#/" @click.prevent="resendVerify">@lang('flexi.resend_sms')</a>
                            <span v-if="count">@lang('flexi.in') @{{ countDown }} @lang('flexi.seconds')</span>
                        </p>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary text-capitalize">
                                    @lang('flexi.validate_your_account')
                                </button>
                                <p class="text-center mt-2">
                                    <a href="{{ backpack_url('login') }}">@lang('flexi.back_to_login')</a>
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
<link rel="stylesheet" href="{{ asset('assets/libraries/intl-tel-input/css/intlTelInput.min.css') }}">
<style>
    .form-group.required label:not(:empty)::after {
        content: ' *';
        color: red;
    }
    .intl-tel-input{
        width: 100%;
    }
    .alert-success {
        color: #155724!important;
        background-color: #d4edda!important;
        border-color: #c3e6cb!important
    }
</style>
@endpush

@push('after_scripts')
    <script>
        new Vue({
            el: '#verify-wrapper',
            data() {
                return {
                    err: {},
                    count: '',
                    timeOut: ''
                };
            },
            methods: {
                resendVerify: function() {
                    axios.post('{{ url("api/request-otp") }}', {
                        phone: '{{ session('phone') }}'
                    })
                        .then(res => {
                            this.count = res.data.retry_in_second;
                            swal({
                                icon: "success",
                                text: "Code have been resent."
                            });
                        })
                        .catch(err => {
                            this.err = err.response.data.errors;
                            this.count = err.response.data.errors.retry_in_second;
                            // console.log(this.count);
                            swal({
                                icon: "error",
                                // text: err.response.data.message
                                text: "Request too fast. Please wait"
                            });
                        });
                },
            },
            computed: {
                countDown: function() {
                    if (this.timeOut) {
                        clearTimeout(this.timeOut);
                    }

                    if(this.count > 0) {
                        this.timeOut = setTimeout(() => {
                            this.count -= 1
                        }, 1000);
                    } else {
                        this.count = '';
                    }
                    return this.count;
                }
            }
        });
    </script>
@endpush
