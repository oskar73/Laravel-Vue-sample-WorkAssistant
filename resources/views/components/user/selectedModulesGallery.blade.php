@foreach($items as $item)
    <div class="tw-bg-white tw-rounded-sm tw-overflow-hidden tw-shadow">
        <a href="javascript:void(0);" class="inner-box" style="text-decoration: none">
            <div class="aspect-view" style="--ratio: 1.5">
                <div>
                    <figure data-href="{{$item->getFirstMediaUrl("thumbnail")}}"
                            class="w-100 progressive replace m-0">
                        <img src="{{$item->getFirstMediaUrl("thumbnail","thumb")}}" alt="{{$item->title}}"
                             class="preview img-full" />
                    </figure>
                </div>
            </div>
        </a>
        <div><a href="javascript:void(0);" class="inner-box" style="text-decoration: none">
                <div class="tw-bg-gray-200 tw-text-gray-900 tw-p-4">
                    <b>{{$item->name}}</b> <br />
                </div>
            </a>
        </div>
    </div>
@endforeach
