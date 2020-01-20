<div class="blog-item">
    <div class="logo_item_preview_container">
        <div class="preview">
            <div class="row no-gutters thumbnail">
                <div class="col-12 position-relative">
                    <div class="thumbnail_overlay">
                        <a href="{{route('portfolio.detail', $item->slug)}}" class="img_area">
                            <figure data-href="{{$item->getFirstMediaUrl('thumbnail')}}" class="w-100 progressive replace img-bg-effect-container mb-0 z-0">
                                <img src="{{$item->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$item->title}}" class="preview"/>
                            </figure>
                        </a>
                        <div class="edit-template-container">
                            <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center">
                                <a href="{{route('portfolio.detail', $item->slug)}}" class="edit-template hide-white-loading">
                                    <span>View Portfolio</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

