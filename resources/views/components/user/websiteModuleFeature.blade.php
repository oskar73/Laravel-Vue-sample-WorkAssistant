
<div class="row">
    @if($module->getMedia('image')->count()!==0)
        <div class="col-12 order-2">
            <div class="lightgallery">
                @foreach($module->getMedia('image') as $key=>$image)
                    <a href="{{$image->getUrl()}}"
                       class="d-block m-2 masonry-item progressive replace img-bg-effect-container gallery-image-class image-gallery-h-250 border"
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
    @if(count($module->getLinks()) + $module->getMedia('video')->count()!==0)
        <div class="col-12 {{$module->gallery_order===0? 'order-1': 'order-3'}}">
            <div class="mt-5 lightgallery">
                @foreach($module->getLinks() as $key=>$link)
                    <a href="{{$link}}" class="m-2 w-100 d-block">
                        <img src="{{asset('assets/img/youtube.jpg')}}" class="w-100"/>
                    </a>
                @endforeach
                @foreach($module->getMedia('video') as $key=>$video)
                    <div data-poster="{{asset('assets/img/video.png')}}" data-sub-html="{{$module->name}}" data-html="#v{{$key}}" class="w-100  m-2 h-cursor">
                        <img src="{{asset('assets/img/video.png')}}" class="w-100"/>
                    </div>
                @endforeach
            </div>

            @foreach($module->getMedia('video') as $key=>$video)
                <div style="display:none;" id="v{{$key}}">
                    <video class="lg-video-object lg-html5" controls preload="none">
                        <source src="{{$video->getUrl()}}">
                        Your browser does not support HTML5 video.
                    </video>
                </div>
            @endforeach
        </div>
    @endif
</div>
<script>
    $('.lightgallery').lightGallery();
</script>
