@extends('layouts.master')

@section('title', 'Purchase Management - Subscriptions')
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
                    <span class="m-nav__link-text">Purchase Management</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Subscriptions</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Subscriptions (<span class="all_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active"> Active Subscriptions (<span class="active_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive"> InActive Subscriptions (<span class="inactive_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            @include('components.user.subscriptionTable', ['selector'=>'datatable-all'])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="active_area">
        <div class="m-portlet__body">
            @include('components.user.subscriptionTable', ['selector'=>'datatable-active'])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="inactive_area">
        <div class="m-portlet__body">
            @include('components.user.subscriptionTable', ['selector'=>'datatable-inactive'])
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/js/user/purchase/subscription.js')}}"></script>
@endsection
