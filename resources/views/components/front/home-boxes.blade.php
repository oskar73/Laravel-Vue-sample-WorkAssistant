<div class="container mt-5 home-boxes" style="max-width: 1400px!important;" >

    <div class="row flex-nowrap overflow-x-auto cat_scroll_container home-boxes-top">
        @foreach(array_slice($boxes, 0, 8) as $i=>$box)
            <div class="col-lg-2 col-md-3 col-6 home-boxes-top-{{$i}}">
                @include('components.front.home-box-item')
            </div>
        @endforeach
    </div>

    <div class="row home-boxes-middle">
        <div class="col-lg-4 col-md-3 d-md-block d-none">
            <div class="row">
                @foreach(array_slice($boxes, 6, 4) as $i=>$box)
                    <div class="col-lg-6 col-md-12 home-boxes-middle-left-{{$i}}">
                        @include('components.front.home-box-item')
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{option('home.front.box.link', 'javascript:void(0)')}}"
               class="d-block home-box-item-container">
                <div class="row no-gutters thumbnail">
                    <div class="col-12 position-relative">
                        <div class="preview-wrapper">
                            <div class="thumbnail_overlay">
                                <div  class="img_area">
                                    <figure data-href="{{ option('home.front.box.image') }}" class="w-100 progressive replace m-0" >
                                        <img src="{{ option('home.front.box.image-thumb') }}" alt="bizinabox home page" class="preview img img-full" />
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-3 d-md-block d-none">
            <div class="row">
                @foreach(array_slice($boxes, 10, 4) as $i=>$box)
                    <div class="col-lg-6 col-md-12 home-boxes-middle-right-{{$i}}">
                        @include('components.front.home-box-item')
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row flex-nowrap overflow-x-auto cat_scroll_container home-boxes-bottom">
        @foreach(array_slice($boxes, 12, 8) as $i=>$box)
            <div class="col-lg-2 col-md-3 col-6 home-boxes-bottom-{{$i}}">
                @include('components.front.home-box-item')
            </div>
        @endforeach
    </div>
</div>

@push('script')
@endpush
