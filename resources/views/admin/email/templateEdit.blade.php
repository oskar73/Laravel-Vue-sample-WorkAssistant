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
                    <span class="m-nav__link-text">Edit</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.email.template.index')}}" class="m-1 btn m-btn-square btn-outline-info m-btn m-btn--custom">Back</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="#/detail"> Template Detail </a></li>
            <li class="tab-item"><a class="tab-link" data-area="#body" href="#/body"> Template Body</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#preview" href="#/preview"> Template Preview</a></li>
        </ul>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.email.template.store')}}" id="submit_form">
                @csrf
                <input type="hidden" name="template_id" value="{{$template->id}}">
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
                                    <option value="" disabled selected>Choose Category
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[2]}}'
                                           data-page="{{$view_name}}"
                                           data-id="2"
                                        ></i>
                                    </option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if($template->category_id==$category->id) selected @endif>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-category"></div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-control-label">Name:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[3]}}'
                                       data-page="{{$view_name}}"
                                       data-id="3"
                                    ></i>
                                </label>
                                <input type="text" class="form-control m-input--square minh-50" name="name" id="name" value="{{$template->name}}">
                                <div class="form-control-feedback error-name"></div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-control-label">Description:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[4]}}'
                                       data-page="{{$view_name}}"
                                       data-id="4"
                                    ></i>
                                </label>
                                <textarea class="form-control m-input--square minh-100" name="description" id="description">{{$template->description}}</textarea>
                                <div class="form-control-feedback error-description"></div>
                            </div>

                            {{-- <div class="form-group">
                                <label for="thumbnail" class="form-control-label">Thumbnail
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[5]}}'
                                       data-page="{{$view_name}}"
                                       data-id="5"
                                    ></i>
                                </label>
                                <input type="file" accept="image/*" class="form-control m-input--square uploadImageBox" id="thumbnail" data-target="thumbnail_image" name="thumbnail">
                                <div class="form-control-feedback error-thumbnail"></div>
                                <img id="thumbnail_image" class="w-100" src="{{$template->getFirstMediaUrl("thumbnail")}}"/>
                            </div> --}}
                            <div class="form-group slimdiv">
                                <label for="thumbnail" class="form-control-label">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail" />
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label>Featured
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[6]}}'
                                           data-page="{{$view_name}}"
                                           data-id="6"
                                        ></i>
                                    </label> <br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" @if($template->featured) checked @endif name="featured">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-4">
                                    <label>New
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[7]}}'
                                           data-page="{{$view_name}}"
                                           data-id="7"
                                        ></i>
                                    </label><br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" @if($template->new) checked @endif name="new">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-4">
                                    <label>Status
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[8]}}'
                                           data-page="{{$view_name}}"
                                           data-id="8"
                                        ></i>
                                    </label> <br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" @if($template->status) checked @endif name="status">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 text-right">
                                <button type="submit" class="m-1 btn m-btn-square btn-outline-success m-btn m-btn--custom smtBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="body_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.email.template.updateBody', $template->id)}}" id="body_form">
                @csrf
                @if($template->body==null)
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="form-group">
                                <label for="body" class="form-control-label">Pick main html file
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[9]}}'
                                       data-page="{{$view_name}}"
                                       data-id="9"
                                    ></i>

                                </label> <br>
                                <input type="file" id="fileInput" accept=".html, .htm">
                                <input type="hidden" id="fileOutput" name="body">
                            </div>
                            <div class="form-group">
                                <label for="uploadFile_create">
                                    Upload All Images:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[10]}}'
                                       data-page="{{$view_name}}"
                                       data-id="10"
                                    ></i>

                                </label>

                                <div class="needsclick dropzone" id="mydropzone" style="text-align:center;">

                                </div>
                                <input type="hidden" id="fileNames" name="fileNames">
                            </div>
                        </div>
                    </div>
                @else
                    <div id="tem_body">{!! $template->body !!}</div>
                @endif
                <div class="mt-3 text-right">
                    <button type="submit" class="m-1 btn m-btn-square btn-outline-success m-btn m-btn--custom smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="preview_area">
        <div class="m-portlet__body">
            <div>{!! $template->body !!}</div>
        </div>
    </div>
@endsection
@section('script')
    <script>var edit_id = "{{$template->id}}", body = "{{$template->body==null?0:1}}"</script>
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script>
        @if($template->getFirstMediaUrl("thumbnail"))
            window.thumbNailUrl = '{{$template->getFirstMediaUrl("thumbnail")}}';
        @endif
    </script>
    @if($template->body==null)
        <script src="{{s3_asset('vendors/inliner/inliner.js')}}"></script>
        <script src="{{asset('assets/js/admin/email/dropzone.js')}}"></script>
    @else
    @endif
    <script src="{{asset('assets/js/admin/email/templateEdit.js')}}"></script>
@endsection
