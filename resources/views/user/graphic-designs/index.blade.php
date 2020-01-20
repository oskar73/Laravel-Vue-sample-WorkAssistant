@extends('layouts.master')
@section('title', 'Graphic Design Items')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">My Logos</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('graphics.index')}}" class="ml-auto btn m-btn--square btn-outline-success m-btn--custom mb-2">Get New Design</a>
    </div>
@endsection

@section('content')
    <x-layout.tabs-wrapper>
        <x-layoutItems.tab-item name="all" label="All Designs" active="1" />
        @foreach($userGraphics as $graphic)
            <x-layoutItems.tab-item :name="$graphic->slug" :label="$graphic->title" active="0" />
        @endforeach
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        @include("components.user.designItem", ['selector'=>'datatable-all'])
    </x-layout.portletBody>

    @foreach($userGraphics as $graphic)
        <x-layout.portletBody id="{{$graphic->slug}}_area" active="0">
            @include("components.user.designItem", ['selector' => 'datatable-'.$graphic->slug])
        </x-layout.portletBody>
    @endforeach
@endsection
@section('script')
    <script>
      let userGraphics = {!! $userGraphics !!};
    </script>
    <script src="{{asset('assets/js/user/graphic-designs/index.js')}}"></script>
@endsection
