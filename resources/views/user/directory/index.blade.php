@extends('layouts.master')

@section('title', 'Directory Listings')
@section('style')

@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Directory Listing']"/>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.directory.select')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Add New Listing</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Listings (<span class="all_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            @include("components.user.directoryTable", ['selector'=>'datatable-all'])
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/user/directory/index.js')}}"></script>
@endsection
