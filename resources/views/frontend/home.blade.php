@extends('layouts.app')

@section('title', null)

@section('style')
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/template/preview.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/slider.min.css')}}">
@endsection

@section('content')
    <div class="bz-front-page--home">

        <x-front.home-slider />

        <section class="bg-light-gray">
            <div class="container">
                <h2 class="fw-600">About Bizinabox</h2>
                <div class="row">
                    <div class="col-lg-6 col-md-12 sm-margin-30px-bottom">
                        <div class="padding-30px-right">
                            <div class="py-3">
                                <p class="no-margin-bottom">
                                    {{option('home.video.description','')}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="story-video bg-img cover-background" data-overlay-dark="0" style="background-image: url({{s3_asset('img/front/about-04.jpg')}})">

                            <div class="opacity-extra-medium bg-black"></div>
                            <a class="video" target="_blank" href="{{option('home.video.url','https://www.youtube.com/embed/tgbNymZ7vqY')}}">
                                <div class="text-center center-col absolute-middle-center z-index-1">
                                        <span class="video_btn margin-10px-right margin-15px-bottom">
                                            <i class="fas fa-play font-size18 xs-font-size16"></i>
                                        </span>
                                </div>
                            </a>
                            <div class="inner-border"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <template >
            <x-front.home-templates />
        </template>

        <section>
            <div class="container">
                <div class="section-heading">
                    <h3>{{$data['title']??''}}</h3>
                    <p class="w-55px sm-w-75px xs-w-95px">{{$data['description']??''}}</p>
                </div>

                <div class="row feature-boxes-container">

                    <div class="col-lg-4 col-md-12 sm-margin-20px-bottom feature-box-04">
                        <div class="feature-box-inner h-100">
                            <i class="icon-browser font-size50 md-font-size46 sm-font-size40 xs-font-size38"></i>
                            <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase fw-600 text-black">{{$data['items'][0]['title']??''}}</h4>
                            <div class="sepratar"></div>
                            <p>{{$data['items'][0]['description']??''}}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12 sm-margin-20px-bottom feature-box-04">
                        <div class="feature-box-inner h-100">
                            <i class="icon-layers font-size50 md-font-size46 sm-font-size40 xs-font-size38"></i>
                            <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase fw-600 text-black">{{$data['items'][1]['title']??''}}</h4>
                            <div class="sepratar"></div>
                            <p>{{$data['items'][1]['description']??''}}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 feature-box-04">
                        <div class="feature-box-inner h-100">
                            <i class=" icon-hotairballoon font-size50 md-font-size46 sm-font-size40 xs-font-size38"></i>
                            <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase fw-600 text-black">{{$data['items'][2]['title']??''}}</h4>
                            <div class="sepratar"></div>
                            <p>{{$data['items'][2]['description']??''}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <template class="d-none">
            <x-front.home-boxes />
        </template>

        {{-- <x-front.testimonial /> --}}
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/js/front/template/preview.js')}}"></script>
@endsection
