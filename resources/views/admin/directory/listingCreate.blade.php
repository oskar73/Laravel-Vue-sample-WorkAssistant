@extends('layouts.master')

@section('title', 'Directory Listing')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Directory Listing', 'Create Listing']"/>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Create Listing</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.directory.listing.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row" x-data="{thumbnail:true,featured:true,links:true,tracking:true,social:true,image:true,videos:true}">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="footer" class="form-control-label">Choose Category:</label>
                            <select name="category" id="category" class="category" data-live-search="true" data-width="100%">
                                <option value="" disabled selected>Choose Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" data-tags="{{$category->approvedTags->pluck('id')}}">{{$category->name}}</option>
                                    @foreach($category->approvedSubCategories as $subcat)
                                        <option value="{{$subcat->id}}" data-tags="{{$category->approvedTags->pluck('id')}}">{{$category->name}} &rarr; {{$subcat->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-category"></div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="tag">Select Tags</label><br>
                            <select class="form-control" id="tag" name="tags[]" multiple>
                                <option></option>
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-tags"></div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="form-control-label">Title:</label>
                            <input type="text" class="form-control" name="title" id="title" >
                            <div class="form-control-feedback error-title"></div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="form-control-label">Website Link URL:</label>
                            <input type="text" class="form-control" name="url" id="url" >
                            <div class="form-control-feedback error-url"></div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description:</label>
                            <textarea class="form-control m-input--square minh-100" name="description" id="description"></textarea>
                            <div class="form-control-feedback error-description"></div>
                        </div>
                        <div>
                            <label for="property" class="form-control-label">Choose Property:</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="m-checkbox m-checkbox--state-success">
                                        <input type="checkbox" name="allow_thumbnail" x-bind:checked="thumbnail" x-on:click="thumbnail=!thumbnail" >
                                        Allow to put thumbnail

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This will allow for users to put their image"
                                        ></i>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-checkbox m-checkbox--state-success">
                                        <input type="checkbox" name="allow_social" x-bind:checked="social" x-on:click="social=!social">
                                        Allow to put social media share links

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This will allow for users to put their social media link"
                                        ></i>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-checkbox m-checkbox--state-success">
                                        <input type="checkbox" name="allow_image" x-bind:checked="image" x-on:click="image=!image">
                                        Allow to put image gallery

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This will allow for users to put image gallery in listing detail."
                                        ></i>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-checkbox m-checkbox--state-success">
                                        <input type="checkbox" name="allow_links" x-bind:checked="links" x-on:click="links=!links">
                                        Allow to put external video links

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This will allow for users to put external video links."
                                        ></i>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-checkbox m-checkbox--state-success">
                                        <input type="checkbox" name="allow_videos"  x-bind:checked="videos"  x-on:click="videos=!videos">
                                        Allow to upload custom videos

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This will allow for users to upload custom videos."
                                        ></i>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-checkbox m-checkbox--state-success">
                                        <input type="checkbox" name="allow_tracking" x-bind:checked="tracking" x-on:click="tracking=!tracking">
                                        Allow to track impression

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This will allow for users to see impression tracking data."
                                        ></i>
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label for="featured" class="form-control-label">Featured?</label>
                                    <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox"  name="featured" id="featured" x-bind:checked="featured" x-on:click="featured=!featured">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="new" class="form-control-label">New?</label>
                                    <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox"  name="new" id="new">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group" x-show="thumbnail">
                            <label for="thumbnail" class="form-control-label">Upload Image</label>
                            <div class="slim slimdiv"
                                 data-download="true"
                                 data-label="Drop or choose image"
                                 data-max-file-size="50"
                                 data-instant-edit="true"
                                 data-button-remove-title="Upload"
                                 data-ratio="1:1">
                                <input type="file" name="thumbnail" />
                            </div>
                        </div>
                        <div class="form-group" x-show="image">
                            <table class="table table-bordered table-item-center">
                                <tbody id="image_area">

                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addImage">+ Add Image</a>
                            </table>
                        </div>
                        <div class="form-group" x-show="links">
                            <table class="table table-bordered table-item-center">
                                <tbody id="link_area">

                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addLink">+ Add External Video Link</a>
                            </table>
                        </div>
                        <div class="form-group" x-show="videos">
                            <table class="table table-bordered table-item-center">
                                <tbody id="video_area">

                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addVideo">+ Upload Video</a>
                            </table>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
                            <label for="status" class="form-control-label">Status</label>
                            <select name="status" id="status" class="selectpicker" data-width="100%">
                                <option value="approved">Approved</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                            <div class="form-control-feedback error-status"></div>
                        </div>
                        <div class="row" x-data="{expire_date:false}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expire_date" class="form-control-label">
                                        Expire Date:

                                        <i class="la la-info-circle tipso2"
                                           data-tipso-title="What is this?"
                                           data-tipso="This is the date that this listing will be expired."
                                        ></i>
                                    </label>
                                    <input type="text" class="form-control" name="expire_date" id="expire_date" x-bind:disabled="expire_date">
                                    <div class="form-control-feedback error-expire_date"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <br>
                                <label class="m-checkbox m-checkbox--state-success">
                                    <input type="checkbox" name="unlimit" x-on:click="expire_date=!expire_date">
                                    Never Expire

                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="If you check this field, it will never expire."
                                    ></i>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-3 form-group">
                            <label for="customer" class="form-control-label">Choose Customer</label>
                            <select name="customer" id="customer" class="select2 m-select2">
                                <option value="{{user()->id}}" selected>{{user()->name}} ({{user()->email}})</option>
                            </select>
                            <div class="form-control-feedback error-customer"></div>
                        </div>

                    </div>
                </div>
                <div class="text-right mt-4">
                    <a href="{{route('admin.directory.listing.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/directory/listingCreate.js')}}"></script>
@endsection
