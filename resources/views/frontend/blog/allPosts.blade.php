@extends('layouts.app')

@section('title', 'All-Posts')

@section('style')
@endsection
@section('content')
    <x-front.hero
        image="{{ option('blog.front.header.image') }}"
        thumbImage="{{ option('blog.front.header.image.thumb') }}"
    />

    <div class="container-fluid mt-3" >
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <x-front.blog-nav></x-front.blog-nav>
            </div>
        </div>
    </div>
    <div class="container-lg blog_append_section">
        <div class="row mt-5">
            <div class="col-lg-3">
                <div class="filter-widget">
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
            <div class="col-lg-9">
                <div class="all_post_ajax_area">
                    <div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/front/blog/allPosts.js')}}"></script>
@endsection
