@extends('layouts.authApp')

@section("title") Unsubscribe @endsection
@section('content')
    <div class="form position-relative">
        <div class="form-top-area border-bottom">
            <div class="row">
                <div class="@auth col-8 @else col-12 @endauth pt-2">
                    <h1 style="font-size: 24px;">Unsubscribe newsletter</h1>
                </div>
                @auth
                <div class="col-4 pt-2 border-left">
                    <a href="/account/subscribed">Go to setting <i class="fa fa-external-link-alt"></i></a>
                </div>
                @endauth
            </div>
        </div>
        <div class="tab-content mt-4">
            <form method="POST" action="{{ route('unsubscribe.confirm') }}" id="submit_form">
                @csrf
                @honeypot
                <div class="field-wrap">
                    <p class="mb-0 text-left">Email Address</p>
                    <input type="email" class="biz_input" name="email" value="{{ old('email')}}" autocomplete="off" autofocus required/>
                    @error('email')
                        <div class="text-danger text-left">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-left">
                    <p><b>You may unsubscribe from the following categories:</b></p> <br>
                    <div class="row">
                        <div class="col-6">
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <label for="{{$category->slug}}">
                                        <input class="form-check-input position-static" type="checkbox" name="categories[]" id="{{$category->slug}}" value="{{$category->id}}">
                                        {{$category->name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <label for="all">
                                    <input class="form-check-input position-static" type="checkbox" id="all" name="all" value="0">
                                    Unscribe All
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="auth_button button-block mt-2">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bottom mt-3">
        <div class="float-left">
            Need an account? <a href="{{ route('register') }}" class="forgot_psw">Sign up</a>
        </div>
        <div class="float-right">
            <a href="{{ route('login') }}" class="forgot_psw">Log In</a>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
