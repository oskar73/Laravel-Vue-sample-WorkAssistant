@extends('layouts.app')

@section('title', 'Bizinabox Blog - ' . $post->title)

@section('seo')
    <link rel="canonical" href="{{ config('app.url') }}/blog/detail/{{ $post->slug }}">
@endsection

@section('style')
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
@endsection
@section('content')
    <x-front.hero
            image="{{ option('blog.front.header.image') }}"
            thumbImage="{{ option('blog.front.header.image.thumb') }}"
    />

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <x-front.blog-nav></x-front.blog-nav>
            </div>
        </div>
        <div class="row mt-5 blog_search_remove_section">
            <div class="col-lg-2">
                <div class="blog-ads-position-11125 text-right"></div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="blog-title-lg">{{$post->title}}</h1>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between position-relative tw-w-full align-items-center">
                                <div class="d-flex align-bottom">
                                    <a href="{{route('blog.author', $post->user->username??'')}}" class="mr-2">
                                        <img src="{{$post->user->avatar()}}" alt="{{$post->user->name}}"
                                             class="rounded-circle w-50px">
                                    </a>
                                    <span class="m-auto">
                                        <div class="d-flex align-items-end">
                                            <a href="{{route('blog.author', $post->user->username??'')}}">
                                                <h2 class="font-size24">{{$post->user->name}}</h2>
                                            </a>
                                            <div class="d-flex ml-auto">
                                                @if(Auth::check()&&$post->user->id==user()->id)
                                                @else
                                                    <livewire:following :user="$post->user" />
                                                @endif
                                            </div>
                                        </div>
                                        <span
                                                class="font-italic font-size12">Created at: {{$post->created_at->toDateString()}}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="blog-ads-position-11123"></div>
                        <div class="mt-4">
                            @if($post->video)
                                w
                                <div class="row lightgallery">
                                    <a href="{{$post->video}}" class="col-md-12 mb-3 masonry-item">
                                        <figure data-href="{{$post->getFirstMediaUrl("image")}}"
                                                class="progressive replace h-cursor f_post_item mb-3">
                                            <img src="{{$post->getFirstMediaUrl("image", "thumb")}}"
                                                 alt="{{$post->title}}" class="preview">
                                        </figure>
                                        <img src="{{asset("assets/img/youtube_button.png")}}" alt=""
                                             style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:80px;">
                                        <div class="masonry-item-overlay">
                                            <img src="{{asset("assets/img/youtube_button.png")}}" alt=""
                                                 style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:80px;">
                                        </div>
                                    </a>
                                </div>
                            @else
                                <figure data-href="{{$post->getFirstMediaUrl("image")}}"
                                        class="progressive replace h-cursor f_post_item mb-3">
                                    <img src="{{$post->getFirstMediaUrl("image", "thumb")}}" alt="{{$post->title}}"
                                         class="preview">
                                </figure>
                            @endif

                            <div class="row">
                                <div class="col-12 order-2">
                                    <div class="container">
                                        {!! $post->render() !!}
                                    </div>
                                </div>
                                <div class="col-12 {{$post->gallery_order===0? 'order-1': 'order-3'}}">

                                    @if($post->getMedia('images')->count()!==0)
                                        <div class="text-center mt-5">
                                            <h3>IMAGE GALLERY</h3>
                                        </div>
                                        <div class="row lightgallery">
                                            @foreach($post->getMedia('images') as $key=>$image)
                                                <a href="{{$image->getUrl()}}"
                                                   class="col-md-4 d-block mb-0 masonry-item progressive replace img-bg-effect-container gallery-image-class image-gallery-h-250"
                                                >
                                                    <img src="{{$image->getUrl('thumb')}}" alt=""
                                                         class="img-bg preview img-bg-effect">
                                                    <div
                                                            class="masonry-item-overlay z-index-9 h-cursor"
                                                    >
                                                        <span class="font-size40">+</span>
                                                    </div>
                                                </a>
                                            @endforeach


                                        </div>
                                    @endif

                                    @if(count($post->getLinks()) + $post->getMedia('video')->count()!==0)
                                        <div class="text-center mt-5">
                                            <h3>VIDEO GALLERY</h3>
                                        </div>
                                        <div class="row mt-1 lightgallery">
                                            @foreach($post->getLinks() as $key=>$link)
                                                <a href="{{$link}}" class="col-md-12 mb-3 masonry-item">
                                                    <img src="{{getYoutubeImage($link)}}" class="w-100" />
                                                    <img src="{{asset("assets/img/youtube_button.png")}}" alt=""
                                                         style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:80px;">
                                                    <div class="masonry-item-overlay">

                                                        <img src="{{asset("assets/img/youtube_button.png")}}" alt=""
                                                             style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:80px;">
                                                    </div>
                                                </a>
                                            @endforeach
                                            @foreach($post->getMedia('video') as $key=>$video)
                                                <div data-poster="{{asset('assets/img/video.png')}}"
                                                     data-sub-html="{{$video->title}}" data-html="#v{{$key}}"
                                                     class="col-md-12 mb-3 h-cursor masonry-item">
                                                    <img src="{{asset('assets/img/video.png')}}" class="w-100" />
                                                    <div class="masonry-item-overlay"></div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @foreach($post->getMedia('video') as $key=>$video)
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

                            <br>

                            @foreach($post->approvedTags as $tag)
                                <a href="{{route('blog.tag', $tag->slug)}}"
                                   class="btn btn-outline-info rounded-0 m-1">{{$tag->name}}</a>
                            @endforeach
                            <br>
                            <div class="d-flex justify-content-between mt-3 position-relative" id="like-area">

                                <livewire:favorite-post :post="$post" />

                                <div class="share position-absolute bottom-0 right-0">
                                    <x-front.socialShare></x-front.socialShare>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between tw-w-full">
                                <div class="my-2 tw-w-full">
                                    <div
                                            class="d-flex justify-content-between position-relative tw-w-full align-items-center">
                                        <div class="d-flex align-bottom">
                                            <a href="{{route('blog.author', $post->user->username??'')}}" class="mr-2">
                                                <img src="{{$post->user->avatar()}}" alt="{{$post->user->name}}"
                                                     class="rounded-circle w-50px">
                                            </a>
                                            <span class="m-auto">
                                        <a href="{{route('blog.author', $post->user->username??'')}}">
                                            <h2 class="font-size24">{{$post->user->name}}</h2>
                                        </a>
                                    </span>
                                        </div>
                                        <div class="d-flex">
                                            @if(Auth::check()&&$post->user->id==user()->id)
                                            @else
                                                <livewire:following :user="$post->user" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        {!! $post->user->introduction !!}
                                    </div>
                                    <div class="mt-2">
                                        @if($socialAccount)
                                            <livewire:social-share :socials="$socialAccount" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="blog-ads-position-11124"></div>
                            <hr>
                            <div class="mt-5">
                                <h5 class="p-area-title">You May Also Like</h5>
                                <div class="row">
                                    @foreach($randomposts as $r_post)
                                        <div class="col-sm-6">
                                            <a href="{{route('blog.detail', $r_post->slug)}}">
                                                <figure data-href="{{$r_post->getFirstMediaUrl("image")}}"
                                                        class="progressive replace h-cursor f_post_item">
                                                    <img src="{{$r_post->getFirstMediaUrl("image", "thumb")}}"
                                                         alt="{{$r_post->title}}" class="preview">
                                                    <div
                                                            class="position-absolute text-white post-title-bg z-index-2 w-100 bottom-0 p-3">
                                                        <p class="blog_title_medium">{{$r_post->title}}</p>
                                                        <span
                                                                class="post_small_info text-white">{{$r_post->created_at->diffForHumans()}}</span>
                                                        &nbsp;&nbsp;
                                                        <span class="post_small_info"><i
                                                                    class="fa fa-eye"></i> </span>{{$r_post->view_count}}
                                                        &nbsp;
                                                        <span class="post_small_info"><i
                                                                    class="fa fa-comment"></i> </span>{{$r_post->approved_comments_count}}
                                                        &nbsp;
                                                        <span class="post_small_info"><i
                                                                    class="fa fa-heart"></i> </span>{{$r_post->favoriters_count}}
                                                    </div>
                                                </figure>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>


                        <div class="all_comment_area" id="comment_section">

                        </div>
                        <div class="mt-4">
                            <h5 class="p-area-title">Leave a comment</h5>
                        </div>


                        @auth
                            <form action="{{route('blog.postComment', $post->id)}}" method="POST"
                                  class="post_comment_form">
                                @csrf
                                @honeypot
                                <div class="leave_comment_area my-3">
                                    <div id="comment" class="minh-100 comment_box comment default_comment"></div>
                                    <div class="form-control-feedback error-comment"></div>
                                    <div class="text-right">
                                        <button class="btn btn-outline-success mt-2 smtBtn" type="submit">Submit
                                        </button>
                                    </div>
                                </div>
                            </form>

                        @else
                            Please <a href="{{route('cart.login')}}?redirect={{url()->current()}}#leave_comment"
                                      class="underline">Login</a> to post comment here.
                        @endauth
                    </div>
                    <div class="col-lg-4">
                        <div class="blog-ads-position-11120"></div>

                        <x-front.blog-popular></x-front.blog-popular>

                        <div class="mb-5">
                            <h5 class="p-area-title">Subscribe to this post.</h5>
                            <div class="mb-3">To get notification about update, comments to this post, please
                                subscribe.
                            </div>
                            <div class="text-center">
                                <livewire:subscribe-post :post="$post" />
                            </div>
                        </div>

                        <div class="blog-ads-position-11121"></div>

                        <x-front.blog-discussed></x-front.blog-discussed>

                        <div class="blog-ads-position-11122"></div>

                        <div class="blog-ads-position-11127"></div>

                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="blog-ads-position-11126"></div>
            </div>
        </div>
    </div>
    <div class="blog_append_section"></div>
@endsection
@section('script')
    <script>
        var locationUrl = window.location.href
        locationUrl = locationUrl.slice(0, locationUrl.indexOf('?'))
        var goToIndex = locationUrl.slice(locationUrl.indexOf('#'))

        var element_to_scroll_to = $(goToIndex)[0]
        if (element_to_scroll_to) {
            element_to_scroll_to.scrollIntoView()
        }
    </script>
    @livewireScripts
    <script src="{{s3_asset('vendors/tinymce/tinymce.min.js')}}"></script>
    <script> var slug = "{{$post->slug}}", post_id = "{{$post->id}}" </script>
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script src="{{asset('assets/js/front/blog/detail.js')}}"></script>
@endsection
