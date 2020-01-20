@extends('layouts.master')

@section('title', 'Directory Listing Package')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Directory Listing', 'Package', 'Create']"/>
    </div>
@endsection

@section('content')
    <x-layout.tabs-wrapper>
        <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Package Detail</a></li>
        <li class="tab-item"><a class="tab-link" href="javascript:void(0);">Set Price</a></li>
        <li class="tab-item"><a class="tab-link" href="javascript:void(0);">Meeting and Attach Form</a></li>
    </x-layout.tabs-wrapper>
    <x-layout.portletBody id="all_area" active="1">
        <x-form.form action="{{route('admin.directory.package.store')}}">
            <div class="row">
                <div class="col-md-7">

                    <x-form.input name="name" label="Name" />

                    <x-form.textarea name="description" label="Description" />

                    <h5>Features</h5>
                    <hr>
                    <div class="row" x-data="{unlimit:false}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="listing_limit" class="form-control-label">
                                    Listing limit number:

                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="This is directory listing limit number that users can put using this package."
                                    ></i>
                                </label>
                                <input type="number" class="form-control" name="listing_limit" id="listing_limit" x-bind:disabled="unlimit">
                                <div class="form-control-feedback error-listing_limit"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <br>
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="unlimit" x-on:click="unlimit=!unlimit">
                                Set Unlimit Listing Number

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="If you check this field, it will give unlimited listing privilege to users"
                                ></i>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_thumbnail">
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
                                <input type="checkbox" name="allow_social">
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
                                <input type="checkbox" name="allow_featured">
                                Allow featured listings

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to set featured listing. Featured listings will be searched first."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_image">
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
                                <input type="checkbox" name="allow_links">
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
                                <input type="checkbox" name="allow_videos">
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
                                <input type="checkbox" name="allow_tracking">
                                Allow to track impression

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to see impression tracking data."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <hr>

                    <x-form.addImage ></x-form.addImage>
                    <x-form.addLink ></x-form.addLink>
                    <x-form.uploadVideo ></x-form.uploadVideo>
                    <x-form.galleryOrder></x-form.galleryOrder>

                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="thumbnail" class="form-control-label">Upload Image</label>
                        @php
                            $ratio_width = config("custom.variable.package_image_ratio_width");
                            $ratio_height=config("custom.variable.package_image_ratio_height");
                        @endphp
                        <div class="slim slimdiv"
                             data-download="true"
                             data-label="Drop or choose image"
                             data-max-file-size="50"
                             data-instant-edit="true"
                             data-button-remove-title="Upload"
                             data-ratio="{{$ratio_width}}:{{$ratio_height}}">
                            <input type="file" name="image" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <x-form.status label="Featured?" name="featured" />
                        </div>
                        <div class="col-4">
                            <x-form.status label="New?" name="new" />
                        </div>
                        <div class="col-4">
                            <x-form.status label="Approve?" name="status" disabled="disabled"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-4">
                <x-form.a link="{{route('admin.blog.package.index')}}" label="Back"/>
                <x-form.button type="submit" label="Next"/>
            </div>
        </x-form.form>
    </x-layout.portletBody>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/directory/packageCreate.js')}}"></script>
@endsection
