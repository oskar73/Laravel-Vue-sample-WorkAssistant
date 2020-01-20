@extends('layouts.app')

@section('title', 'Portfolios')

@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
    <style>
        .bizchat-fixed-bottom-right{
            display: none !important;
        }
    </style>
@endsection
@section('content')

    <x-front.hero />

    <div class="container">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a>Portfolios</a></li>
            @if($item->category->category->name!==null)
                <li class="breadcrumb-item"><a>{{$item->category->category->name}}</a></li>
            @endif
            <li class="breadcrumb-item active"><a>{{$item->category->name}}</a></li>
        </ol>
        <div class="row mt-5">
            <div class="col-md-6 mb-5 mb-md-0">
                <a href="{{$item->getFirstMediaUrl('thumbnail')}}" class="w-100 progressive replace">
                    <img src="{{$item->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$item->title}}" class="w-100 preview">
                </a>
            </div>
            <div class="col-md-6">
                <div class="product-detail">
                    <h3 class="margin-8px-bottom">
                        {{$item->title}} @if($item->featured)<span class="label-sale bg-theme text-white text-uppercase font-size12">Featured</span> @endif
                    </h3>

                    <div class="bg-theme separator-line-horizontal-full margin-20px-bottom"></div>

                    <div class="margin-20px-bottom white-space-pre-line">
                        {{ $item->description }}
                    </div>

                    <div class="margin-20px-bottom">
                        <div class="display-inline-block margin-15px-right padding-15px-right border-right border-color-extra-medium-gray">
                            <div id="review_rating"></div>
                        </div>
                        <div class="display-inline-block">
                            <a class="text-theme" href="#review">Write a review</a>
                        </div>
                    </div>

                    <x-front.socialShare></x-front.socialShare>

                </div>
            </div>
        </div>

        <x-front.gallery :item="$item" :order="$item->order"></x-front.gallery>

        <x-front.reviewForm type="portfolio" :id="$item->id"></x-front.reviewForm>
        <div class="text-right mt-4">
            <a href="{{route('admin.portfolio.item.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
            <a href="{{route('admin.portfolio.item.deny', $item->id)}}" class="btn m-btn--square m-btn m-btn--custom btn-outline-danger">Deny</a>
            <a href="{{route('admin.portfolio.item.approve', $item->id)}}" class="btn m-btn--square m-btn m-btn--custom btn-outline-success text-success">Approve</a>
        </div>
    </div>

@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script>var type = "portfolio", model_id="{{$item->id}}" </script>
@endsection
