@extends(backpack_view('layouts.plain'))

@section('content')
    @php 
        $crud = Helper::fakeBackPackCrud();
    @endphp
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="card mb-2 pt-4">
                <div class="text-center">
                    <img class="card-img-top" style="width: 70%" src="{{ asset(config('const.s3Path.medium').'/'.Setting::get('logo')) }}" alt="LOGO">
                </div>
                <div class="card-body">
                    <h3 class="text-center mb-4">{{ trans('backpack::base.register') }}</h3>
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.register') }}">
                        {!! csrf_field() !!}
                        @php $invalidPhoneClass = isset($errors) && $errors->has(backpack_authentication_column()) ? ' is-invalid' : ''; @endphp
                        @include('crud::fields.phone', [
                            "field" => [
                                'name' => backpack_authentication_column(),
                                'type' => backpack_authentication_column()=='email'?'email':'text',
                                'label' => config('backpack.base.authentication_column_name'),
                                'wrapperAttributes' => ['class' => 'form-group mb-0'],
                                'classAttributes' => 'form-control'.$invalidPhoneClass
                            ]
                        ])
                        @if (isset($errors) && $errors->has(backpack_authentication_column()))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first(backpack_authentication_column()) }}</strong>
                            </span>
                        @endif

                        <div class="form-group mt-3">
                            <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>

                            <div>
                                <input type="password" class="form-control{{ isset($errors) && $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password">

                                @if (isset($errors) && $errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password_confirmation">{{ trans('backpack::base.confirm_password') }}</label>

                            <div>
                                <input type="password" class="form-control{{ isset($errors) && $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" id="password_confirmation">

                                @if (isset($errors) && $errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    {{ trans('backpack::base.register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email())
                {{-- <div class="text-center"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div> --}}
            @endif
            <div class="text-center"><a href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a></div>
        </div>
    </div>
@endsection