@if($box === null)
    <div class="box-item-container">
        <div class="row no-gutters thumbnail">
            <div class="col-12 position-relative">
                <div class="preview-wrapper">
                    <div class="thumbnail_overlay">
                        <div  class="img_area">
                            <figure data-href="{{ s3_asset('img/front/plugin.png') }}" class="w-100 progressive replace m-0" >
                                <img src="{{ s3_asset('img/front/plugin.png') }}" alt="bizinabox home page portfolio" class="preview" />
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="d-block box-item-container">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="preview-wrapper">
                    <div class="thumbnail_overlay">
                        <div  class="img_area">
                            <figure data-href="{{$box->getFirstMediaUrl("thumbnail")}}" class="w-100 progressive replace m-0">
                                <img src="{{$box->getFirstMediaUrl("thumbnail","thumb")}}" alt="{{$box->name}}" class="preview" />
                            </figure>
                        </div>
                    </div>
                    <div class="portfolio">
                        <a href="{{route('portfolio.detail', $box->slug)}}">View Portfolio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
