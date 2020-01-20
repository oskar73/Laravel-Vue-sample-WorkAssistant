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
            <h3 class="font-size20">Reset Password</h3>
        </div>

        <div class="tab-content">
            <form method="POST" action="{{ route('password.update') }}" id="auth_form" class="authentication_form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">Email Address</p>
                    <input type="email" class="biz_input" required autocomplete="off" name="email" value="{{ old('email') }}"/>

                    @error('email')
                    <div class="text-danger text-left">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">New Password</p>
                    <input type="password" class="biz_input" required autocomplete="off" name="password"/>

                    @error('password')
                    <div class="text-danger text-left">{{ $message }}</div>
                    @enderror

                </div>

                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">Confirm Password</p>
                    <input type="password" class="biz_input" required autocomplete="off" name="password_confirmation"/>
                </div>
                @if(config('captcha.sitekey'))
                    <div class="biz_captcha_container">
                        {!! NoCaptcha::display() !!}
                    </div>
                @endif
                @if ($errors->has('g-recaptcha-response'))
                    <div class="form-control-feedback error-g-recaptcha-response">{{ $errors->first('g-recaptcha-response') }}</div>
                @endif

                <button type="submit" class="signupBtn btn-block  mt-2">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @if(config('captcha.sitekey'))
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
