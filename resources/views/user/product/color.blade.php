@extends('layouts.master')

@section('title', 'Product Colors')
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
                <a href="{{ route('user.product.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Product</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Colors</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0)" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2 newColor">New Color</a>
    </div>
@endsection

@section('content')
    <div class="tab_btn_area text-center">
        <div class="show_checked d-none">
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn " data-action="delete">Delete</a>
        </div>
    </div>
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active"  data-area="#all" href="#/all"> Colors </a></li>
        </ul>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area area-active" id="all_area">
        <div class="m-portlet__body">
            <div class="table-responsive">
                <table class="table table-hover ajaxTable datatable datatable-all">
                    <thead>
                    <tr>
                        <th>
                            <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="datatable-all">
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Color Code
                        </th>
                        <th>
                            Slug
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="loading-tbody"><tr><td colspan="6" style="height:200px;"></td></tr></tbody>
                </table>
            </div>

        </div>
    </div>
    @include("components.user.addColorModal")
@endsection
@section('script')
    <script src="{{asset('assets\js\user\product\color.js')}}"></script>
@endsection
