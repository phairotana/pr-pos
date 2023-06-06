@extends(backpack_view('blank'))

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }

        .image-style {
            border-radius: 50%;
        }

        #profile {
            display: none;
        }

        #btn-file {
            height: 40px;
            width: 100%;
            bottom: 0;
            position: absolute;
            transform: translateX(-50%);
            left: 50%;
            background: rgba(0, 0, 0, 0.7);
            color: wheat;
            cursor: pointer;
        }

        .div-profile {
            margin: auto;
            border-radius: 50%;
            height: 200px;
            width: 200px;
            position: relative;
            overflow: hidden;
            border: 5px solid rgb(199, 199, 199);
        }

    </style>
@endsection

@php
$breadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    trans('backpack::base.my_account') => false,
];
@endphp

@section('header')
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1>{{ trans('backpack::base.my_account') }}</h1>
        </div>
    </section>
@endsection

@section('content')
    <div class="row">

        @if (session('success'))
            <div class="col-lg-8">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->count())
            <div class="col-lg-8">
                <div class="alert alert-danger">
                    <ul class="mb-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body text-center">
                    <div>
                        <img class="div-profile" src="{{ backpack_user()->MediumProfile }}">
                    </div>
                    <strong style="margin-top: 10px">{{ $user->name }}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- UPDATE INFO FORM --}}
            <div class="col-lg-12">
                <form class="form" action="{{ route('backpack.account.info.store') }}" method="post">

                    {!! csrf_field() !!}

                    <div class="card padding-10">

                        <div class="card-header">
                            {{ trans('backpack::base.update_account_info') }}
                        </div>

                        <div class="card-body backpack-profile-form bold-labels">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    @php
                                        $label = trans('backpack::base.name');
                                        $field = 'name';
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <input required class="form-control" type="text" name="{{ $field }}"
                                        value="{{ old($field) ? old($field) : $user->$field }}">
                                </div>

                                <div class="col-md-6 form-group">
                                    @php
                                        $label = config('backpack.base.authentication_column_name');
                                        $field = backpack_authentication_column();
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <input required class="form-control"
                                        type="{{ backpack_authentication_column() == 'email' ? 'email' : 'text' }}"
                                        name="{{ $field }}"
                                        value="{{ old($field) ? old($field) : $user->$field }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    @php
                                        $label = 'Phone';
                                        $field = 'phone';
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <input required class="form-control" type="text" name="{{ $field }}"
                                        value="{{ old($field) ? old($field) : $user->$field }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    @php
                                        $label = 'Branch';
                                        $field = 'branch_id';
                                        $options = \App\Models\Branch::all()->pluck('branch_name', 'id');
                                        
                                    @endphp

                                    <label for="">Branch<span class="text-danger">*</span></label>


                                    <select class="form-control select2_element" name="branch_id">
                                        <option value="">-</option>
                                        @foreach ($options as $k => $v)
                                            <option value="{{ $k }}"
                                                {{ $k == $user->branch_id ? 'selected' : '' }}>
                                                {{ $v }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-12 form-group">
                                    @php
                                        $label = 'Address';
                                        $field = 'address';
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <textarea required class="form-control" type="textarea"
                                        name="{{ $field }}">{{ old($field) ? old($field) : $user->$field }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="la la-save"></i>
                                {{ trans('backpack::base.save') }}</button>
                            <a href="{{ backpack_url() }}"
                                class="btn">{{ trans('backpack::base.cancel') }}</a>
                        </div>
                    </div>

                </form>
            </div>

            {{-- CHANGE PASSWORD FORM --}}
            <div class="col-lg-12">
                <form class="form" action="{{ route('backpack.account.password') }}" method="post">

                    {!! csrf_field() !!}

                    <div class="card padding-10">

                        <div class="card-header">
                            {{ trans('backpack::base.change_password') }}
                        </div>

                        <div class="card-body backpack-profile-form bold-labels">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    @php
                                        $label = trans('backpack::base.old_password');
                                        $field = 'old_password';
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <input autocomplete="new-password" required class="form-control" type="password"
                                        name="{{ $field }}" id="{{ $field }}" value="">
                                </div>

                                <div class="col-md-4 form-group">
                                    @php
                                        $label = trans('backpack::base.new_password');
                                        $field = 'new_password';
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <input autocomplete="new-password" required class="form-control" type="password"
                                        name="{{ $field }}" id="{{ $field }}" value="">
                                </div>

                                <div class="col-md-4 form-group">
                                    @php
                                        $label = trans('backpack::base.confirm_password');
                                        $field = 'confirm_password';
                                    @endphp
                                    <label class="required">{{ $label }}</label>
                                    <input autocomplete="new-password" required class="form-control" type="password"
                                        name="{{ $field }}" id="{{ $field }}" value="">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="la la-save"></i>
                                {{ trans('backpack::base.change_password') }}</button>
                            <a href="{{ backpack_url() }}"
                                class="btn">{{ trans('backpack::base.cancel') }}</a>
                        </div>

                    </div>

                </form>
            </div>
        </div>


    </div>
@endsection
@section('after_scripts')
    <script>
        // $('#profile').change(function() {
        //     var input = this;
        //     var url = $(this).val();
        //     var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        //     if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
        //         var reader = new FileReader();

        //         reader.onload = function(e) {
        //             $('#profile-image').attr('src', e.target.result);
        //         }
        //         reader.readAsDataURL(input.files[0]);
        //     }
        // })
    </script>
@endsection
