@extends('layouts.master')

@section('title', 'Directory Listing Package')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Directory Listing', 'Package']"/>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info sortBtn mb-2">Sort Order</a>
        <a href="{{route('admin.directory.package.create')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div class="tab_btn_area text-center">
        <div class="show_checked d-none">
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn" data-action="active">Active</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-primary switchBtn " data-action="inactive">Inactive</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn" data-action="featured">Featured</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-primary switchBtn " data-action="unfeatured">Unfeatured</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn " data-action="new">New</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-primary switchBtn " data-action="undonew">Undo New</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn " data-action="delete">Delete</a>
        </div>
    </div>
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Packages (<span class="all_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active"> Active Packages (<span class="active_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive"> InActive Packages (<span class="inactive_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="active_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="inactive_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>

    @include('components.global.sortModal')

@endsection
@section('script')
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/directory/package.js')}}"></script>
@endsection
