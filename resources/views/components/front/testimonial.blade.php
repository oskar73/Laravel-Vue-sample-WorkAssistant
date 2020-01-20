@if($items->count()!=0)
    <div class="bz-front-component--testimonial">
        <section class="bg-img mt-5" data-overlay-dark="0">
            <div class="container">
                <div class="section-heading"><span>Testimonial</span>
                    <h3>What Our Client Say</h3>
                </div>
                <div class="owl-carousel owl-theme testmonials-style1-{{ $items->count() > 3 ? 'default' : $items->count() }} text-center">
                    @foreach($items as $item)
                        <div class="center-col width-65 sm-width-90 margin-20px-bottom xs-margin-10px-bottom">
                            <p class="margin-40px-bottom sm-margin-35px-bottom xs-margin-30px-bottom white-space-pre-line">
                                {{$item->comment}}
                            </p>
                            <a href="{{$item->getFirstMediaUrl('image')}}" class="wh-200px progressive replace m-auto rounded-circle overflow-hidden">
                                <img src="{{$item->getFirstMediaUrl('image', 'thumb')}}" alt="{{$item->name}}" class="preview wh-200px"/>
                            </a>
                            <br>
                            <h4>{{$item->name}}</h4>
                            <h6>{{$item->title}}</h6>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endif
