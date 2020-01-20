@extends('layouts.master')

@section('title', 'Tutorial')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Tutorials', 'Detail']"/>
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.tutorial.item.index')}}" type="info" label="Back"/>
        <x-form.a link="{{route('admin.tutorial.item.edit', $item->id)}}" type="success" label="Edit" />
    </div>
@endsection

@section('content')
    <x-layout.portlet id="all_area" active="1" label="Tutorial Detail">
        <x-layout.container>
            <p class="fs-20 mt-2">{{$item->title}}</p>
            <hr>
            {!! $item->description !!}
            <div class="row">
                @if($item->getMedia('image')->count()!==0)
                    <div class="col-12 order-2">
                        <div class="text-center mt-5">
                            <h3>IMAGE GALLERY</h3>
                        </div>
                        <div class="row lightgallery">
                            @foreach($item->getMedia('image') as $key=>$image)
                                <a href="{{$image->getUrl()}}"
                                   class="col-md-4 d-block mb-0 masonry-item progressive replace img-bg-effect-container gallery-image-class image-gallery-h-250 border"
                                >
                                    <img src="{{$image->getUrl('thumb')}}" alt="" class="img-bg preview img-bg-effect z-index-9 ">
                                    <div
                                        class="masonry-item-overlay z-index-9 h-cursor"
                                    >
                                        <span class="font-size40">+</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($item->getLinks()) + $item->getMedia('video')->count()!==0)
                       <div class="col-12 {{$item->gallery_order===0? 'order-1': 'order-3'}}">
                           <div class="text-center mt-5">
                               <h3>VIDEOS</h3>
                           </div>
                            <div class="row mt-5 lightgallery">
                                @foreach($item->getLinks() as $key=>$link)
                                    <a href="{{$link}}" class="col-md-4 mb-3">
                                        <img src="{{asset('assets/img/youtube.jpg')}}" class="w-100"/>
                                    </a>
                                @endforeach
                                @foreach($item->getMedia('video') as $key=>$video)
                                    <div data-poster="{{asset('assets/img/video.png')}}" data-sub-html="{{$item->title}}" data-html="#v{{$key}}" class="col-md-4 mb-3 h-cursor">
                                        <img src="{{asset('assets/img/video.png')}}" class="w-100"/>
                                    </div>
                                @endforeach
                            </div>

                            @foreach($item->getMedia('video') as $key=>$video)
                                <div style="display:none;" id="v{{$key}}">
                                    <video class="lg-video-object lg-html5" controls preload="none">
                                        <source src="{{$video->getUrl()}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
        </x-layout.container>
    </x-layout.portlet>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script>
        $('.lightgallery').lightGallery();
    </script>
@endsection
