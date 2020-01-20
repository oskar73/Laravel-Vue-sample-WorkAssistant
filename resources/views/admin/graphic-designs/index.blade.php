@extends('layouts.master')

@section('title', 'Graphic Design Category')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Graphics']"/>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--custom m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__head bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Graphic Design Categories (<span class="all_count">0</span>)
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools"></div>
        </div>
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>
    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Graphic Design Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="create_modal_form" method="post" enctype="multipart/form-data" action="{{route('admin.graphics.store')}}">
                    <div class="modal-body">
                        @csrf
                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <input hidden id="graphic_id" name="graphic_id"/>
                            <div>
                                <div class="form-group">
                                    <label for="title" class="form-control-label">
                                        Title:
                                    </label>
                                    <input type="text" class="form-control m-input--square" name="title" id="name">
                                    <div class="form-control-feedback error-name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="title" class="form-control-label">
                                        Width:
                                    </label>
                                    <input type="text" class="form-control m-input--square" name="width" id="width">
                                    <div class="form-control-feedback error-name"></div>
                                </div>

                                <div class="form-group">
                                    <label for="title" class="form-control-label">
                                        Height:
                                    </label>
                                    <input type="text" class="form-control m-input--square" name="height" id="height">
                                    <div class="form-control-feedback error-name"></div>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="title" class="form-control-label">
                                        Thumbnail:
                                    </label>
                                    <input type="file" name="thumbnail" class="slimInput" />
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
@endsection
@section('script')
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/graphic-design/graphics.js')}}"></script>
@endsection
