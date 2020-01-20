<div class="home-front-component--feature">
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-12">
                <div class="section-heading title-style3">
                    <h3 class="font-size22 xs-font-size18">Related Community</h3>
                </div>
            </div>

            <div class="carousel-style1 col-12">
                <div class="product-grid control-top owl-carousel owl-theme">

                    @foreach($sliders as $slider)
                        <div>

                            <div class="product-details">

                                <div class="product-img">
                                    @if($slider->featured_name!=null)
                                        <div class="label-offer bg-red">{{$slider->featured_name}}</div>
                                    @endif
                                    <a  @if($slider->model_type === 'url') href="{{$slider->model_id}}" target='_blank' @else href="{{route('slider.detail', $slider->id)}}" @endif>
                                        <figure data-href="{{$slider->getFirstMediaUrl('image')}}" class="w-100 progressive replace">
                                            <img src="{{$slider->getFirstMediaUrl('image', 'thumb')}}" alt="{{$slider->name}}" class="preview w-100"/>
                                        </figure>
                                    </a>
                                    <div class="product-cart">
                                        {{--                                <a href="{{route('slider.detail', $slider->id)}}"><i class="fas fa-eye"></i></a>--}}
                                        {{--                                <a href="javascript:void(0);" class="slider_add_to_cart"--}}
                                        {{--                                   data-id="{{$slider->id}}"--}}
                                        {{--                                   data-route="@if(Route::has($slider->modelToSlug($slider->model_type) . '.addtoCart')&&$slider->model->id!=null){{route($slider->modelToSlug($slider->model_type) . '.addtoCart', $slider->model->id)}}@endif"--}}
                                        {{--                                ><i class="fas fa-cart-plus"></i></a>--}}
                                    </div>
                                </div>

                                <div data-model-type="{{ $slider->model_type }}" class="product-info">
                                    @if($slider->model_type === 'url')
                                        <a href="{{ $slider->model_id }}" target="_blank">{{$slider->name}}</a>
                                    @else
                                        <a href="{{route('slider.detail', $slider->id)}}">{{$slider->name}}</a>
                                    @endif
                                    @if($slider->model_type !== 'url')
                                    <p class="price text-center no-margin">
                                        @if(($slider->model->standardPrice?? null) !==null)
                                            @if($slider->model->standardPrice->slashed_price)
                                                <span class="red line-through">
                                            ${{$slider->model->standardPrice->slashed_price}}

                                        </span>
                                            @endif
                                            @if($slider->model->standardPrice->price)
                                                <span>
                                                    ${{$slider->model->standardPrice->price}}
                                                    @if($slider->model->standardPrice->recurrent)
                                                        / {{$slider->model->standardPrice->period}} {{Str::plural($slider->model->standardPrice->period_unit, $slider->model->standardPrice->period)}}
                                                    @endif
                                                </span>
                                            @endif
                                        @else
                                            @if($slider->model->slashed_price ?? null)
                                                <span class="red line-through">
                                            ${{$slider->model->slashed_price}}
                                        </span>
                                            @endif
                                            @if($slider->model->price ?? null)
                                                <span>${{$slider->model->price}}</span>
                                            @endif
                                        @endif
                                    </p>
                                    @endif
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>
