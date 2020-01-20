<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Bizinabox</title>

    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />

    <meta name="keywords" content="HTML5 Template Crizal" />
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preload" href="/assets/img/login.jpg" as="image" type="image/jpg" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <link rel="stylesheet" href="{{s3_asset('vendors/izitoastr/iziToast.min.css')}}" />
    <link href="{{ s3_asset('vendors/fontawesome/fontawesome.min.css') }}" rel="stylesheet"/>
    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}

    <link rel="canonical" href="{{ url()->current() }}/">
    @vite(['resources/sass/front.scss'])

    @yield('style')
</head>
<body>
    <div id="app" class="auth_area">
        <div class="authApp">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="main_area">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{s3_asset('vendors/jquery/jquery.min.js')}}"></script>
    <script src="{{s3_asset('vendors/izitoastr/iziToast.min.js')}}"></script>
    <script src="{{asset('assets/js/dev1/both.js')}}"></script>
    <script src="{{asset('assets/js/dev1/front.js')}}"></script>

    @include("components.global.toast")

    @yield('script')
</body>

</html>
