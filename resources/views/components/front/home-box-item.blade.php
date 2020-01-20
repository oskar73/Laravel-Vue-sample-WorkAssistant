@if($box === null)
    <div class="home-box-item-container">
        <div class="row no-gutters thumbnail">
            <div class="col-12 position-relative">
                <div class="preview-wrapper">
                    <div class="thumbnail_overlay">
                        <div  class="img_area">
                            <x-global.aspect-view>
                                <figure data-href="{{ s3_asset('img/front/plugin.png') }} class="w-100 h-100 progressive replace m-0" >
                                    <img src="{{ s3_asset('img/front/plugin.png') }}" alt="bizinabox home page" class="preview img-full" />
                                </figure>
                            </x-global.aspect-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="d-block home-box-item-container" >
        <div class="row no-gutters">
            <div class="col-12">
                <div class="preview-wrapper">
                    <a href="{{$box->link ? $box->link : 'javascript:void(0)'}}">
                        <div class="thumbnail_overlay">
                            <div  class="img_area">
                                <figure data-href="{{$box->getFirstMediaUrl("image")}}" class="w-100 progressive replace m-0">
                                    <img src="{{$box->getFirstMediaUrl("image","thumb")}}" alt="{{$box->name}}" class="preview img-full" />
                                </figure>
                            </div>
                            <div class="description">
                                <div class="fs-12px hide-white-loading p-3 line-height-14px cat_desc_text">
                                    {{$box->description}}
                                </div>
                            </div>
                            <div class="text-name">
                                <span class="fs-12px">{{$box->name}}</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
