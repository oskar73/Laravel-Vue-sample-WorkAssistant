<div class="bz-front-component--home-slider">
    <div class="rev_slider_wrapper">
        <div id="home-banner-slider" class="rev_slider" data-version="5.4.5" style="background-color: #0d47a13f">
            <ul>
                @foreach($sliders as $i =>  $slider)
                    <li>
                        <img src="{{$slider->getFirstMediaUrl('image')}}?v={{$i}}" alt="slide1" class="rev-slidebg">
                        @if($slider->title)
                            <div class="tp-caption tp-resizeme alt-font fw-700 text-title"
                                 @if(!($i % 2)) data-x= "30" @else data-x="['right','right','right','right']" @endif
                                 data-y="center"
                                 data-voffset="[-100,-120,-130,-140]"
                                 data-fontsize="[58,48,36,32]" data-lineheight="[60,52,40,36]" data-width="[none, none, none, 300]" data-whitespace="[nowrap, nowrap, nowrap, normal]"
                                 data-frames='[{
                                            "delay":1500,
                                            "speed":1400,
                                            "frame":"1",
                                            "from":"y:150px;opacity:0;",
                                            "ease":"Power3.easeOut",
                                            "to":"o:1;"
                                            },{
                                            "delay":"wait",
                                            "speed":1000,
                                            "frame":"999",
                                            "to":"opacity:0;","ease":"Power3.easeOut"
                                        }]' data-splitout="none"
                            >
                                <span style="color: white!important;">{{$slider->title}}</span>
                            </div>
                        @endif

                        @if($slider->description)
                            <div class="tp-caption tp-resizeme slider-text text-description py-3 {{$i % 2?'text-right':'text-left'}}"
                                 @if(!($i % 2)) data-x="30" @else data-x="['right','right','right','right']" @endif
                                 data-y="center" data-voffset="[5,0,-20,-5]" data-fontsize="[21,21,21,21]" data-lineheight="34"
                                 data-width="[600, 500, 400, 300]" data-whitespace="[nowrap, nowrap, nowrap, normal]" data-frames='[{
                                        "delay":1700,
                                        "speed":1400,
                                        "frame":"0",
                                        "from":"y:150px;opacity:0;",
                                        "ease":"Power3.easeOut",
                                        "to":"o:1;"
                                        },{
                                        "delay":"wait",
                                        "speed":1000,
                                        "frame":"999",
                                        "to":"opacity:0;","ease":"Power3.easeOut"
                                    }]'
                            >
                                <p class="white-space" style="color: #eaeaea!important;">{{$slider->description}}</p>
                            </div>
                        @endif


                        <div class="tp-caption tp-resizeme"
                             @if(!($i % 2)) data-x= "30" @else data-x="['right','right','right','right']" @endif
                             data-y="center" data-voffset="[120,130,110,135]" data-lineheight="55" data-hoffset="0" data-frames='[{
                                            "delay":1900,
                                            "speed":1400,
                                            "frame":"0",
                                            "from":"y:150px;opacity:0;",
                                            "ease":"Power3.easeOut",
                                            "to":"o:1;"
                                            },{
                                            "delay":"wait",
                                            "speed":1000,
                                            "frame":"999",
                                            "to":"opacity:0;","ease":"Power3.easeOut"
                                        }]'
                        >
                            @if(empty($slider->link))
                                @if(Auth::check())
                                    <a href='/' class='butn theme'><span> {{ $slider->button ?? 'Get Started' }}</span></a>
                                @else
                                    <a href='/ssoRegister' class='butn theme'><span>{{ $slider->button ?? 'Get Started' }}</span></a>
                                @endif
                            @else
                                <a href="{{ $slider->link }}" class='butn theme'><span>{{ $slider->button ?? 'Get Started' }}</span></a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@push('script')
    <script src="{{asset('assets/js/slider.min.js')}}"></script>
    <script>
      const containerWidth = document.getElementById('home-banner-slider').offsetWidth
      const aspectRatio = 2288 / 1300
      const gridWidth  = containerWidth;
      const gridHeight  = containerWidth / aspectRatio;

      $(function () {
        let homeBannerSlider = $('#home-banner-slider')
        homeBannerSlider
          .show()
          .revolution({
            sliderType: 'standard',
            sliderLayout: 'fullwidth',
            dottedOverlay: 'none',
            delay: 5000,
            spinner: 'none',
            snow: {
              startSlide: 'first',
              endSlide: 'last',
              maxNum: '400',
              minSize: '0.2',
              maxSize: '6',
              minOpacity: '0.3',
              maxOpacity: '1',
              minSpeed: '30',
              maxSpeed: '100',
              minSinus: '1',
              maxSinus: '100'
            },
            navigation: {
              keyboardNavigation: 'off',
              keyboard_direction: 'horizontal',
              mouseScrollNavigation: 'off',
              onHoverStop: 'off',
              touch: {
                touchenabled: 'on',
                swipe_threshold: 75,
                swipe_min_touches: 1,
                swipe_direction: 'horizontal',
                drag_block_vertical: false
              },
              arrows: {
                enable: true,
                style: 'metis',
                tmp: '',
                rtl: false,
                hide_onleave: true,
                hide_onmobile: true,
                hide_under: 0,
                hide_over: 9999,
                hide_delay: 200,
                hide_delay_mobile: 1200,
                left: {
                  container: 'slider',
                  h_align: 'left',
                  v_align: 'center',
                  h_offset: 20,
                  v_offset: 0
                },
                right: {
                  container: 'slider',
                  h_align: 'right',
                  v_align: 'center',
                  h_offset: 20,
                  v_offset: 0
                }
              },
              bullets: { enable: false }
            },
            responsiveLevels: [1920, 1024, 768, 480],
            gridwidth: [1170, 1170, 767, 480],
            gridheight: [650, 650, 550, 450],
            lazyType: 'none',
            shadow: 0,
            shuffle: 'off',
            // autoHeight: 'on'
          })
      })
    </script>
@endpush
