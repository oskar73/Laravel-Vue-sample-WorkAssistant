@extends('layouts.authApp')

@section("title") Reset Password @endsection
@section('content')
    <div class="form">

        <div class="text-left mb-5">
            <a href="{{route('home')}}" class="logo">
                <img src="{{asset('assets/img/logo.png')}}" alt="logo" style="width:150px;">
            </a>
        </div>
        <div class="text-left">
            <h3 class="font-size20">Reset Password Request</h3>
        </div>

        <div class="tab-content">
            <form method="POST" action="{{ route('password.email') }}" id="auth_form" class="authentication_form">
                @csrf
                @if (session('status'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">Email Address</p>
                    <input type="email" class="biz_input" required autocomplete="off" name="email" value="{{ old('email') }}"/>
                    @error('email')
                    <div class="text-danger text-left">{{ $message }}</div>
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

                <button type="submit" class="signupBtn btn-block mt-2">Send</button>
            </form>

            <div class="mt-3 text-center">
                Go back to <a href="{{ route('login') }}" class="link_to underline">Log in</a>
            </div>
        </div><!-- tab-content -->
    </div>

@endsection
@section('script')
    @if(config('captcha.sitekey'))
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
