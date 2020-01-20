@extends('layouts.app')

@section('title', 'Package')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery.dataTables.min.css')}}">
    <style>
        .dataTables_wrapper .dataTables_paginate {
            text-align: center;
            padding-top: 0.5rem;
        }
    </style>
@endsection
@section('content')
    <x-front.hero>Package</x-front.hero>

    <div class="tw-w-full tw-mx-auto tw-flex tw-max-w-[1440px]">
        <div class="tw-w-full tw-p-3">
            <div class="items_result">
                <div class="text-center minh-100 pt-5"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
            </div>
        </div>
        <div class="tw-w-80 tw-pt-3">
            <div class="tw-w-full tw-flex tw-flex-col tw-gap-4 tw-pt-12">
                <a href="/modules" class="btn btn-outline-info">Go to App Page</a>
                <div class="tw-w-full tw-flex tw-flex-col tw-gap-2">
                    <h6 class="tw-text-center tw-text-lg tw-font-semibold">My Chosen Apps</h6>
                    <div id="selected_apps"></div>
                </div>
                <div class="tw-w-full tw-flex tw-flex-col tw-gap-2">
                    <h6 class="tw-text-center tw-text-lg tw-font-semibold">Available Apps</h6>
                    <div id="available_apps"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script>
      let selectedAppsCount = {{count(session("module_wishes", []))}}
    </script>
    <script src="{{asset('assets/js/front/package/index.js')}}"></script>
@endsection
