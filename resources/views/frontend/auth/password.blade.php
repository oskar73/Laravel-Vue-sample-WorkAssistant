@extends('layouts.authApp')

@section("title") Password @endsection
@section('content')
    <div class="form">
        <div class="text-left mb-5">
            <a href="{{route('home')}}" class="logo">
                <img src="{{asset('assets/img/logo.png')}}" alt="logo" style="width:150px;">
            </a>
        </div>
        <div class="text-left {{session()->has('successMessage')? 'd-none': 'd-block'}} show_area">
            <h3 class="font-size20">Password</h3>
        </div>

        <div class="tab-content mt-4">
            @if(session()->has('successMessage'))
                <div class="alert alert-success border-radius-0">
                    <b> Success... </b><br>please check your spam box and email for password. <br>
                    Once you find your password, <a href="#" class="link_to underline click_here">click here</a> to enter
                    <div class="mt-5">Didn't get password? <a href="{{ route('resend-email') }}" class="link_to underline">click here</a> to resend</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.password') }}" id="auth_form" class="authentication_form show_area {{session()->has('successMessage')? 'd-none': 'd-block'}}">
                @csrf

                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">Password <i class="fa fa-info-circle tooltip_1" title="We just sent password to your email address. Please check your mailbox including spam folder."></i></p>
                    <input type="password" class="biz_input" autocomplete="off" name="password" required/>
                    @error('password')
                    <div class="form-control-feedback error-password">{{ $message }}</div>
                    @enderror
                </div>
                @if(config('captcha.sitekey'))
                    <div class="biz_captcha_container">
                        {!! NoCaptcha::display() !!}
                    </div>
                @endif
                @if ($errors->has('g-recaptcha-response'))
                    <div class="form-control-feedback error-g-recaptcha-response">{{ $errors->first('g-recaptcha-response') }}</div>
                @endif
                <div class="text-left">
                    <button type="submit" class="signupBtn">
                        Submit
                    </button>
                </div>
            </form>
            <div class="mt-3 text-right text-dark">
                <a href="{{ route('login') }}" class="link_to underline">Go to Log in</a>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @if(config('captcha.sitekey'))
        {!! NoCaptcha::renderJs() !!}
    @endif
    <script>
        $(".click_here").click(function(e) {
            e.preventDefault();
            $(".show_area").removeClass("d-none");
            $(".alert").remove();
        })
    </script>
@endsection

