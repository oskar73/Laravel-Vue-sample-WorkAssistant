{{--    {{$socials}}--}}
<div class="d-flex tw-gap-2">
    @if($socials->facebook)
        <a href="{{ $socials->facebook }}" class="d-block" target="_blank"><img
                    src="{{asset('assets/img/social/facebook.png')}}" alt="" class="tw-w-7"></a>
    @endif
    @if($socials->twitter)
        <a href="{{ $socials->twitter }}" class="d-block" target="_blank"><img
                    src="{{asset('assets/img/social/twitter.png')}}" alt="" class="tw-w-7"></a>
    @endif
    @if($socials->linkedin)
        <a href="{{ $socials->linkedin }}" class="d-block" target="_blank"><img
                    src="{{asset('assets/img/social/linkedin.png')}}" alt="" class="tw-w-7"></a>
    @endif
    @if($socials->instagram)
        <a href="{{ $socials->instagram }}" class="d-block" target="_blank"><img
                    src="{{asset('assets/img/social/instagram.png')}}" alt="" class="tw-w-7"></a>
    @endif
    @if($socials->youtube)
        <a href="{{ $socials->youtube }}" class="d-block" target="_blank"><img
                    src="{{asset('assets/img/social/youtube.png')}}" alt="" class="tw-w-7"></a>
    @endif
    @if($socials->vk)
        <a href="{{ $socials->vk }}" class="d-block" target="_blank"><img
                    src="{{asset('assets/img/social/facebook.png')}}" alt="" class="tw-w-7"></a>
    @endif
</div>
