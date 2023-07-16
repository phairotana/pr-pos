@extends(backpack_view('layouts.plain'))

@section('content')

<div class="wrapper">
    <div class="headline">
        <h2>WELCOME TO MR HANG</h2>
    </div>

    <form class="col-md-7" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
        {!! csrf_field() !!}
        <div class="text-center text-white">
            <small>Welcome back! Please login to continue</small>
        </div>
        <div class="form-group mt-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><svg t="1689534228328" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10479" width="20" height="20">
                            <path d="M768 554.666667a42.666667 42.666667 0 0 1-42.666667-42.666667 213.333333 213.333333 0 1 0-28.586666 106.666667A126.08 126.08 0 0 0 768 640a128 128 0 0 0 128-128 384 384 0 1 0-112.426667 271.573333l-60.586666-60.586666A298.666667 298.666667 0 1 1 810.666667 512a42.666667 42.666667 0 0 1-42.666667 42.666667z m-256 85.333333a128 128 0 1 1 128-128 128 128 0 0 1-128 128z" p-id="10480" fill="#094A9E"></path>
                        </svg></div>
                </div>
                @php $invalidPhoneClass = $errors->has($username) ? ' is-invalid' : ''; @endphp


                <input style="z-index: 1" type="email" class="form-control {{$invalidPhoneClass}}" name="{{$username}}" id="password" placeholder="Enter Email">
                @if ($errors->has($username))
                <span class="invalid-feedback text-white">
                    <strong>{{ $errors->first($username) }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><svg t="1674026732283" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9219" width="20" height="20">
                            <path d="M780.8 354.58H665.6v-42.89c0-72.31-19.85-193.3-153.6-193.3-138.87 0-153.6 135.05-153.6 193.3v42.89H243.2v-42.89C243.2 122.25 348.79 0 512 0s268.8 122.25 268.8 311.69v42.89z m-192 314.84c0-43.52-34.58-78.65-76.8-78.65s-76.8 35.13-76.8 78.65c0 29.46 15.4 54.47 38.44 67.82v89.64c0 21.74 17.25 39.7 38.4 39.7s38.4-17.96 38.4-39.7v-89.64c23-13.35 38.36-38.36 38.36-67.82zM896 512v393.61c0 65.26-51.87 118.39-115.2 118.39H243.2c-63.291 0-115.2-53.13-115.2-118.39V512c0-65.22 51.87-118.39 115.2-118.39h537.6c63.33 0 115.2 53.17 115.2 118.39z" p-id="9220" fill="#094A9E"></path>
                        </svg></div>
                </div>
                <input style="z-index: 1" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Enter Password">
                @if ($errors->has('password'))
                <span class="invalid-feedback text-white">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @else
                <div class="toggle-password">
                    <span class="la la-eye"></span>
                </div>
                @endif
            </div>
        </div>
        <div class="check-box">
            <label>
                <input type="checkbox" name="remember"> <small class="text-white">{{ trans('backpack::base.remember_me') }}</small>
            </label>
        </div>
        <div class="form-group">
            <div>
                <button type="submit" class="btn btn-block">
                    <span class="gradient-text">{{ trans('backpack::base.login') }}</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('after_styles')
<style>
    /* === Google Font ===*/
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Raleway, sans-serif;
    }

    /* === Variables Define ===*/
    :root {
        /* --primary-color: #3525D3; */
        --primary-color: #008bdc;
        --white-color: #fff;
        --black-color: #3C4A57;
        --light-gray: #E4E8EE;
    }

    /* === Body CSS ===*/
    body {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0px;
        padding: 0px;
        background-image: url("../../images/construction-workers.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100%;
        overflow: hidden;
    }

    form {
        margin: auto;
        padding: 2%;
        border-radius: 24px;
        /* background: linear-gradient(180deg, rgba(204, 248, 235, 0) 0%, rgba(156 187 218) 100%); */
        background: linear-gradient(180deg, rgba(204, 248, 235, 0) 0%, #ffae05 245%);
        backdrop-filter: blur(1px);
    }

    /* === Main Content CSS ===*/
    .wrapper {
        padding: 0 25px 0;
        max-width: 668px;
        width: 100%;
        margin: auto;
    }

    .wrapper .headline {
        text-align: center;
        padding-bottom: 26px;
    }

    .wrapper .headline h2 {
        font-size: 34px;
        font-weight: 900;
        line-height: 52px;
        background: -webkit-linear-gradient(#0071b4, #1b2a4e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .wrapper .form {
        max-width: 350px;
        width: 100%;
        margin: auto;
    }

    .wrapper .form-group input {
        font-size: 16px;
        padding: 11px 7px;
        border-radius: 5px;
        color: var(--black-color);
        box-shadow: none;
    }

    .wrapper .form-group input:focus {
        border-color: var(--primary-color);
    }

    .wrapper .form-group input::placeholder {
        color: var(--primary-color);
        font-weight: 400;
        font-size: 14px;
    }

    .wrapper .btn {
        margin: 15px 0 30px;
        font-size: 10px;
        line-height: 22px;
        font-weight: 700;
        color: white;
    }

    .wrapper .btn:focus {
        outline: none;
    }

    input[type=checkbox] {
        vertical-align: middle;
        width: 12px;
        height: 12px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 100%;
        right: 0;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        opacity: 0.6;
        z-index: 1;
    }

    .toggle-password:hover {
        opacity: 0.5;
    }

    @media (max-width:1030px) {
        .wrapper::before {
            left: -25%;
            min-height: 60vh;
            height: 500px;
        }
    }

    @media (max-width:767px) {
        .wrapper {
            max-width: 550px;
        }
    }

    button:hover,
    button:hover span {
        animation: button-animation-rev 0.65s ease-out forwards;
    }

    button:hover {
        box-shadow: 3px 2px 10px 1px rgba(0, 0, 0, 0.15);
        transition: 0.5s;
    }

    @keyframes button-animation {
        0% {
            background-position: top right;
        }

        100% {
            background-position: top left;
        }
    }

    @keyframes button-animation-rev {
        0% {
            background-position: top left;
        }

        100% {
            background-position: top right;
        }
    }

    button {
        background-image: linear-gradient(45deg, #0f50a4 50%, #b57302 50%);
        background-size: 270%;
        background-repeat: repeat;
        background-position: top right;
        animation: button-animation 0.65s 0.15s ease-out forwards;
    }

    button span {
        font-family: "Archivo Black", sans-serif;
        font-weight: normal;
        font-size: 2em;
        text-align: center;
        margin-bottom: 0;
        margin-bottom: -0.25em;
        width: 100%;
        padding: 0 1.5em;
    }

    .input-group>.custom-select:not(:last-child),
    .input-group>.form-control:not(:last-child) {
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }
</style>
@endpush

@push('after_scripts')
<script>
    $('.toggle-password').click(function() {
        $(this).children().toggleClass('la la-eye la la-eye-slash');
        let input = $(this).prev();
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
    });
</script>
@endpush