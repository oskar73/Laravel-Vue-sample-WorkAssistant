@extends('layouts.master')

@section('title', 'Palettes Simple Category')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Palettes','Simple','Category']" />
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info sortBtn mb-2">Sort
            Order</a>
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div class="tab_btn_area text-center">
        <div class="show_checked d-none">
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success switchBtn"
               data-action="active">Active</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-primary switchBtn "
               data-action="inactive">Inactive</a>
            <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger switchBtn "
               data-action="delete">Delete</a>
        </div>
    </div>
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> All Categories (<span
                            class="all_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active"> Active Categories (<span
                            class="active_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive"> InActive Categories (<span
                            class="inactive_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="active_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="inactive_area">
        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>

    <div class="modal fade" id="create_modal" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="create_modal_form" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="category_id" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Name</label>
                                    <input type="text" class="form-control m-input--square" name="name" id="name">
                                    <div class="form-control-feedback error-name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description:</label>
                                    <textarea class="form-control m-input--square minh-100" name="description"
                                              id="description"></textarea>
                                    <div class="form-control-feedback error-description"></div>
                                </div>
                                <x-form.switch id="status" label="Active" name="status" />
                            </div>
                            <div class="col-lg-6">
                                <div class="form-control-label mb-1">Thumbnail</div>
                                <div id="upload-image" class="btn btn-info">Upload Image</div>
                                <div class="w-100 mt-2">
                                    <img id="preview-image" class="w-100" src="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                                data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.global.sortModal')

@endsection
@section('script')
    <script>
      let getDataTableUrl = '{{route('user.palettes.categories.datatable', ['type' => 'simple'])}}'
      let sortViewUrl = '{{route('user.palettes.categories.sort.view', ['type' => 'simple'])}}'
      let sortUrl = '{{route('user.palettes.categories.sort', ['type' => 'simple'])}}'
      let storeUrl = '{{route('user.palettes.category.store', ['type' => 'simple'])}}'
      let switchUrl = '{{route('user.palettes.category.switch', ['type' => 'simple'])}}'
    </script>
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>

    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}

    @vite(['resources/js/component/image-selector.js'])

    <script src="{{asset('assets/js/palettes/palette-category.js')}}"></script>
@endsection
