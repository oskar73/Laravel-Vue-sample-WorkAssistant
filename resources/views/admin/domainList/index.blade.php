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
                    <span class="m-nav__link-text">Lists</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="domain_search_area">
        <div class="tabs-wrapper">
            <div class="clearfix"></div>
            <ul class="tab-nav">
                <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Domains (<span class="all_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active"> Active Domains (<span class="active_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#expired" href="#/expired"> Expired Domains (<span class="expired_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#transferred" href="#/transferred"> Transfered Domains (<span class="transferred_count">0</span>)</a></li>
            </ul>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
            <div class="m-portlet__body">
                @include("components.admin.domainListTable", ['selector'=>'datatable-all', 'user'=>1])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="active_area">
            <div class="m-portlet__body">
                @include("components.admin.domainListTable", ['selector'=>'datatable-active', 'user'=>1])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="expired_area">
            <div class="m-portlet__body">
                @include("components.admin.domainListTable", ['selector'=>'datatable-expired', 'user'=>1])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="transferred_area">
            <div class="m-portlet__body">
                @include("components.admin.domainListTable", ['selector'=>'datatable-transferred', 'user'=>1])
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="renew_area">
            <div class="m-portlet__body">
                <div class="renew_result">

                </div>
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="confirm_area">
            <div class="m-portlet__body">
                <div class="renew_confirm_result">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/domainList/index.js')}}"></script>
@endsection
