@extends('layouts.master')

@section('title', 'Domain')
@section('style')

@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Domain</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">TLDs</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.domainTld.get')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info loadBtn mb-2">Update TLDs Data</a>
        <a href="{{route('admin.domainPrice.get')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info loadBtn mb-2">Update Pricing Data</a>
    </div>
@endsection

@section('content')
        <div class="tab_btn_area text-center">
            <div class="show_checked d-none">
                <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn" data-action="active">Active</a>
                <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn " data-action="inactive">Inactive</a>
                <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn " data-action="recommend">Recommend</a>
                <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn " data-action="unrecommend">Unrecommend</a>
            </div>
        </div>
        <div class="tabs-wrapper">
            <div class="clearfix"></div>
            <ul class="tab-nav">
                <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Tlds (<span class="all_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active"> Active Tlds (<span class="active_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive"> InActive Tlds (<span class="inactive_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#recommend" href="#/recommend"> Recommend Tlds (<span class="recommend_count">0</span>)</a></li>
            </ul>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
            <div class="m-portlet__body">
                @include("components.admin.domainTldTable", ['selector'=>'datatable-all'])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="active_area">
            <div class="m-portlet__body">
                @include("components.admin.domainTldTable", ['selector'=>'datatable-active'])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="inactive_area">
            <div class="m-portlet__body">
                @include("components.admin.domainTldTable", ['selector'=>'datatable-inactive'])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="recommend_area">
            <div class="m-portlet__body">
                @include("components.admin.domainTldTable", ['selector'=>'datatable-recommend'])
            </div>
        </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/domainTlds/index.js')}}"></script>
@endsection
