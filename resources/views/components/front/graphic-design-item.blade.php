<div class="tw-w-full tw-flex tw-justify-center tw-items-center">
    <div class="tw-relative tw-w-full " style="padding-top: 100%">
        <div class="tw-absolute tw-w-full tw-h-full top-0 tw-flex tw-justify-center tw-items-center tw-border tw-group tw-cursor-pointer">
            @if($box === null)
                <figure data-href="{{ s3_asset('img/front/plugin.png') }}" class="tw-h-full tw-object-cover progressive replace">
                    <img src="{{ s3_asset('img/front/plugin.png') }}" alt="design image" class="preview" />
                </figure>
            @else
                <figure data-href="{{$box['preview']}}" class="w-100 progressive replace m-0">
                    <img src="{{$box['preview']}}" class="preview img-fill" alt="" />
                </figure>
                <a class="tw-absolute tw-shadow-lg tw-text-gray-700 tw-bg-white tw-py-2 tw-px-5 focus:tw-bg-white tw-hidden group-hover:tw-block" href="{{route('graphics.choose', $box['hash'])}}">EDIT TEMPLATE</a>
                @if($box['premium'])
                    <span class="tw-absolute tw-right-2 tw-bottom-2 tw-border tw-border-pink-500 tw-text-pink-500 tw-p-1 tw-text-xs">PREMIUM</span>
                @else
                    <span class="tw-absolute tw-right-2 tw-bottom-2 tw-border tw-border-green-500 tw-text-green-500 tw-p-1 tw-text-xs">FREE</span>
                @endif
            @endif
        </div>
    </div>
</div>
