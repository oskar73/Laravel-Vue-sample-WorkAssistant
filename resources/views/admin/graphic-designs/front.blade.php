@extends('layouts.master')

@section('title', 'Directory Front Setting')

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
                    <span class="m-nav__link-text">Graphic Design</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Front Setting</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">{{ $graphic->title }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div class="m-portlet__body">
            <div class="row container my-2">
                <div class="col-4">
                    <select name="country" id="graphic_id" class="form-control" data-width="100%"
                        data-live-search="true">
                        @foreach ($graphics as $item)
                            <option value="{{ $item->slug }}" @if ($item->slug == $graphic->slug) selected @endif>
                                {{ $item->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <form method="post" action="{{ route('admin.graphics.front.store', $graphic->slug) }}" id="submit_form"
                enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label for="nav_image" class="form-control-label text-bold h4">
                                    {{ $graphic->title }} header background image
                                    <i class="la la-info-circle tipso2" data-tipso-title="What is this?"
                                        data-tipso="This is Blog Navigation Area Background Image"></i>
                                </label>
                                <div class="slimdiv">
                                    <input type="file" data-url="{{ $frontSetting['header_image'] ?? '' }}"
                                        name="headerImage" id="slimInput1" />
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="nav_image" class="form-control-label text-bold h4">
                                    {{ $graphic->title }} box image
                                    <i class="la la-info-circle tipso2" data-tipso-title="What is this?"
                                        data-tipso="This is Blog Navigation Area Background Image"></i>
                                </label>
                                <div class="slimdiv">
                                    <input type="file" data-url="{{ $frontSetting['box_image'] ?? '' }}" name="boxImage"
                                        id="slimInput2" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="my-4 text-bold">{{ $graphic->title }} SEO Setting</h4>
                            <div class="form-group">
                                <label for="meta_title">
                                    Meta Title
                                    <i class="la la-info-circle tipso2" data-tipso-title="What is this?"
                                        data-tipso="Meta Title"></i>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control m-input m-input--square" name="title"
                                        id="meta_title" value="{{ $frontSetting['seo']['title'] ?? '' }}">
                                </div>
                                <div class="form-control-feedback error-meta_title"></div>
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords">
                                    Meta Keywords
                                    <i class="la la-info-circle tipso2" data-tipso-title="What is this?"
                                        data-tipso="Meta Keywords"></i>
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control m-input m-input--square" name="keywords"
                                        id="meta_keywords" value="{{ $frontSetting['seo']['keywords'] ?? '' }}">
                                </div>
                                <div class="form-control-feedback error-meta_keywords"></div>
                            </div>
                            <div class="form-group">
                                <label for="meta_description">
                                    Meta Description
                                    <i class="la la-info-circle tipso2" data-tipso-title="What is this?"
                                        data-tipso="Meta Description"></i>
                                </label>
                                <div class="position-relative">
                                    <textarea class="form-control m-input m-input--square minh-100px" rows="5" name="description"
                                        id="meta_description">{{ $frontSetting['seo']['description'] ?? '' }}</textarea>
                                </div>
                                <div class="form-control-feedback error-meta_description"></div>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="meta_image" class="form-control-label">
                                    Meta Image
                                </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input uploadImageBox" id="image" name="image" data-target="preview" accept="image/*">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                                <img src="{{ $frontSetting['seo']['image'] ?? ''}}" id="preview"
                                    class="w-100 mt-3 img bordered">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="ml-auto btn m-btn--square m-btn--custom btn-outline-success mb-2 smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/admin/graphic-design/front.js') }}"></script>
@endsection
