@extends('layouts.app')

@section('title', 'Directory Listing')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}"/>
    <style>
        .bizchat-fixed-bottom-right{
            display: none !important;
        }
    </style>
@endsection
@section('content')

    <x-front.hero>Directory</x-front.hero>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <div class="directory-ads-position-1114 text-right"></div>
                <div class="directory-ads-position-1115 text-right"></div>
            </div>
            <div class="col-lg-8">

                <x-front.directory-nav></x-front.directory-nav>

                <div class="search_append_area py-4">
                    <div class="directory-ads-position-1111 text-center"></div>
                    <div class="directory_category_area">
                        <a href="{{route('admin.todo.index')}}"><i class="fa fa-home"></i></a>
                        @if($listing->category->category->name!==null)
                        &rarr; <a>{{$listing->category->category->name}}</a>
                        &rarr; <a>{{$listing->category->name}}</a>
                        @else
                        &rarr; <a>{{$listing->category->name}}</a>
                        @endif
                    </div>
                    <div class="directory-ads-position-1112 text-center"></div>
                    <div class="directory_item_area ">
                        <div class="directory_listing_detail_area">
                            @if($listing->new)
                            <span class="py-1 px-3 border-color-new font-size14">New</span>
                            @endif
                            @if($listing->featured)
                            <span class="py-1 px-3 border-color-featured font-size14">Featured</span>
                            @endif
                            <div class="d-flex justify-content-between">
                                <h1>
                                    {{$listing->title}}
                                </h1>
                               <div>
                                   <a href="{{$listing->url}}" target="_blank" class="btn btn-outline-info">Visit Site <i class="fa fa-external-link-alt"></i></a>
                               </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="h-30px">
                                    <div class="d-inline-block" id="review_rating"></div>
                                    <div class="d-inline-block">  (<span class="review_count">0</span> <a href="#review">Reviews</a>)</div>
                                </div>
                                <div class="position-relative social-share-area">
                                    <div class="share position-absolute bottom-0 right-0">
                                        <x-front.socialShare></x-front.socialShare>
                                    </div>
                                </div>
                            </div>

                            <hr class="m-0">
                            <div class="description">
                                {!! $listing->description !!}
                            </div>
                            <div class="gallery_area">
                                <x-front.gallery :item="$listing" :order="$listing->order"></x-front.gallery>
                            </div>
                            <div class="text_posted_on">
                                Posted on {{$listing->created_at->toDateTimeString()}}
                            </div>
                                <br>
                            <a href="{{$listing->url}}" class="btn btn-outline-info">Visit Site <i class="fa fa-external-link-alt"></i></a>
                        </div>
                        <x-front.reviewForm type="directory" :id="$listing->id"></x-front.reviewForm>
                        <div class="text-right mt-4">
                            <a href="{{route('admin.directory.listing.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                            <a href="{{route('admin.directory.listing.deny', $listing->id)}}" class="btn m-btn--square m-btn m-btn--custom btn-outline-danger smtBtn">Deny</a>
                            <a href="{{route('admin.directory.listing.approve', $listing->id)}}" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Approve</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script>
        directory_url = "{{route('directory.index')}}"
        type = "directory";
        model_id="{{$listing->id}}"
    </script>
@endsection
