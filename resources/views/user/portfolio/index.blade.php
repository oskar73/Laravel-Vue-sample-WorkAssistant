@extends('layouts.master')

@section('title', 'Portfolio')
@section('style')

@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Portfolio']"/>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.portfolio.create')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Create New Portfolio</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Portfolio (<span class="all_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            @include("components.user.portfolioTable", ['selector'=>'datatable-all'])
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/user/portfolio/index.js')}}"></script>
@endsection
