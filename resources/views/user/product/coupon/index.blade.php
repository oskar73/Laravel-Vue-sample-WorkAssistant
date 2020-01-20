@extends('layouts.master')

@section('title', 'Coupon')
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
                    <span class="m-nav__link-text">Coupons</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div class="tab_btn_area text-center">
        <div class="show_checked d-none">
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn" data-action="active">Active</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn " data-action="inactive">Inactive</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn " data-action="delete">Delete</a>
        </div>
    </div>
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#active" href="#/active"> Active Coupons (<span class="active_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive"> Inactive Coupons (<span class="inactive_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#used" href="#/used"> User Coupon Usage (<span class="used_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="active_area">
        <div class="m-portlet__body">
            @include("components.user.couponTable", ['selector'=>'datatable-active'])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="inactive_area">
        <div class="m-portlet__body">
            @include("components.user.couponTable", ['selector'=>'datatable-inactive'])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="used_area">
        <div class="m-portlet__body">
            @include("components.user.couponTable", ['selector'=>'datatable-used'])
        </div>
    </div>

    <div class="modal fade" id="item_modal" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Coupon Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="item_modal_form" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="item_id" id="item_id">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Name:

                                <i class="la la-info-circle tooltip_icon"
                                   title='tool tip'
                                   data-page="data page"
                                   data-id="1"
                                ></i>

                            </label>
                            <input type="text" class="form-control m-input--square" name="name" id="name" autocomplete="off">
                            <div class="form-control-feedback error-name"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="position:relative;">
                                    <label for="code" class="form-control-label"> Code:

                                        <i class="la la-info-circle tooltip_icon"
                                           title='tool tip'
                                           data-page="data page"
                                           data-id="2"
                                        ></i>

                                    </label>
                                    <div class="input-group m-input-group">
                                        <input type="text" class="form-control m-input" name="code" id="code" autocomplete="off">
                                        <div class="input-group-append">
                                            <a href="javascript:void(0);" id="generateCode" class="generateCode btn m-btn--square  btn-outline-info">Generate</a>
                                        </div>
                                    </div>
                                    <div class="form-control-feedback error-code"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount">
                                        Discount

                                        <i class="la la-info-circle tooltip_icon"
                                           title='tool tip'
                                           data-page="data page"
                                           data-id="3"
                                        ></i>

                                    </label>
                                    <div class="input-group m-input-group">
                                        <input type="text" class="form-control m-input text-right" name="discount" placeholder="10.00" id="discount" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon1">%</span>
                                        </div>
                                    </div>
                                    <div class="form-control-feedback error-discount"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Choose Type

                                <i class="la la-info-circle tooltip_icon"
                                   title='tool tip'
                                   data-page="data page"
                                   data-id="4"
                                ></i>

                            </label>
                            <select class="form-control m-input select_picker" name="type" id="type">
                                <option value="all" selected>All Products</option>
                                <option value="category">Category</option>
                                <option value="subCategory">Sub-Category</option>
                                <option value="product">Product</option>
                            </select>
                            <div class="form-control-feedback error-type"></div>
                        </div>
                        <div class="form-group product_area d-none">
                            <label for="product">Choose

                                <i class="la la-info-circle tooltip_icon"
                                   title='tool tip'
                                   data-page="data page"
                                   data-id="5"
                                ></i>

                            </label>
                            <select class="form-control m-input select_picker" name="product" id="product">
                                <option value="all" selected>All Products</option>
                            </select>
                            <div class="form-control-feedback error-product"></div>
                            <div class="form-control-feedback error-productType"></div>
                            <input type="hidden" name="productType" id="productType">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reusable">Reusable

                                        <i class="la la-info-circle tooltip_icon"
                                           title='tool tip'
                                           data-page="data page"
                                           data-id="8"
                                        ></i>

                                    </label>
                                    <select class="form-control m-input select_picker" name="reusable" id="reusable">
                                        <option value="0" selected>Onetime</option>
                                        <option value="1">Reusable</option>
                                    </select>
                                    <div class="form-control-feedback error-reusable"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expire_date">Choose Expire Date

                                        <i class="la la-info-circle tooltip_icon"
                                           title='tool tip'
                                           data-page="data page"
                                           data-id="7"
                                        ></i>

                                    </label>
                                    <input type="text" class="form-control" id="expire_date" readonly="" name="expire_date" placeholder="Select date &amp; time">
                                    <div class="form-control-feedback error-expire_date"></div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">Status</label> <br/>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" checked="checked" name="status" id="status">
                                            <span></span>
                                        </label>
                                    </span>
                                    <div class="form-control-feedback error-status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn m-btn--square btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-btn--square btn-outline-info smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/js/user/product/coupon/index.js')}}"></script>
@endsection
