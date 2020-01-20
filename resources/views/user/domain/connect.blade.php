@extends('layouts.master')

@section('title', 'Connect Domain')
@section('style')

@endsection
@section('breadcrumb')
    <div class="mr-auto">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{ route('user.domain.check') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Domain</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Connect</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="domain_search_area">
        <div class="m-portlet m-portlet--mobile tab_area area-active" id="search_area">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Connect Domain
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">

                </div>
            </div>
            <div class="m-portlet__body">
                <form action="{{route('user.domain.connect')}}" method="POST" class="domain-search" id="search-form">
                    @csrf
                    @honeypot

                    <div class="container">
                        <div class="text-center mb-3">
                            <a href="{{route('user.domain.search')}}" class="btn m-btn--square  btn-outline-info m-btn m-btn--custom">Register Domain</a>
                            <a href="javascript:void(0);" class="btn m-btn--square btn-info m-btn m-btn--custom">Connect My Domain</a>
                        </div>
                        <div class="instruction" style="border:1px solid green;padding:20px 10px;font-size:14px;margin-bottom:20px;">
                            Before adding your custom domain, ensure that it is pointing to our server by following these steps:
                            <br>
                            <ul>
                                <li>
                                    On your domain registrar, create an <b>A RECORD</b>
{{--                                    (or <b>CNAME</b>)--}}
                                </li>
                                <li>Point the A record to the following IP address <b>{{optional(option('ssh'))['ip']}}</b>
{{--                                    (<b>{{optional(option('ssh'))['domain']}}</b> for CNAME)--}}
                                </li>
                            </ul>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="domain" id="domain" class="form-control domain_search_box" autocomplete="off" required>
                            <button type="submit" class="btn btn-info smtBtn"><i class="fa fa-arrow-right fa-2x"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#search-form").on("submit", function() {
            $(".smtBtn").html("<i class='fa fa-spinner fa-spin fa-2x'></i>");
        })
    </script>
@endsection
