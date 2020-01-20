@extends('layouts.master')

@section('title', 'File Storage')
@section('style')
<style>
</style>
@endsection
@section('breadcrumb')
    <div class="mr-auto">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">File</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Main Storage</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="file_tab tab-link tab-active" data-area="#main" href="#/main"> Bizinabox Main Storage</a></li>
            <li class="tab-item"><a class="file_tab tab-link" data-area="#users" href="#/users"> Users Storage</a></li>
        </ul>
    </div>
    <div class="m-portlet__body p-0 tab_area area-active" id="main_area">
    </div>
    <div class="m-portlet__body p-0 tab_area" id="users_area">
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/file/storage.js')}}"></script>
@endsection
