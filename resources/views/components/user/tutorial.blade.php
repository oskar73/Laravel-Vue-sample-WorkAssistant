<div class="row">
    @forelse($tutorials as $tutorial)
        <div class="col-md-4 mb-5">
            <a href="#/{{$tutorial->module->slug??'basic'}}/{{$tutorial->slug}}" class="tutorial_item d-block d_slider_item" style="border:2px solid #32a506">
                <div class="position-relative">
                    <figure data-href="{{$tutorial->getFirstMediaUrl('thumbnail')}}" class="w-100 progressive replace mb-0">
                        <img src="{{$tutorial->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$tutorial->title}}" class="preview w-100"/>
                    </figure>
                    <span class="position-absolute middle-center m-1 btn m-btn--square m-btn m-btn--custom btn-bizinabox active">{{$tutorial->title}}</span>
                </div>
            </a>
        </div>
    @empty
        <div class="col-md-12 text-center">No items</div>
    @endforelse
</div>
