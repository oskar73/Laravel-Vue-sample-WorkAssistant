@extends('layouts.master')

@section('title', 'Home Boxes')

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
                    <span class="m-nav__link-text">Home Boxes</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')

    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#boxes" href="#/boxes">Boxes</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#middleBox" href="#/middleBox">Middle Box</a></li>
        </ul>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area area-active" id="boxes_area" >
        <div class="m-portlet__body">
            <div class="tab_btn_area text-center" style="top: 40px">
                <div class="show_checked d-none">
                    <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn" data-action="active">Active</a>
                    <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-primary switchBtn " data-action="inactive">Inactive</a>
                    <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn " data-action="delete">Delete</a>
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile md-pt-50">
                <div class="m-portlet__body boxes">
                    <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area" id="middleBox_area">
        <div class="m-portlet__body">
            <form id="homeMiddleBoxUpdateForm" action="{{route('admin.boxes.middleBox')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="thumbnail" class="form-control-label">Middle Image
                                </label>
                                <div class="slimdiv">
                                    <input type="file" name="image" id="homeMiddleBoxInput"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="content" class="form-control-label">Link:</label>
                                <input class="form-control m-input--square" value="{{option('home.front.box.link','')}}" name="link" id="home_link" />
                                <div class="form-control-feedback error-home_link"></div>
                            </div>
                            <div class="w-100">
                                <button type="submit" class="btn m-btn--square m-btn btn-outline-success pull-right" id="submitBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Home Box</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="create_modal_form" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="box_id" id="box_id" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Name:</label>
                                    <input type="text" class="form-control m-input--square" name="name" id="name">
                                    <div class="form-control-feedback error-title"></div>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="form-control-label">Description:</label>
                                    <textarea class="form-control m-input--square" rows="3" name="description" id="description"></textarea>
                                    <div class="form-control-feedback error-description"></div>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="form-control-label">Link:</label>
                                    <input class="form-control m-input--square" name="link" id="link" />
                                    <div class="form-control-feedback error-link"></div>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Active?</label>
                                    <div class="d-flex align-items-center">
                                        <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                            <label>
                                                <input type="checkbox" checked="checked" id="status" name="status">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="thumbnail" class="form-control-label">Image
                                        <i class="la la-info-circle tooltip_icon"></i>
                                    </label>
                                    <div class="slimdiv">
                                        <input type="file" name="image" id="slimInput"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-info m-btn m-btn--square" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn m-btn--square m-btn btn-outline-success ">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.homeMiddleBoxImageUrl = '{{uploadUrl(option('home.front.box.image'))}}';
    </script>
    <script src="{{asset('assets/js/admin/content-management/boxes.js')}}"></script>
@endsection
