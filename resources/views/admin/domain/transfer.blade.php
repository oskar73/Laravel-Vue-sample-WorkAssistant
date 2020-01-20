@extends('layouts.master')

@section('title', 'Transfer Domain')
@section('style')

@endsection
@section('breadcrumb')
    <div class="mr-auto">
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
                    <span class="m-nav__link-text">Search</span>
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
                        Transfer Domain
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form class="domain-search" id="search-form">
                @csrf
                <div class="container">
                    <div class="input-group mb-3">
                        <input type="text" name="domain" id="domain" class="form-control domain_search_box" autocomplete="off">
                        <button type="submit" class="btn btn-primary smtBtn"><i class="fa fa-search fa-2x"></i></button>
                    </div>

                    <div class="result_area">

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{s3_asset('vendors/typed/typed.js')}}"></script>
<script src="{{asset('assets/js/admin/domain/transfer.js')}}"></script>
@endsection
