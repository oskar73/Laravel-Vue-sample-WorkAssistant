@extends('layouts.master')

@section('title', 'FAQ')
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
                    <span class="m-nav__link-text">FAQ</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Create</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Create FAQ</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.faq.item.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="container">
                    <div class="form-group">
                        <label for="footer" class="form-control-label">Choose Category:</label>
                        <select name="category" id="category" class="category selectpicker" data-live-search="true" data-width="100%">
                            <option value="" disabled selected>Choose Category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        <div class="form-control-feedback error-category"></div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="form-control-label">Question:</label>
                        <input type="text" class="form-control" name="question" id="question" >
                        <div class="form-control-feedback error-question"></div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-control-label">Description:</label>
                        <textarea class="form-control m-input--square minh-100 tinymce_area" name="description" id="description"></textarea>
                        <div class="form-control-feedback error-description"></div>
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered table-item-center">
                            <tbody id="image_area">

                            </tbody>
                            <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addImage">+ Add Image</a>
                        </table>
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered table-item-center">
                            <tbody id="link_area">

                            </tbody>
                            <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addLink">+ Add External Video Link</a>
                        </table>
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered table-item-center">
                            <tbody id="video_area">

                            </tbody>
                            <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addVideo">+ Upload Video</a>
                        </table>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="example_input_full_name">Choose Gallery Order:</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="1" checked>
                                                <span></span>
                                            </span>
                                        </span>
                                    <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Image Gallery

                                                    <hr/>

                                                    Video Gallery
                                                </span>
                                            </span>
                                        </span>
                                </label>
                            </div>
                            <div class="col-lg-6">
                                <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="0">
                                                <span></span>
                                            </span>
                                        </span>
                                    <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                     Video Gallery

                                                    <hr/>

                                                     Image Gallery

                                                </span>
                                            </span>
                                        </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4">
                                    <label for="status" class="form-control-label">Approve?</label>
                                    <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" checked name="status" id="status">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="text-right mt-4">
                                <a href="{{route('admin.faq.item.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                                <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script src="{{asset('assets/js/admin/faq/itemCreate.js')}}"></script>
@endsection
