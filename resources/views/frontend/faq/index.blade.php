@extends('layouts.app')

@section('title', 'FAQs')

@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
@endsection
@section('content')
    <x-front.hero>FAQs</x-front.hero>

    <div class="container container-1500">
        <div class="row">
            <div class="col-lg-3  pt-5">
                <form class="quform newsletter-form w-sm-90 mx-auto mx-lg-0" action="" method="post" enctype="multipart/form-data">
                    <div class="quform-elements">
                        <div class="quform-element mb-4">
                            <div class="quform-input">
                                <input class="form-control" id="keyword" type="text" name="keyword" placeholder="Search..." autocomplete="off">
                                <div class="quform-submit-inner">
                                    <button class="btn btn-white text-theme-color m-0" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="filter-widget">
                    <p class="view_by_txt">Filter by Category</p>
                    <ul class="category-menu">
                        <li class="all"><a href="#/all">All Categories</a></li>
                        @foreach($categories as $key=>$category)
                            <li class="{{$category->slug}}">
                                <a href="#/{{$category->slug}}">{{$category->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 pt-5">
                <div class="items_result">
                    <div class="text-center minh-100 pt-5"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{asset('assets/js/front/faq/index.js')}}"></script>
@endsection
