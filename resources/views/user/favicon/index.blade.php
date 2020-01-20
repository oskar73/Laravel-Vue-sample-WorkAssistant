@extends('layouts.master')
{{--TODO: TODO_REMOVE_BLADFILE--}}
@section('title', 'Logo Types Items')
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
                    <span class="m-nav__link-text">My Favicons</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('favicon.categories')}}" class="ml-auto btn m-btn--square btn-outline-success m-btn--custom mb-2">Get New Favicon</a>
    </div>
@endsection

@section('content')
    <x-layout.tabs-wrapper>
        <x-layoutItems.tab-item name="all" label="All Logos" active="1"/>
        <x-layoutItems.tab-item name="notdownload" label="Not Downloaded" active="0"/>
        <x-layoutItems.tab-item name="download" label="Downloaded" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        @include("components.user.faviconItem", ['selector'=>'datatable-all'])
    </x-layout.portletBody>

    <x-layout.portletBody id="notdownload_area" active="0">
        @include("components.user.faviconItem", ['selector'=>'datatable-notdownload'])
    </x-layout.portletBody>
    <x-layout.portletBody id="download_area" active="0">
        @include("components.user.faviconItem", ['selector'=>'datatable-download'])
    </x-layout.portletBody>


@endsection
@section('script')
    <script src="{{asset('assets/js/user/favicon/index.js')}}"></script>
@endsection
