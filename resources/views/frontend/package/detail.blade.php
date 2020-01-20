@extends('layouts.app')

@section('title', 'Bizinabox Package - ' . $item->name)

@section('seo')
    <link rel="canonical" href="{{ config('app.url') }}/package/{{ $item->slug }}">
@endsection

@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
@endsection
@section('content')
    <x-front.hero>Package</x-front.hero>

    <div class="container">
        <ul class="breadcrumb">
            <li class="m-nav__item m-nav__item--home">
                <a href="/" class="d-flex align-items-center">
                    <i class="fa fa-home mr-2"></i> Home
                </a>
            </li>
            <li class="m-nav__separator mx-1"> / </li>
            <li class="m-nav__item">
                <a href="/package" class="m-nav__link">
                    <span class="m-nav__link-text">Package</span>
                </a>
            </li>
            <li class="m-nav__separator mx-1"> / </li>
            <li class="m-nav__item">
                <span class="m-nav__link-text">{{ $item->name }}</span>
            </li>
        </ul>
        <div class="row mt-5 margin-70px-bottom">
            <div class="col-md-5">
                <div class="xzoom-container">
                    <a href="{{$item->getFirstMediaUrl('thumbnail')}}" class="w-100 progressive replace">
                        <img src="{{$item->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$item->name}}" class="w-100 preview">
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div class="product-detail">
                    <h3 class="margin-8px-bottom">
                        {{$item->name}} @if($item->featured)<span class="label-sale bg-theme text-white text-uppercase font-size14">Featured</span> @endif
                    </h3>
                    <div class="bg-theme separator-line-horizontal-full margin-20px-bottom"></div>
                    <div class="margin-20px-bottom white-space-pre-line">
{{--                        <ul>--}}
{{--                            @if($item->featured_module!=0)--}}
{{--                                <li>Your choice of {{$item->featured_module}} featured modules</li>--}}
{{--                            @endif--}}
{{--                            @if($item->module!=0)--}}
{{--                                <li>Your choice of {{$item->module}} modules</li>--}}
{{--                            @endif--}}
{{--                            <li>{{$item->website==-1?'Unlimited':$item->website}} websites with {{$item->page}} pages, {{$item->storage}} GB storage</li>--}}
{{--                            @if($item->domain!=0)--}}
{{--                               <li>Free Domain up to ${{formatNumber($item->domain)}} price for a year</li>--}}
{{--                            @endif--}}
{{--                            <li>Free Domain SSL</li>--}}
{{--                            @foreach($item->services as $service)--}}
{{--                            <li><a href="{{route('service.detail', $service->slug)}}">{{$service->name}}</a></li>--}}
{{--                            @endforeach--}}
{{--                            @foreach($item->lacartes as $lacarte)--}}
{{--                            <li><a href="{{route('lacarte.detail', $lacarte->slug)}}">{{$lacarte->name}}</a></li>--}}
{{--                            @endforeach--}}
{{--                            @foreach($item->plugins as $plugin)--}}
{{--                            <li><a href="{{route('plugin.detail', $plugin->slug)}}">{{$plugin->name}}</a></li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
                        {{ $item->description }}
                    </div>

                    <div class="margin-20px-bottom">
                        <div class="display-inline-block margin-15px-right padding-15px-right border-right border-color-extra-medium-gray">
                            <div id="review_rating"></div>
                        </div>
                        <div class="display-inline-block">
                            <a class="text-theme-color" href="#review">Write a review</a>
                        </div>
                    </div>
                    <div class="margin-20px-bottom">
                        @foreach($item->approvedPrices as $price)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" id="price-{{$price->id}}" value="{{$price->id}}" @if($price->standard===1) checked @endif>
                                <label class="form-check-label" for="price-{{$price->id}}">
                                    @if($price->slashed_price!==null)
                                        <span class="margin-15px-right font-size18 font-weight-600 offer-price">
                                            ${{formatNumber($price->slashed_price)}}
                                        </span>
                                    @endif
                                    <span class="font-size18 font-weight-700 text-theme-color">
                                         ${{formatNumber($price->price)}}
                                        @if($price->recurrent) / {{periodName($price->period, $price->period_unit)}} (Subscription)@endif
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-4 col-lg-2">
                            <label>Qty:</label>
                            <input type="number" value="1" placeholder="1" class="margin-20px-bottom fcustom-input" name="quantity" id="quantity">
                        </div>
                    </div>

                    <div class="row margin-20px-bottom">
                        <div class="col-lg-12">
                            <a href="javascript:void(0);" class="btn style-1 theme margin-15px-right xs-margin-10px-bottom addToCartBtn toggleBtn" data-cart="0">
                                <span><i class="fas fa-shopping-cart margin-5px-right"></i> Add to Cart</span>
                            </a>
                            <a href="{{route('cart.index')}}" class="btn style-1 theme margin-15px-right xs-margin-10px-bottom toggleBtn d-none">
                                <span> Go to Cart <i class="fas fa-arrow-right margin-5px-left"></i></span>
                            </a>
                            <button class="btn style-1 text-uppercase addToCartBtn" data-cart="1"><span> Buy Now</span></button>
                        </div>
                    </div>

                    <x-front.socialShare></x-front.socialShare>

                </div>
            </div>
        </div>
        <x-front.gallery :item="$item" :order="$item->order"></x-front.gallery>

        <x-front.reviewForm type="package" :id="$item->id"></x-front.reviewForm>

    </div>

@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script>var type = "package", model_id="{{$item->id}}"; </script>
    <script src="{{asset('assets/js/front/package/detail.js')}}"></script>
@endsection
