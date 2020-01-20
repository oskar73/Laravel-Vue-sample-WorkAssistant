@extends('layouts.authApp')

@section("title") Login @endsection
@section('content')
    <div class="form">
        <div class="text-left mb-5">
            <a href="https://bizinabox.com" class="logo">
                <img src="{{asset('assets/img/logo.png')}}" alt="logo" style="width:150px;">
            </a>
        </div>
        <div class="text-left">
            <h3 class="font-size20">Log in</h3>
        </div>
        <div class="tab-content">
            <form method="POST" action="{{ route('login') }}" id="auth_form" class="authentication_form">
                @csrf
                @if(session("message"))
                    <div class="text-danger">{{session("message")}}</div>
                @endif
                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">
                        Email:
                    </p>
                    <input type="email" class="biz_input" name="email" value="{{ old('email')}}" autocomplete="off" autofocus required/>
                    @error('email')
                    <div class="text-danger text-left">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field-wrap" x-data={show:false}>
                    <p class="mb-0 text-left biz_label z-index-100">
                        Password:
                    </p>
                    <div class="position-relative">
                        <input x-bind:type="show===true?'text':'password'" class="biz_input" required autocomplete="off" name="password" id="password"/>
                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                    </div>
                    @error('password')
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
                <div class="row">
                    <div class="col text-left">
                        <div class="form-check rem_me mt-3 d-inline-block">
                            <label class="check_area" >Remember Me
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col text-right pt-3">
                        <a href="{{ route('password.request') }}" class="link_to underline">Forgot Password?</a>
                    </div>
                </div>
                <div class="text-left">
                    <button type="submit" class="signupBtn btn-block">
                        Log In
                    </button>
                </div>
            </form>

            @php
                $social = optional(option("social", null));
            @endphp

            @if(in_array(1,[$social['twitter'], $social['facebook'], $social['instagram'], $social['google'], $social['linkedin'], $social['github']]))
            <div class="row mt-3">
                <div class="col-4"><hr></div>
                <div class="col-4">Or continue with</div>
                <div class="col-4"><hr></div>
            </div>
            @endif
            <div class="auth-social-links mt-3">
                @if($social['twitter']==1)<a href="{{route('social.login', 'twitter')}}" class="twitter bg-tw"><i class="fab fa-twitter"></i></a>@endif
                @if($social['facebook']==1)<a href="{{route('social.login', 'facebook')}}" class="facebook bg-fb"><i class="fab fa-facebook"></i></a>@endif
                @if($social['instagram']==1)<a href="{{route('social.login', 'instagram')}}" class="instagram bg-ins"><i class="fab fa-instagram"></i></a>@endif
                @if($social['google']==1)<a href="{{route('social.login', 'google')}}" class="google-plus bg-go"><i class="fab fa-google-plus"></i></a>@endif
                @if($social['linkedin']==1)<a href="{{route('social.login', 'linkedin')}}" class="linkedin bg-ln"><i class="fab fa-linkedin"></i></a>@endif
                @if($social['github']==1)<a href="{{route('social.login', 'github')}}" class="github bg-git"><i class="fab fa-github"></i></a>@endif
            </div>
            <div class="text-center mt-3 text-dark">
                Need an account? <a href="{{ route('register') }}" class="link_to underline">Sign up</a>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if(config('captcha.sitekey'))
    {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
