@extends('layouts.master')

@section('title', 'Blog Front Setting')
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
                    <span class="m-nav__link-text">Portfolio</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Front Setting</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Blog Front Setting
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <form method="post" action="{{route('admin.portfolio.front.store')}}" id="submit_form" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group" >
                                <label for="nav_image" class="form-control-label text-bold h4">
                                    Portfolio header background image
                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="This is Blog Navigation Area Background Image"
                                    ></i>
                                </label>
                                <div class="slimdiv">
                                    <input type="file" name="headerImage" data-url="{{uploadUrl(option('portfolio.front.header.image'))}}" id="slimInput1"/>
                                </div>
                            </div>

                            <div class="form-group m-form__group" >
                                <label for="nav_image" class="form-control-label text-bold h4">
                                    Portfolio box image
                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="This is Blog Navigation Area Background Image"
                                    ></i>
                                </label>
                                <div class="slimdiv">
                                    <input type="file" name="boxImage"  data-url="{{uploadUrl(option('portfolio.front.box.image'))}}"  id="slimInput2"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="text-bold">Portfolio SEO Setting</h4>
                            <div class="form-group">
                                <label for="meta_title">
                                    Meta Title
                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="Meta Title"
                                    ></i>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control m-input m-input--square" name="title" id="meta_title" value="{{$seo['title']??''}}">
                                </div>
                                <div class="form-control-feedback error-meta_title"></div>
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords">
                                    Meta Keywords
                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="Meta Keywords"
                                    ></i>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control m-input m-input--square" name="keywords" id="meta_keywords" value="{{$seo['keywords']??''}}">
                                </div>
                                <div class="form-control-feedback error-meta_keywords"></div>
                            </div>
                            <div class="form-group">
                                <label for="meta_description">
                                    Meta Description
                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="Meta Description"
                                    ></i>
                                </label>
                                <div class="position-relative">
                                    <textarea class="form-control m-input m-input--square minh-100px" rows="5" name="description" id="meta_description">{{$seo['description']??''}}</textarea>
                                </div>
                                <div class="form-control-feedback error-meta_description"></div>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="meta_image" class="form-control-label">
                                    Meta Image

                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="This is seo meta image. Size recommend: 1200x627px, max upload size: 5 MB."
                                    ></i>
                                    <a href="https://www.iloveimg.com/crop-image" class="underline" target="_blank">Resize</a>
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input uploadImageBox" id="image" name="image" data-target="preview" accept="image/*">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                <img src="{{uploadUrl($seo['image']??'')}}" id="preview" class="w-100 mt-3 img bordered">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="ml-auto btn m-btn--square btn-outline-success mb-2 smtBtn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/portfolio/front.js')}}"></script>
@endsection
