@extends('layouts.master')

@section('title', 'Featured Sliders')
@section('style')

@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Featured Sliders</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info sortBtn mb-2">Sort Order</a>
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Items (<span class="all_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>

    <div class="modal fade" id="item_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Slider Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="item_modal_form" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="item_id" id="item_id"/>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="thumbnail" class="form-control-label">
                                            Thumbnail
                                            <i class="la la-info-circle tooltip_1"
                                               title="This is slider cover image.">
                                            </i>
                                        </label>
                                        <div class="slimdiv">
                                            <input type="file" name="thumbnail" id="slimInput"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group mb-5">
                                        <label for="name" class="form-control-label">
                                            Name
                                            <i class="la la-info-circle tooltip_1"
                                               title="This is name of featured slider.">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="name" id="name">
                                        <div class="form-control-feedback error-name"></div>
                                    </div>
                                    <div class="form-group mb-5">
                                        <label for="type">Choose Product Type
                                            <i class="la la-info-circle tooltip_1"
                                               title="This is product type.">
                                            </i>
                                        </label>
                                        <select class="form-control m-input select_picker" name="type" id="type">
                                            <option selected disabled hidden>Select Product Type</option>
                                            <option value="url">URL</option>
                                            <option value="package">Package</option>
                                            <option value="readymade">Ready Made BIZ</option>
                                            <option value="module">Module</option>
                                            <option value="plugin">Plugin</option>
                                            <option value="service">Service</option>
                                            <option value="lacarte">A La Carte</option>
                                            <option value="blogPackage">Blog Package</option>
                                            <option value="blogAds">Blog Advertisement</option>
                                            <option value="portfolio">Portfolio</option>
                                        </select>
                                        <div class="form-control-feedback error-type"></div>
                                    </div>
                                    <div class="form-group product_area d-none mb-5">
                                        <label for="product">Choose Product Item
                                            <i class="la la-info-circle tooltip_1"
                                               title="This is product">
                                            </i>
                                        </label>
                                        <select class="form-control m-input select_picker" name="product" id="product">
                                            <option disabled hidden selected>Choose Item</option>
                                        </select>
                                        <div class="form-control-feedback error-product"></div>
                                    </div>
                                    <div class="form-group url_area mb-5 d-none">
                                        <label for="url" class="form-control-label">
                                            URL
                                            <i class="la la-info-circle tooltip_1"
                                               title="This is url of this slider. Nullable">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="url" id="url">
                                        <div class="form-control-feedback error-url"></div>
                                    </div>
                                    <div class="form-group  mb-5">
                                        <label for="featured_name" class="form-control-label">
                                            Featured Name
                                            <i class="la la-info-circle tooltip_1"
                                               title="This is featured name of this slider. Nullable">
                                            </i>
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="featured_name" id="featured_name">
                                        <div class="form-control-feedback error-featured_name"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.global.sortModal')

@endsection
@section('script')
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/slider/index.js')}}"></script>
@endsection
