@extends('layouts.master')

@section('title', 'Portfolio')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Portfolios', 'Create']"/>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Create Portfolio</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.portfolio.item.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-7">
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
                                    @foreach($category->approvedSubCategories as $subcat)
                                        <option value="{{$subcat->id}}">{{$category->name}} --> {{$subcat->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-category"></div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="form-control-label">Title:

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[2]}}'
                                   data-page="{{$view_name}}"
                                   data-id="2"
                                ></i>
                            </label>
                            <input type="text" class="form-control" name="title" id="title" >
                            <div class="form-control-feedback error-title"></div>
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
                        <x-form.addImage ></x-form.addImage>

                        <x-form.addLink ></x-form.addLink>

                        <x-form.uploadVideo></x-form.uploadVideo>

                        <x-form.galleryOrder></x-form.galleryOrder>
                    </div>
                    <div class="col-md-5">

                        <label for="thumbnail" class="form-control-label">Upload Image</label>
                        @php
                            $ratio_width = config("custom.variable.portfolio_image_ratio_width");
                            $ratio_height=config("custom.variable.portfolio_image_ratio_height");
                        @endphp
                        <div class="slim slimdiv"
                             data-download="true"
                             data-label="Drop or choose image"
                             data-max-file-size="10"
                             data-instant-edit="true"
                             data-button-remove-title="Upload"
                             data-ratio="{{$ratio_width}}:{{$ratio_height}}">
                            <input type="file" name="image" />
                        </div>

                        <div class="row mt-4">
                            <div class="col-4">
                                <label for="featured" class="form-control-label">Featured?

                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[9]}}'
                                       data-page="{{$view_name}}"
                                       data-id="9"
                                    ></i>
                                </label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" checked name="featured" id="featured">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="new" class="form-control-label">New?

                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[10]}}'
                                       data-page="{{$view_name}}"
                                       data-id="10"
                                    ></i>
                                </label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" checked name="new" id="new">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="status" class="form-control-label">Approve?

                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[11]}}'
                                       data-page="{{$view_name}}"
                                       data-id="11"
                                    ></i>
                                </label>
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
                </div>
                <div class="text-right mt-4">
                    <a href="{{route('admin.portfolio.item.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var ratio_width = "{{config("custom.variable.portfolio_image_ratio_width")}}",
            ratio_height="{{config("custom.variable.portfolio_image_ratio_height")}}"
    </script>
    <script src="{{asset('assets/js/admin/portfolio/itemCreate.js')}}"></script>
@endsection
