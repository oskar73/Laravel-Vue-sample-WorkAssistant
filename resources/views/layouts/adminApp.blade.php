<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">

<head>
    <meta charset="utf-8" />
    <title>Bizinabox - @yield('title')</title>

    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {!! $basic['back_head'] !!}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.min.css" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="{{ s3_asset('vendors/datatable/datatables.min.css') }}" rel="stylesheet"/>
    <link href="{{ s3_asset('vendors/slim/slim.min.css') }}" rel="stylesheet"/>
    <link href="{{ s3_asset('vendors/izitoastr/iziToast.min.css') }}" rel="stylesheet"/>
    <link href="{{ s3_asset('vendors/base/vendors.bundle.css') }}" rel="stylesheet"/>
    <link href="{{ s3_asset('vendors/base/style.bundle.css') }}" rel="stylesheet"/>
    <link href="{{mix('assets/resources/css/all.css')}}" rel="stylesheet"/>

    @yield('style')

</head>

<body id="masterBody" class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default m-aside-left--fixed">

<div class="m-grid m-grid--hor m-grid--root m-page">
    @include("components.account.header")

    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

        @if(Request::is('admin*'))
            @include("components.admin.sidebar")
        @else
            @include("components.user.sidebar")
        @endif
        <div class="m-grid__item m-grid__item--fluid m-wrapper">

            <div class="m-subheader">
                <div class="row">
                    @yield('breadcrumb')
                </div>
            </div>

            <div class="m-content position-relative md-plr-10">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>
<script src="{{s3_asset('vendors/base/vendors.bundle.js')}}"></script>
<script src="{{s3_asset('vendors/base/scripts.bundle.js')}}"></script>
<script src="{{s3_asset('vendors/tinymce/tinymce.min.js')}}"></script>
<script src="{{s3_asset('vendors/datatable/datatables.min.js')}}"></script>
<script src="{{s3_asset('vendors/slim/slim.kickstart.min.js')}}"></script>
<script src="{{s3_asset('vendors/izitoastr/iziToast.min.js')}}"></script>
<script src="{{mix('assets/resources/js/all.js')}}"></script>

<script>
    var token = $('meta[name="csrf-token"]').attr('content');
</script>

<x-global.toast></x-global.toast>


@yield('script')

{!! $basic['back_bottom'] !!}

@include('cookie-consent::index')
</body>

</html>
