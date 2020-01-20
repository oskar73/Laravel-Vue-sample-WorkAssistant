@extends('layouts.master')

@section('title', 'Tutorial')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Tutorials', 'Create']" :menuLinks="[]" />
    </div>
@endsection

@section('content')
    <x-layout.portlet id="all_area" active="1" label="Create Tutorial">
        <x-layout.container>
            <x-form.form action="{{route('admin.tutorial.item.store')}}">

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

                <div class="permission_area" x-data="{public:0}">
                    <div class="form-group">
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox"
                                   name="public"
                                   id="public"
                                   x-bind:checked="public==1"
                                   x-on:click="public=public==1?0:1"
                            > Visible For all users
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group permission_area" x-show="public==0">
                        <div class="row">
                            @foreach($modules as $module)
                                <div class="col-md-3">
                                    <x-form.checkbox
                                        name="module[{{$module->id}}]"
                                        label="{{$module->name}}" purchased users
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        {{-- <x-form.thumbnail /> --}}
                        <div class="slim slimdiv"
                                data-download="true"
                                data-label="Drop or choose thumbnail"
                                data-max-file-size="{{config('custom.variable.max_image_size')}}"
                                data-instant-edit="true"
                                data-button-remove-title="Upload"
                                data-ratio="3:4">
                            <input type="file" name="thumbnail" />
                        </div>
                    </div>
                    <div class="col-md-7">

                        <x-form.addImage />

                        <x-form.addLink />

                        <x-form.uploadVideo />

                        <x-form.galleryOrder name="gallery_order"/>

                        <div class="row">
                            <div class="col-md-6">
                                <x-form.input name="order" value="1" label="Sort Order in same category:" />
                            </div>
                            <div class="col-md-6">
                                <x-form.status name="status" label="Approve?" checked="checked"/>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <x-form.a link="{{route('admin.tutorial.item.index')}}" label="Back" type="info"/>
                            <x-form.smtBtn type="success" label="Submit" />
                        </div>
                    </div>
                </div>
            </x-form.form>
        </x-layout.container>
    </x-layout.portlet>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>
        var ratio_width = "{{config("custom.variable.tutorial_image_ratio_width")}}",
            ratio_height="{{config("custom.variable.tutorial_image_ratio_height")}}"
    </script>
    <script src="{{asset('assets/js/account/image_crop.js')}}"></script>
    <script src="{{asset('assets/js/admin/tutorial/create.js')}}"></script>
@endsection
