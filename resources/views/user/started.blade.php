@extends('layouts.master')

@section('title', 'Getting Started')
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
@endsection
@section('breadcrumb')
    <div class="mr-auto">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Getting Started</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="m-portlet m-portlet--primary m-portlet--head-solid-bg m-portlet--head-sm">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Getting Started
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:50px 10px;">
                <div  style="max-width:600px;margin:auto;">
                    <div class="slider">
                        @foreach($tutorials as $tutorial)
                            <a href="{{route('user.tutorial.index')}}" class="d-block d_slider_item" style="border:2px solid #32a506">
                                <div class="position-relative">
                                    <figure data-href="{{$tutorial->getFirstMediaUrl('thumbnail')}}" class="w-100 progressive replace mb-0">
                                        <img src="{{$tutorial->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$tutorial->title}}" class="preview w-100"/>
                                    </figure>
                                    <span class="position-absolute middle-center m-1 btn m-btn--square m-btn m-btn--custom btn-bizinabox active">{{$tutorial->title}}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="row screen" id="main_screen">
                    <div class="col-lg-4">
                        <a href="javascript:void(0);" class="biz-card m-option btn-bizinabox" id="start_btn">
                            Start Here
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a href="javascript:void(0);" class="biz-card m-option  btn-bizinabox to_screen2">
                            Continue Here
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a href="{{route('user.dashboard')}}" class="biz-card m-option  btn-bizinabox" id="dashboard_btn">
                            Dashboard
                        </a>
                    </div>
                </div>
                <div class="row d-none screen" id="screen_1">
                    <div class="col-md-12" style="max-width:800px;margin:auto;">
                        <a href="javascript:void(0);" class="float-left text-center text-underline back_btn"><i class="fa fa-reply"></i> Back</a>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <a href="{{route('package.index')}}" class="biz-card m-option btn-bizinabox to_domain">
                                    Purchase Package
                                </a>
                                @if($package>0)
                                    <div class="text-center">
                                        <i class="fa fa-check-circle" style="color:#32a506" title="Already had it"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <a href="javascript:void(0);" class="biz-card m-option btn-bizinabox to_domain">
                                    Choose your Domain
                                </a>
                                @if($domain>0)
                                    <div class="text-center">
                                        <i class="fa fa-check-circle" style="color:#32a506" title="Already had it"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <a href="{{route('user.website.create')}}" class="biz-card m-option btn-bizinabox">
                                    Create Your Website
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-none screen" id="screen_2">
                    <div class="col-md-12" style="max-width:800px;margin:auto;">
                        <a href="javascript:void(0);" class="float-left text-center text-underline back_btn"><i class="fa fa-reply"></i> Back</a>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-lg-6">
                                <a href="javascript:void(0);" class="biz-card m-option btn-bizinabox to_domain">
                                    My Domains
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{route('user.website.index')}}" class="biz-card m-option  btn-bizinabox">
                                    My Websites
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-none screen" id="screen_domain">
                    <div class="col-md-12" style="max-width:800px;margin:auto;">
                        <a href="javascript:void(0);" class="float-left text-center text-underline back_btn"><i class="fa fa-reply"></i> Back</a>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <a href="{{route('user.domain.search')}}" class="biz-card m-option btn-bizinabox">
                                    Register New Domain
                                </a>
                            </div>
                            <div class="col-lg-4">
                                <a href="{{route('user.domain.connect')}}" class="biz-card m-option  btn-bizinabox">
                                    Connect Your Domain
                                </a>
                            </div>
                            <div class="col-lg-4">
                                <a href="{{route('user.domainList.index')}}" class="biz-card m-option  btn-bizinabox">
                                    Manage My Domain
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(".slider").slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true
        });

        $("#start_btn").click(function() {
            $(".screen").removeClass().addClass('screen row d-none');
            $("#screen_1").removeClass('d-none').addClass('d-flex');
        });
        $(".back_btn").click(function() {
            $(".screen").removeClass().addClass('screen row d-none');
            $("#main_screen").removeClass('d-none').addClass('d-flex');
        });
        $(".to_screen2").click(function() {
            $(".screen").removeClass().addClass('screen row d-none');
            $("#screen_2").removeClass('d-none').addClass('d-flex');
        });
        $(".to_domain").click(function() {
            $(".screen").removeClass().addClass('screen row d-none');
            $("#screen_domain").removeClass('d-none').addClass('d-flex');
        });

    </script>
@endsection
