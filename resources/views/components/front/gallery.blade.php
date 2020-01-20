<div class="row">
  <div class="col-12 order-2">
    @if($item->getMedia('image')->count()!==0)
      <div class="text-center mt-5">
        <h3>IMAGE GALLERY</h3>
      </div>
      <div class="row lightgallery">
        @foreach($item->getMedia('image') as $key=>$image)
          <a href="{{$image->getUrl()}}"
             class="col-md-4 d-block mb-0 masonry-item progressive replace img-bg-effect-container gallery-image-class"
          >
            <img src="{{$image->getUrl('thumb')}}" alt="" class="img-bg preview img-bg-effect">
            <div
              class="masonry-item-overlay z-index-9 h-cursor"
            >
              <span class="font-size40">+</span>
            </div>
          </a>
        @endforeach
      </div>
    @endif
  </div>
  <div class="col-12 {{$item->order===0? 'order-1': 'order-3'}}">
    @if(count($item->getLinks()) + $item->getMedia('video')->count()!==0)
      <div class="text-center mt-5">
        <h3>VIDEOS</h3>
      </div>
      <div class="row mt-5 lightgallery">
        @foreach($item->getLinks() as $key=>$link)
          <a href="{{$link}}" class="col-md-4 mb-3 masonry-item">
            <img src="{{getYoutubeImage($link)}}" class="w-100"/>
            <img src="{{asset("assets/img/youtube_button.png")}}" alt=""
                 style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:80px;">
            <div class="masonry-item-overlay">
              <img src="{{asset("assets/img/youtube_button.png")}}" alt=""
                   style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);width:80px;">
            </div>
          </a>
        @endforeach
        @foreach($item->getMedia('video') as $key=>$video)
          <div data-poster="{{asset('assets/img/video.png')}}" data-sub-html="{{$item->title}}" data-html="#v{{$key}}"
               class="col-md-4 mb-3 h-cursor masonry-item">
            <img src="{{asset('assets/img/video.png')}}" class="w-100"/>
            <div class="masonry-item-overlay"></div>
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
