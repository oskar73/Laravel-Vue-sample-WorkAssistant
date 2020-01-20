<div class="row mt-5">
    @forelse($items as $item)
        <div class="col-lg-4 mb-5">
            <div class="project-grid-style3">
                <a href="{{route('blog.package.detail', $item->slug)}}" class="inner-box">
                    <div class="position-relative">
                        <div class="position-absolute z-index-99 w-100 p-2">
                            @if($item->featured) <span class="float-left text-success border-all pl-1 pr-1 border-success"> Featured </span> @endif
                            @if($item->new) <span class="float-right text-danger border-all pl-1 pr-1 border-danger"> New </span> @endif
                        </div>
                        <x-global.aspect-view :ratio="3/4">
                            <figure data-href="{{$item->getFirstMediaUrl('thumbnail')}}" class="w-100 h-100 progressive replace" style="height:300px !important; width: 300px !important">
                                <img src="{{$item->getFirstMediaUrl('thumbnail', 'thumb')}}" alt="{{$item->title}}" class="img-full preview w-100"/>
                            </figure>
                        </x-global.aspect-view>
                        <div class="overlay">
                            <div class="overlay-inner">
                                <div class="description">
                                    <div class="text">{{\Str::limit($item->description, 300)}}</div>
                                    <div  class="read-more"><span class="fa fa-angle-right"></span> See detail</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="project-desc d-flex flex-column justify-content-between h-140px pb-2">
                        <div class="category">{{$item->name}}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price_area font-size18">
                                <div class="text-dark">
                                    @if($item->standardPrice->slashed_price!==null)
                                        <span class="slashed_price_text ml-1 font-size16">
                                            ${{formatNumber($item->standardPrice->slashed_price)}}
                                        </span>
                                    @endif
                                    ${{formatNumber($item->standardPrice->price)}}
                                    @if($item->standardPrice->recurrent) / {{periodName($item->standardPrice->period, $item->standardPrice->period_unit)}} @endif
                                </div>
                            </div>
                            <div class="width-120px text-right">
                                @if($item->approved_prices_count > 1)
                                    <span class="btn btn-success m-1 border-radius-none font-size12 w-100">
                                        <i class="fas fa-eye margin-5px-right"></i> View Pricing
                                    </span>
                                @else
                                    <span class="btn btn-outline-success m-1 border-radius-none font-size12  w-100">
                                        <i class="fas fa-eye margin-5px-right"></i> View Details
                                    </span>
                                    <span class="btn btn-success m-1 border-radius-none add_to_cart font-size12 w-100" data-id="{{$item->id}}">
                                        <i class="fas fa-shopping-cart margin-5px-right"></i> Add to cart
                                    </span>
                                @endif
                                <span class="btn btn-success m-1 border-radius-none d-none gotocart font-size12">
                                    Go to cart <i class="fas fa-arrow-right margin-5px-left"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @empty
        <div class="col-md-12 text-center">
            <h3>No item..</h3>
        </div>
    @endforelse
</div>
<div>
    {{ $items->links() }}
</div>
