<div class="d-block box-item-container" >
    <div class="row no-gutters">
        <div class="col-12">
            <div class="preview-wrapper">
                <div class="thumbnail_overlay">
                    <div  class="img_area">
                        <figure data-href="{{$item->getFirstMediaUrl('thumbnail')}}" class="w-100 progressive replace m-0">
                            <img src="{{$item->getFirstMediaUrl('thumbnail', 'thumb')}}"  alt="{{$item->title}}" class="preview img-fill" />
                        </figure>
                    </div>
                </div>
                <div class="portfolio">
                    <a href="{{route('directory.detail', $item->slug)}}">View Listing</a>
                </div>
            </div>
        </div>
    </div>
</div>



