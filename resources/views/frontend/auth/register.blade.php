@extends('layouts.authApp')

@section("title") Register @endsection
@section('content')
    <div class="form">
        <div class="text-left mb-5">
            <a href="https://bizinabox.com" class="logo">
                <img src="{{asset('assets/img/logo.png')}}" alt="logo" style="width:150px;">
            </a>
        </div>
        <div class="text-left">
            <h3 class="font-size20">Sign up</h3>
        </div>

        <div class="tab-content mt-4">
            <form method="POST" action="{{ route('register.email') }}" id="auth_form" class="authentication_form">
                @csrf
                <input type="hidden" name="timezone" id="timezone">

                @if(isset($state) && isset($continue))
                    <input type="hidden" name="state" value="{{$state}}">
                    <input type="hidden" name="continue" value="{{$continue}}">
                @endif

                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">Email <i class="fa fa-info-circle tooltip_1" title="Valid Email Address."></i></p>
                    <input type="email" class="biz_input" autocomplete="off" name="email" value="{{old("email")}}" required/>
                    @error('email')
                    <div class="form-control-feedback error-email">{{ $message }}</div>
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
                        Next
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
            <div class="mt-3 text-right text-dark">
                Already have an account? <a href="{{ route('login') }}" class="link_to underline">Log in</a>
            </div>
        </div><!-- tab-content -->
    </div>

@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>

    @if(config('captcha.sitekey'))
        {!! NoCaptcha::renderJs() !!}
    @endif

    <script>
        $(document).ready(function() {
            var timezone = moment.tz.guess();
            console.log(timezone);
            $('#timezone').val(timezone);
        });

    </script>
@endsection

