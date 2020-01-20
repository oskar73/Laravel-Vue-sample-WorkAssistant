<div x-data="{table:false}">
    <div class="text-right">
        <span class="ml-auto btn border-radius-none m-btn--sm btn-outline-success mb-2 tooltip_3" x-bind:class="table? 'active': ''" x-on:click="table=true" title="Table View"><i class="fa fa-th"></i></span>
        <span class="ml-auto btn border-radius-none m-btn--sm btn-outline-success mb-2 tooltip_3" x-bind:class="table? '': 'active'" x-on:click="table=false" title="List View"><i class="fa fa-list-ul"></i></span>
    </div>
    <div class="table_area" x-show="table">
        <table class="table table-hover ajaxTable datatable">
            <thead>
                <tr>
                    <th class="text-center">Spot Name</th>
                    <th class="text-center">Page</th>
                    <th class="text-center">Ad Position (Type) </th>
                    <th class="text-center">Spot Price</th>
                    <th class="text-center">Select</th>
                </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td class="text-left">{{$item->name}}</td>
                    <td class="text-left">{{$item->getPageName()}}</td>
                    <td class="text-left">
                        {{$item->position->name}} <br>
                        (Width:{{json_decode($item->type)->width}}px, Height:{{json_decode($item->type)->height}}px)
                    </td>
                    <td class="text-right">
                        <span class="slashed_price_text">${{$item->standardPrice->slashed_price}}</span>
                        <span>${{$item->standardPrice->price}} / {{$item->standardPrice->getUnit()}}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{route('blogAds.spot', $item->slug)}}" class="btn border-radius-none btn-outline-success">Select</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="list_area" x-show="!table">
        <div class="row mt-5">
            @forelse($items as $item)
                <div class="col-lg-4 mb-5  m-auto">
                    <div class="project-grid-style3">
                        <a href="{{route('blogAds.spot', $item->slug)}}" class="inner-box">
                            <div class="position-relative">
                                <div class="position-absolute z-index-99 w-100 p-2">
                                    @if($item->featured) <span class="float-left text-success border-all pl-1 pr-1 border-success"> Featured </span> @endif
                                    @if($item->new) <span class="float-right text-danger border-all pl-1 pr-1 border-danger"> New </span> @endif
                                </div>
                                <figure data-href="{{$item->getFirstMediaUrl('image')}}" class="w-100 progressive replace img-bg-effect-container gallery-image-class">
                                    <img src="{{$item->getFirstMediaUrl('image', 'thumb')}}" alt="{{$item->name}}" class="preview img-bg img-bg-effect"/>
                                </figure>
                                <div class="overlay">
                                    <div class="overlay-inner">
                                        <div class="description">
                                            <div class="text">{{\Str::limit($item->description, 300)}}</div>
                                            <div  class="read-more"><span class="fa fa-angle-right"></span> See detail</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="project-desc  pb-2">
                                <div class="category">{{$item->name}}</div>
                                <span class="mb-2">({{$item->getPageName()}})</span> <br>
                                <div class="mt-3">
                                    {{$item->position->name}} <br>
                                    (Width:{{json_decode($item->type)->width}}px, Height:{{json_decode($item->type)->height}}px)
                                </div>
                                <div class="mt-2 btn_area d-flex justify-content-between">
                                    <div class="price_area mt-3 font-size18">
                                        <div class="text-dark">
                                            @if($item->standardPrice->slashed_price!==null)
                                                <span class="slashed_price_text ml-1 font-size16">
                                                ${{formatNumber($item->standardPrice->slashed_price)}}
                                            </span>
                                            @endif
                                            ${{formatNumber($item->standardPrice->price)}} / {{$item->standardPrice->getUnit()}}
                                        </div>
                                    </div>
                                    <span class="btn btn-success m-1 border-radius-none font-size12">
                                         Select
                                    </span>
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
    </div>
    <div>
        {{ $items->links() }}
    </div>
</div>
