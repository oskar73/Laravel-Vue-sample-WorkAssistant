@extends('layouts.authApp')

@section("title") Thank you! @endsection
@section('content')

    <div class="form mt-3">
        <div class="tab-content">
            {!! $data !!}
        </div>
    </div>

    @isWhitelisted
    <div class="bottom mt-3">
        <div class="float-left">
            Need an account? <a href="{{ route('register') }}" class="forgot_psw">Sign up</a>
        </div>
        <div class="float-right">
            <a href="{{ route('login') }}" class="forgot_psw">Log In</a>
        </div>
        <div class="clearfix"></div>
    </div>
    @endisWhitelisted
@endsection
