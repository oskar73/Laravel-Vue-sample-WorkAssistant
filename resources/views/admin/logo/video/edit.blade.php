@extends('layouts.master')

@section('title', 'video')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['videos', 'Edit']" :menuLinks="[]" />
    </div>
@endsection

@section('content')
    <x-layout.portlet id="all_area" active="1" label="Edit video">
        <x-form.form action="{{route('admin.video.item.update', $item->id)}}">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input name="thumbnail" type="file" id="thumbnail" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <x-form.selectpicker name="category" label="Choose Category">
                            <option hidden disabled>Choose Category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @if($item->category_id==$category->id) selected @endif>{{$category->name}}</option>
                                @foreach($category->approvedSubCategories as $subcat)
                                    <option value="{{$subcat->id}}" @if($item->category_id==$subcat->id) selected @endif>{{$category->name}} --> {{$subcat->name}}</option>
                                @endforeach
                            @endforeach
                        </x-form.selectpicker>

                        <x-form.input name="title" label="Title" value="{{$item->title}}"/>

                        <x-form.textarea name="description" class="tinymce_area" label="Description">
                            {{$item->description}}
                        </x-form.textarea>
                    </div>
                    <div class="col-lg-4" x-data="{ type: @if($item->link) 'link' @else 'upload' @endif }">
                        <dvi class="form-group mt-2">
                            <div class="form-check-inline">
                                <label class="form-check-label" for="videoLink">
                                    <input type="radio" class="form-check-input" name="type" id="videoLink" @click="type='link'" :checked="type==='link'">Video Link
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label" for="uploadVideo">
                                    <input type="radio" class="form-check-input" name="type" id="uploadVideo" @click="type='upload'" :checked="type==='upload'">Upload Video
                                </label>
                            </div>
                        </dvi>

                        <div class="form-group mt-2 " x-show="type==='link'">
                            <input name="link" value="{{$item->link}}" class="form-control" placeholder="Video Link" />
                        </div>
                        <div class="form-group mt-2" x-show="type==='upload'">
                            <input name="video" value="{{$item->link}}" type="file" class="form-control" />
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <x-form.input name="order" value="{{$item->order}}" label="Sort Order in same category:" />
                            </div>
                            <div class="col-md-6">
                                <x-form.status name="status" label="Approve?" checked="{{$item->status==1? 'checked':''}}"/>
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
        window.thumbnailUrl = '{{$item->thumbnail()}}';
    </script>
    <script src="{{asset('assets/js/admin/logo/video/edit.js')}}"></script>
@endsection
