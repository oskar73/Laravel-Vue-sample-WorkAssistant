@extends('layouts.master')

@section('title', 'video')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['videos', 'Create']" :menuLinks="[]" />
    </div>
@endsection

@section('content')
    <x-layout.portlet id="all_area" active="1" label="Create video">
        <x-form.form action="{{route('admin.video.item.store')}}">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group slimdiv">
                            <label>Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <x-form.selectpicker name="category" label="Choose Category">
                            <option hidden disabled selected>Choose Category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @foreach($category->approvedSubCategories as $subcat)
                                    <option value="{{$subcat->id}}">{{$category->name}} --> {{$subcat->name}}</option>
                                @endforeach
                            @endforeach
                        </x-form.selectpicker>

                        <x-form.input name="title" label="Title" />

                        <x-form.textarea name="description" class="tinymce_area" label="Description"/>
                    </div>
                    <div class="col-lg-4" x-data="{type:'link'}">
                        <dvi class="form-group mt-2">
                            <div class="form-check-inline">
                                <label class="form-check-label" for="videoLink">
                                    <input type="radio" class="form-check-input" name="type" id="videoLink" @click="type='link'" checked >Video Link
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label" for="uploadVideo">
                                    <input type="radio" class="form-check-input"  name="type" id="uploadVideo" @click="type='upload'" >Upload Video
                                </label>
                            </div>
                        </dvi>
                        <div class="form-group mt-2" x-show="type==='link'">
                            <input name="link" class="form-control" placeholder="Video Link" />
                        </div>
                        <div class="form-group mt-2" x-show="type==='upload'">
                            <input name="video" type="file" class="form-control" />
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <x-form.input name="order" value="1" label="Sort Order in same category:" />
                            </div>
                            <div class="col-md-6">
                                <x-form.status name="status" label="Approve?" checked="checked"/>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <x-form.a link="{{route('admin.video.item.index')}}" label="Back" type="info"/>
                            <x-form.smtBtn type="success" label="Submit" />
                        </div>
                    </div>
                </div>
            </div>
        </x-form.form>
    </x-layout.portlet>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>
        var ratio_width = "{{config("custom.variable.video_image_ratio_width")}}",
            ratio_height="{{config("custom.variable.video_image_ratio_height")}}"
    </script>
    <script src="{{asset('assets/js/admin/logo/video/create.js')}}"></script>
@endsection
