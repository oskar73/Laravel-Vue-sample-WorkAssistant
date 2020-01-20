<div class="tw-h-[320px] tw-w-full tw-bg-white tw-rounded tw-overflow-hidden tw-cursor-pointer hover:tw-shadow-xl tw-transition-shadow tw-duration-800">
    @if($template === null)
        <figure data-href="{{ s3_asset('img/front/plugin.png') }}" class="tw-h-full tw-w-full progressive replace">
            <img src="{{ s3_asset('img/front/plugin.png') }}" alt="bizinabox home page" class="preview img-full" />
        </figure>
    @else
        <div class="tw-flex tw-flex-col tw-h-full tw-flex-col-reverse">
            <div class="position-absolute z-index-99 p-2">
                @if($template->featured)
                    <span class="template-featured"> Featured </span>
                @endif
                @if($template->new)
                    <span class="template-new"> New </span>
                @endif
            </div>
            <div class="template-footer tw-p-1">
                <b>{{$template->name}}</b> <br />
                <small class="text-gray">By Bizinabox</small>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="javascript:void(0)" data-url="{{route('package.index', ['template' => $template->slug])}}" class="btn btn-sm btn-outline-info mr-2 choose_btn">Choose Template</a>
                    <a href="javascript:void(0)" data-slug="{{$template->slug}}" class="template_item_choose btn btn-sm btn-outline-info">Preview</a>
                </div>
            </div>
            <div class="tw-flex tw-h-full tw-overflow-hidden" >
                <figure data-href="{{$template->getFirstMediaUrl("preview")}}" class="w-100 progressive replace">
                    <img src="{{$template->getFirstMediaUrl("preview","thumb")}}" alt="{{$template->name}}" class="preview" />
                </figure>
            </div>
        </div>
    @endif
</div>
