<div class="boxes">
    <div class="container my-5" style="max-width: 1400px!important;" >
        <div class="col-12 overflow-x-auto cat_scroll_container my-3">
            <div class="row flex-row flex-nowrap">
                @foreach($boxes1 as $box)
                    @include('components.front.directory-box-item')
                @endforeach
            </div>
        </div>

        <div class="col-12 overflow-x-auto cat_scroll_container my-3">
            <div class="row flex-row flex-nowrap align-items-center boxes-2">
                @foreach($boxes2 as $i => $box)
                    @if($box === 'home')
                        <div class="col-md-4 col-sm-6 col-12 col-sm-3 col-6 home-box-{{$i}}">
                            <a href="{{route('directory.categories')}}"
                               class="d-block box-item-container">
                                <div class="row no-gutters thumbnail">
                                    <div class="col-12 position-relative">
                                        <div class="preview-wrapper" @if($i === 2) style="--aspect-ratio: 1.5" @endif>
                                            <div class="thumbnail_overlay">
                                                <div class="img_area">
                                                    <figure data-href="{{uploadUrl(option('directory.front.box.image'),'center-box.png')}}" class="w-100 progressive replace m-0" >
                                                        <img src="{{uploadUrl(option('directory.front.box.image.thumb'),'center-thumb.png')}}" alt="bizinabox home page portfolio" class="preview img-full" />
                                                    </figure>
                                                </div>
                                                <div class="center-title">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        @include('components.front.directory-box-item')
                    @endif
                @endforeach
            </div>
        </div>

        <div class="col-12 overflow-x-auto cat_scroll_container my-3">
            <div class="row flex-row flex-nowrap">
                @foreach($boxes3 as $box)
                    @include('components.front.directory-box-item')
                @endforeach
            </div>
        </div>

    </div>
</div>
@push('script')
@endpush
