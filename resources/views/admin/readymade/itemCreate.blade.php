@extends('layouts.master')

@section('title', 'Ready Made BIZ')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Ready Made BIZ', 'Create']" :menuLinks="[]" />
    </div>
@endsection

@section('content')

    <x-layout.tabs-wrapper>
        <x-layoutItems.normal-tab title="Module Detail" link="all" active="1"/>
        <x-layoutItems.normal-tab title="Set Items" link="item" active="0"/>
        <x-layoutItems.normal-tab title="price" link="price" active="0"/>
        <x-layoutItems.normal-tab title="meeting and attach form" link="meeting" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        <x-form.form action="{{route('admin.readymade.item.store')}}">
            <div class="row">
                <div class="col-md-6">
                    <x-form.selectpicker label="Choose Category"  name="category" class="category">
                        <option value="" disabled selected>Choose Category</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @foreach($category->approvedSubCategories as $subcat)
                                <option value="{{$subcat->id}}">{{$category->name}} --> {{$subcat->name}}</option>
                            @endforeach
                        @endforeach
                    </x-form.selectpicker>

                    <x-form.input name="name" value="" label="Name"/>

                    <x-form.textarea label="Description" name="description"></x-form.textarea>

                    <x-form.addImage></x-form.addImage>

                    <x-form.addLink></x-form.addLink>

                    <x-form.uploadVideo></x-form.uploadVideo>

                    <x-form.galleryOrder name="order" order="1"/>
                </div>
                <div class="col-md-6">

                    {{-- <x-form.thumbnail></x-form.thumbnail> --}}
                    <div class="slim slimdiv" style="width: 300px; margin: auto"
                            data-download="true"
                            data-label="Drop or choose thumbnail"
                            data-max-file-size="{{config('custom.variable.max_image_size')}}"
                            data-instant-edit="true"
                            data-button-remove-title="Upload"
                            data-ratio="3:4">
                        <input type="file" name="thumbnail" />
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <x-form.status name="featured" label="Featured?" />
                        </div>
                        <div class="col-4">
                            <x-form.status name="new" label="New?"/>
                        </div>
                        <div class="col-4">
                            <x-form.status name="status" label="Approve?" disabled="disabled"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-4">
                <x-form.a link="{{route('admin.readymade.item.index')}}" label="Back" type="info"/>
                <x-form.smtBtn type="success" label="Next" />
            </div>
        </x-form.form>
    </x-layout.portletBody>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>
        var ratio_width = "{{config("custom.variable.package_image_ratio_width")}}",
            ratio_height="{{config("custom.variable.package_image_ratio_height")}}";
    </script>
    <script src="{{asset('assets/js/account/image_crop.js')}}"></script>

    <script src="{{asset('assets/js/admin/readymade/itemCreate.js')}}"></script>
@endsection
