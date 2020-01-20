@extends('layouts.master')

@section('title', 'Email Campaign Template')
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
                    <span class="m-nav__link-text">Email Campaign</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Template</span>
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
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="javascript:void(0)"> Template Detail </a></li>
            <li class="tab-item"><a class="tab-link" data-area="#/detail" href="javascript:void(0)"> Template Body</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#/detail" href="javascript:void(0)"> Template Preview</a></li>
        </ul>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50">
        <div class="m-portlet__body">
            <form action="{{route('admin.email.template.store')}}" id="submit_form">
                @csrf
                <div class="container">
                    <div class="row mb-5">
                        <div class="col-md-6 offset-md-3">
                            <div class="form-group">
                                <label for="footer" class="form-control-label">Choose Category:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[1]}}'
                                       data-page="{{$view_name}}"
                                       data-id="1"
                                    ></i>
                                </label>
                                <select name="category" id="category" class="category selectpicker" data-live-search="true" data-width="100%">
                                    <option value="" disabled selected>Choose Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-category"></div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-control-label">Name:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[2]}}'
                                       data-page="{{$view_name}}"
                                       data-id="2"
                                    ></i>
                                </label>
                                <input type="text" class="form-control m-input--square minh-50" name="name" id="name">
                                <div class="form-control-feedback error-name"></div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-control-label">Description:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[3]}}'
                                       data-page="{{$view_name}}"
                                       data-id="3"
                                    ></i>
                                </label>
                                <textarea class="form-control m-input--square minh-100" name="description" id="description"></textarea>
                                <div class="form-control-feedback error-description"></div>
                            </div>

                            {{-- <div class="form-group">
                                <label for="thumbnail" class="form-control-label">Thumbnail
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[4]}}'
                                       data-page="{{$view_name}}"
                                       data-id="4"
                                    ></i>
                                </label>
                                <input type="file" accept="image/*" class="form-control m-input--square uploadImageBox" id="thumbnail" data-target="thumbnail_image" name="thumbnail">
                                <div class="form-control-feedback error-thumbnail"></div>
                                <img id="thumbnail_image" class="w-100"/>
                            </div> --}}
                            <div class="form-group slim slimdiv"
                                    data-download="true"
                                    data-label="Drop or choose Thumbnail"
                                    data-max-file-size="10"
                                    data-instant-edit="true"
                                    data-button-remove-title="Upload"
                                    data-ratio="1:1">
                                <input type="file" name="thumbnail" />
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label>Featured
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[5]}}'
                                           data-page="{{$view_name}}"
                                           data-id="5"
                                        ></i>
                                    </label> <br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" checked="checked" name="featured">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-4">
                                    <label>New
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[6]}}'
                                           data-page="{{$view_name}}"
                                           data-id="6"
                                        ></i>
                                    </label><br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" checked="checked" name="new">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-4">
                                    <label>Status
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[7]}}'
                                           data-page="{{$view_name}}"
                                           data-id="7"
                                        ></i>
                                    </label> <br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" disabled name="status">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 text-right">
                                <a href="{{route('admin.email.template.index')}}" class="m-1 btn m-btn-square btn-outline-info m-btn m-btn--custom">Back</a>
                                <button type="submit" class="m-1 btn m-btn-square btn-outline-success m-btn m-btn--custom smtBtn">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/email/templateCreate.js')}}"></script>
@endsection
