<div class="row mt-2">
    @forelse($items as $item)
        @if(!$availableOnly || !($item->module > 0 && $item->module < $selectedModulesNum))
            <div class="col-lg-4 mb-5">
                <div class="tw-shadow-md">
                    <div class="inner-box package-card{{$item->module > 0 && $item->module < $selectedModulesNum ? ' tw-opacity-40' : ''}}"
                         data-limit="{{$item->module}}">
                        <div class="position-relative">
                            <div class="position-absolute z-index-99 w-100 p-2">
                                @if($item->featured)
                                    <span class="float-left text-success border-all pl-1 pr-1 border-success"> Featured </span>
                                @endif
                                @if($item->new)
                                    <span class="float-right text-danger border-all pl-1 pr-1 border-danger"> New </span>
                                @endif
                            </div>
                            <figure data-href="{{$item->getFirstMediaUrl('thumbnail')}}"
                                    class="w-100 progressive package-item replace">
                                <img src="{{$item->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$item->title}}"
                                     class="preview w-100" />
                            </figure>
                        </div>
                        <div class="project-desc tw-p-2">
                            <div class="category">{{$item->name}}</div>
                            <div class="btn_area d-flex justify-content-between">
                                <div class="price_area mt-3 font-size18">
                                    <div class="text-dark tw-text-sm">
                                        @if($item->standardPrice->slashed_price!==null)
                                            <span class="slashed_price_text ml-1 font-size16">
                                            ${{formatNumber($item->standardPrice->slashed_price)}}
                                        </span>
                                        @endif
                                        @if($item->standardPrice->price)
                                            ${{formatNumber($item->standardPrice->price)}}
                                            @if($item->standardPrice->recurrent)
                                                / {{periodName($item->standardPrice->period, $item->standardPrice->period_unit)}}
                                            @endif
                                        @else
                                            NAN
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 tw-flex tw-items-center tw-gap-1">
                                <span class="btn btn-sm {{ $item->module > 0 && $item->module < $selectedModulesNum ? 'btn-danger' : 'btn-success' }} m-1 border-radius-none font-size12">
                                    {{ $item->module < 0 ? 'Unlimited Apps' : $item->module.' Available Apps'}}
                                </span>
                                    <div>
                                        <a href="#"
                                           class="btn btn-sm btn-success m-1 border-radius-none choose_package font-size12"
                                           data-id="{{$item->id}}">
                                            Choose
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <div class="col-md-12 text-center">
            <h3>No item..</h3>
        </div>
    @endforelse
</div>
<div>
    {{ $items->links() }}
</div>
