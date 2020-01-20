<ol class="breadcrumb">
    {!! $title !!}
</ol>

@if($category!==null)
    <a href="{{$category->getFirstMediaUrl('image')}}" class="w-100 progressive replace tw-max-w-xl tw-mx-auto">
        <img src="{{$category->getFirstMediaUrl('image', 'thumb')}}"
             alt="{{$category->name}}"
             class=" preview"
        >
    </a>
    <div class="mt-5 white-space-pre-line">
        {{$category->description}}
    </div>
@endif
<div class="tw-grid tw-grid-cols-fill-240 tw-mt-4 tw-gap-2.5">
    @forelse($items as $item)
        @php
            $video = optional($item->getMedia('video')->first())->getUrl();
            $youtube = optional($item->getLinks())[0];
        @endphp
        <div class="tw-bg-white tw-rounded-sm tw-overflow-hidden tw-shadow">
            <a href="javascript:void(0);" class="inner-box" style="text-decoration: none">
                <div class="tw-absolute tw-z-[99] tw-p-2">
                    @if($item->featured)
                        <span class="tw-bg-[#ffffff99] tw-rounded-sm tw-p-1 tw-text-green-700 tw-shadow tw-ml-2.5"> Featured </span>
                    @endif
                    @if($item->new)
                        <span class="tw-bg-[#ffffff99] tw-rounded-sm tw-p-1 tw-text-red-700 tw-shadow tw-ml-2.5"> New </span>
                    @endif
                </div>
                <div class="aspect-view" style="--ratio: 1.5">
                    <div>
                        <figure data-href="{{$item->getFirstMediaUrl("thumbnail")}}"
                                class="w-100 progressive replace m-0">
                            <img src="{{$item->getFirstMediaUrl("thumbnail","thumb")}}" alt="{{$item->title}}"
                                 class="preview img-full" />
                        </figure>
                    </div>
                </div>
                <div>
                    <div class="tw-bg-gray-200 tw-text-gray-900 tw-p-4">
                        <b>{{$item->name}}</b> <br />
                        <small>By Bizinabox</small>
                    </div>
                    <div class="tw-p-4">
                        {{-- @if($type == 'website') --}}
                        <div class="tw-flex tw-items-center tw-justify-between mt-2">
                            <button data-video="{{$video}}" data-youtube="{{$youtube}}"
                                    @if(!$video && !$youtube) disabled title="Video is not available"
                                    @endif class="btn btn-sm tw-text-xs btn-outline-info mr-2 video_btn">View Video
                            </button>
                            <div>
                                <button data-id="{{$item->id}}"
                                        class="btn btn-sm tw-text-xs btn-outline-info mr-2 view_btn">View Details
                                </button>
                                <button data-id="{{$item->id}}" data-slug="{{$item->slug}}"
                                        data-featured="{{$item->featured}}"
                                        @if(in_array($item->slug, $modules)) disabled
                                        @endif class="btn btn-sm tw-text-xs btn-outline-info choose_module_btn">
                                    @if(in_array($item->slug, $modules))
                                        Chosen
                                    @else
                                        Choose App
                                    @endif
                                </button>
                            </div>
                        </div>
                        <div class="tw-flex tw-items-center tw-justify-between mt-2">
                            <button class="btn btn-sm tw-text-xs btn-outline-info mr-2 apps_btn"><span
                                        class="app_chosen_count">{{ count($modules ?? []) }}</span> of Chosen Apps
                            </button>
                            <a href="{{$isDashboard ? '#/change_package' : route('package.index')}}"
                               data-id="{{$item->id}}"
                               class="btn btn-sm tw-text-xs btn-outline-success">{{ $isDashboard ? 'Change/Upgrade Package' : 'Choose or Change a Package' }}</a>
                        </div>
                        {{-- @else
                        <div class="btn_area tw-flex tw-justify-between">
                            <div class="price_area mt-3 font-size18">
                                <div class="tw-text-gray-700">
                                    @if($item->standardPrice->slashed_price!==null)
                                        <span class="slashed_price_text ml-1 font-size16">
                                            ${{formatNumber($item->standardPrice->slashed_price)}}
                                        </span>
                                    @endif
                                    ${{formatNumber($item->standardPrice->price)}}
                                    @if($item->standardPrice->recurrent) / {{periodName($item->standardPrice->period, $item->standardPrice->period_unit)}} @endif
                                </div>
                            </div>
                        </div>
                        @endif --}}
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-md-12 text-center">
            <h3>No item..</h3>
        </div>
    @endforelse
</div>
<div class="tw-mt-4">
    {{ $items->links() }}
</div>
