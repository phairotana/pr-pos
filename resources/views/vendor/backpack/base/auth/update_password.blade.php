@extends(backpack_view('layouts.plain'))
@section('content')
    <div class="row justify-content-center" id="verify-wrapper">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="d-flex justify-content-center mb-3">
                @if (env('APP_PROJECT_LOGO'))
                    <img src="{{ asset('assets/VTrust-Appraisal-logo-04.png') }}" alt="vtrust" height="100">
                @endif
            </div>
            <h3>Update your password</h3>
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
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('web.update.password') }}">
                        @csrf
                        <div class="form-group required">
                            <label class="control-label @error('password') text-danger @enderror" for="password">@lang('flexi.new_password')</label>
                            <div>
                                <input
                                    type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    autocomplete="off"
                                    name="password"
                                    id="password"
                                >

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="control-label @error('password_confirmation') text-danger @enderror" for="passwordConfirmation">@lang('flexi.confirm_password')</label>
                            <div>
                                <input
                                    type="password"
                                    class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                    autocomplete="off"
                                    name="password_confirmation"
                                    id="passwordConfirmation"
                                >

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    @lang('flexi.reset_password')
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