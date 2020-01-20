@extends('layouts.authApp')

@section("title") Verify Email Address @endsection
@section('content')
    <div class="form">

        <div class="text-left mb-5">
            <a href="{{route('home')}}" class="logo">
                <img src="{{asset('assets/img/logo.png')}}" alt="logo" style="width:150px;">
            </a>
        </div>
        <div class="text-left">
            <h3 class="font-size20">Verify Your Email Address</h3>
        </div>

        <div class="tab-content">
            @if (session('resent'))
                <div class="alert alert-success mt-2" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <p class="mt-3 border border-success p-2 text-left">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
            </p>
            <form method="POST" action="{{ route('verification.resend') }}" id="auth_form" class="authentication_form">
                @csrf
                <button type="submit" class="signupBtn btn-block mt-5">Click here to request another</button>
            </form>
            <div class="text-center mt-3">
                <a href="javascript:void(0);" class="link_to underline" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</a>
            </div>

        </div><!-- tab-content -->
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection
