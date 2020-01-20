@extends('layouts.master')

@section('title', 'Setting - SEO')
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
                    <span class="m-nav__link-text">Setting</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">SEO</span>
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
                        SEO Setting
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <form action="{{route('admin.setting.seo.store')}}" id="submit_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="title">
                                    Meta Title
                                    <i class="fa fa-info-circle tooltip_3" title="title"></i>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control m-input m-input--square" name="title" id="title" value="{{optional($seo)['title']}}">
                                </div>
                                <div class="form-control-feedback error-title"></div>
                            </div>
                            <div class="form-group">
                                <label for="keywords">
                                    Meta Keywords
                                    <i class="fa fa-info-circle tooltip_3" title="keywords"></i>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control m-input m-input--square" name="keywords" id="keywords" value="{{optional($seo)['keywords']}}">
                                </div>
                                <div class="form-control-feedback error-keywords"></div>
                            </div>
                            <div class="form-group">
                                <label for="description">
                                    Meta Description
                                    <i class="fa fa-info-circle tooltip_3" title="description"></i>
                                </label>
                                <div class="position-relative">
                                    <textarea class="form-control m-input m-input--square minh-100px" name="description" id="description">{{optional($seo)['description']}}</textarea>
                                </div>
                                <div class="form-control-feedback error-description"></div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group m-form__group">
                                <label for="image" class="form-control-label">
                                    Meta Image
                                    <small>This is seo meta image. Size recommend: 1200x627px, max upload size: 5 MB.</small>
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input uploadImageBox" id="image" name="image" accept="image/*" data-target="preview">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                <img src="{{optional($seo)['image']}}" id="preview" class="w-100 mt-3">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="head_code">
                            Additional Head Code
                            <i class="fa fa-info-circle tooltip_3" title="head_code"></i>
                        </label>
                        <div class="position-relative">
                            <textarea class="form-control m-input m-input--square minh-100px" name="head_code" id="head_code">{{optional($seo)['head_code']}}</textarea>
                        </div>
                        <div class="form-control-feedback error-head_code"></div>
                    </div>

                    <div class="form-group">
                        <label for="bottom_code">
                            Additional Bottom Code
                            <i class="fa fa-info-circle tooltip_3" title="bottom_code"></i>
                        </label>
                        <div class="position-relative">
                            <textarea class="form-control m-input m-input--square minh-100px" name="bottom_code" id="bottom_code">{{optional($seo)['bottom_code']}}</textarea>
                        </div>
                        <div class="form-control-feedback error-bottom_code"></div>
                    </div>


                    <div class="text-right">
                        <button class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2 smtBtn">Submit</button>
                    </div>
                </form>
                <div class="form-group">
                    <label for="description"> Sitemap </label>
                    <br>
                    <a href="{{route('admin.setting.seo.generateSitemap')}}" class="btn m-btn--square  btn-outline-primary">Generate Now <i class="fa flaticon-settings"></i></a>

                    @if(optional(option("google_services", []))['sitemap_updated'])
                        <a href="{{route('admin.setting.seo.downloadSitemap')}}" class="ml-3 ">
                            <span class="underline " title="Download Sitemap">Download</span>
                            <i class="fa fa-download"></i>
                        </a>
                        <span title="Last Updated Date">({{option("google_services")['sitemap_updated']}})</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/setting/seo.js')}}"></script>
@endsection
