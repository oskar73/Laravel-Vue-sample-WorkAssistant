<div class="tw-bg-white tw-rounded-sm tw-overflow-hidden tw-shadow">
    <div class="tw-absolute tw-z-[99] tw-p-2">
        @if($template->featured) <span class="tw-bg-[#ffffff99] tw-rounded-sm tw-p-1 tw-text-green-700 tw-shadow tw-ml-2.5"> Featured </span> @endif
        @if($template->new) <span class="tw-bg-[#ffffff99] tw-rounded-sm tw-p-1 tw-text-red-700 tw-shadow tw-ml-2.5"> New </span> @endif
    </div>
    <div class="aspect-view" style="--ratio: 1.5">
        <div>
            <figure data-href="{{$template->getFirstMediaUrl("preview")}}" class="w-100 progressive replace m-0">
                <img src="{{$template->getFirstMediaUrl("preview","thumb")}}" alt="{{$template->name}}" class="preview img-full" />
            </figure>
        </div>
    </div>
    <div class="tw-p-4">
        <b>{{$template->name}}</b> <br/>
        <small class="text-gray">By Bizinabox</small>
    </div>
</div>