@extends('layouts.master')

@section('title', 'Tutorials')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
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
                    <span class="m-nav__link-text">Tutorials</span>
                </a>
            </li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="sidebar-tab">
                <ul class="sidebar-tab-ul">
                    <li class="tab-item">
                        <a class="tab-link tab-active tutorial_category" data-area="#basic" href="#/basic">
                            Basic Tutorial
                        </a>
                    </li>
                    @foreach($modules as $module)
                    <li class="tab-item">
                        <a class="tab-link tutorial_category" data-area="#{{$module->slug}}" href="#/{{$module->slug}}">
                            {{$module->name}}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-10 col-md-8">
            <div class="result_area">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{asset('assets/js/user/tutorial/index.js')}}"></script>
@endsection
