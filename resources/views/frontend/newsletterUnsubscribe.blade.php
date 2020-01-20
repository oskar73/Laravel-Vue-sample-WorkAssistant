@extends('layouts.authApp')

@section("title")
    Unsubscribe
@endsection
@section('content')
    <div class="form position-relative">
        <div class="form-top-area border-bottom">
            <div class="row">
                <div class="@auth col-8 @else col-12 @endauth pt-2">
                    <h1 style="font-size: 24px;">Please login to unsubscribe and change your settings.</h1>
                </div>
                @auth
                    <div class="col-4 pt-2 border-left">
                        <a href="{{route('subscribed', ['role' => 'account'])}}">Go to setting <i
                                    class="fa fa-external-link-alt"></i></a>
                    </div>
                @endauth
            </div>
        </div>

        @guest
            <div class="bottom mt-5">
                <div class="float-left">
                    Need an account? <a
                            href="{{ route('register') }}?redirect_url={{urlencode(route('subscribed', ['role' => 'account']))}}"
                            class="forgot_psw">Sign up</a>
                </div>
                <div class="float-right">
                    <a href="{{ route('login') }}?redirect_url={{urlencode(route('subscribed', ['role' => 'account']))}}"
                       class="forgot_psw">Log In</a>
                </div>
                <div class="clearfix"></div>
            </div>
        @endguest
    </div>
@endsection
