@extends('layouts.master')

@section('title', 'Tutorial')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Tutorials', 'Edit']" :menuLinks="[]" />
    </div>
@endsection

@section('content')
    <x-layout.portlet id="all_area" active="1" label="Edit Tutorial">
        <x-layout.container>
            <x-form.form action="{{route('admin.tutorial.item.update', $item->id)}}">

                <x-form.selectpicker name="category" label="Choose Category">
                    <option hidden disabled selected>Choose Category</option>
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

                <div class="permission_area" x-data="{public:{{$item->public}}}">
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
                                        checked="{{in_array($module->id, $module_ids)? 'checked': ''}}"
                                        name="module[{{$module->id}}]"
                                        label="{{$module->name}}"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        {{-- <x-form.thumbnail>
                            {{$item->getFirstMediaUrl("thumbnail")}}
                        </x-form.thumbnail> --}}
                        <div class="form-group slimdiv">
                            <label for="thumbnail" class="form-control-label">Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" />
                        </div>
                    </div>
                    <div class="col-md-7">

                        <x-form.addImage >
                            @foreach($item->getMedia('image') as $key=>$image)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                                        <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                    </td>
                                    <td><img class='width-150' src="{{$image->getUrl()}}"/></td>
                                    <td><button type=button class='btn btn-danger btn-sm delBtn'>X</button></td>
                                </tr>
                            @endforeach
                        </x-form.addImage>

                        <x-form.addLink>
                            @foreach($item->getLinks() as $key1=>$link)
                                <tr>
                                    <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}"></td>
                                    <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                                </tr>
                            @endforeach
                        </x-form.addLink>
                        <x-form.uploadVideo>
                            @foreach($item->getMedia('video') as $key2=>$video)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control m-input--square" value="{{$video->getUrl()}}" readonly>
                                        <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                                    </td>
                                    <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                                </tr>
                            @endforeach
                        </x-form.uploadVideo>

                        <x-form.galleryOrder name="gallery_order" order="{{$item->gallery_order}}"/>

                        <div class="row">
                            <div class="col-md-6">
                                <x-form.input name="order" value="{{$item->order}}" label="Sort Order in same category:" />
                            </div>
                            <div class="col-md-6">
                                <x-form.status name="status" label="Approve?" checked="{{$item->status==1? 'checked':''}}"/>
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
            ratio_height="{{config("custom.variable.tutorial_image_ratio_height")}}",
            category = "{{$item->category_id}}",
            item_id = "{{$item->id}}",
            maxImageSize = "{{config('custom.variable.max_image_size')}}";

        @if($item->getFirstMediaUrl("thumbnail"))
            window.thumbNailUrl = '{{$item->getFirstMediaUrl("thumbnail")}}';
        @endif
    </script>
    <script src="{{asset('assets/js/account/image_crop.js')}}"></script>
    <script src="{{asset('assets/js/admin/tutorial/edit.js')}}"></script>
@endsection
