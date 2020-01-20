@extends('layouts.master')

@section('title', 'Domain')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/checkout.css')}}">
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
    <div class="col-md-6 text-right">
        <a href="{{route('user.domain.search')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">New Domain</a>
    </div>
@endsection

@section('content')
        <div class="tabs-wrapper">
            <ul class="tab-nav">
                <li class="tab-item"><a class="tab-link tab-active" data-area="#purchased" href="#/purchased"> Purchased Domains (<span class="purchased_count">0</span>)</a></li>
                <li class="tab-item"><a class="tab-link" data-area="#connected" href="#/connected"> Connected Domains (<span class="connected_count">0</span>)</a></li>
            </ul>
        </div>
        <div class="m-portlet m-portlet--mobile domain_search_area">
            <div class="m-portlet__body tab_area area-active md-pt-50" id="purchased_area">
                <div class="domain_result">
                    <i class='fa fa-spinner fa-spin fa-2x fa-fw m-auto d-block'></i>
                </div>
            </div>
            <div class="m-portlet__body tab_area md-pt-50" id="connected_area">
                <div class="connected_result">

                </div>
            </div>
            <div class="m-portlet__body tab_area md-pt-50" id="renew_area">
                <div class="renew_result">

                </div>
            </div>
            <div class="m-portlet__body tab_area md-pt-50" id="confirm_area">
                <div class="renew_confirm_result">

                </div>
            </div>
        </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/user/domainList/index.js')}}"></script>
    <script src="https://js.stripe.com/v3/"></script>
@endsection
