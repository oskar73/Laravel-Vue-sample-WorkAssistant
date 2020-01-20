@extends('layouts.authApp')

@section("title") Confirm Password @endsection
@section('content')
    <div class="form">

        <div class="text-left mb-5">
            <a href="{{route('home')}}" class="logo">
                <img src="{{asset('assets/img/logo.png')}}" alt="logo" style="width:150px;">
            </a>
        </div>
        <div class="text-left">
            <h3 class="font-size20">Confirm Password</h3>
        </div>

        <div class="tab-content">
            <form method="POST" action="{{ route('password.confirm') }}" id="auth_form" class="authentication_form">
                @csrf

                <div class="field-wrap">
                    <p class="mb-0 text-left biz_label">Password</p>
                    <input type="password" class="biz_input" required autocomplete="off" name="password" value="{{ old('password') }}"/>
                </div>
                <button type="submit" class="signupBtn btn-block mt-2">Confirm</button>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}" class="link_to underline">Forgot Password?</a>
            </div>
        </div><!-- tab-content -->
    </div>
@endsection

