@extends('layouts.master')

@section('title', 'Graphic Design')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Graphic Design', 'Edit Design']"/>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.graphics.design.index')}}" class="ml-auto btn m-btn--square btn-outline-info m-btn--custom mb-2">Back</a>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__head bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Edit Design
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <form action="{{route('admin.graphics.design.update', $design->id)}}" method="POST" enctype="multipart/form-data" id="submit_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label for="preview_image" class="form-control-label">
                                    Preview Image
                                </label>
                                <input type="file" data-url="{{$design->preview}}" id="preview_image" name="preview_image">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{route('graphics.choose', $design->hash)}}" target="_blank">Preview in Editor <i class="la la-external-link"></i></a>
                            <div class="form-group">
                                <label for="category">Choose Category</label>
                                <select name="category_ids[]" id="category_ids" multiple class="select2 form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if(in_array($category->id, $category_ids)) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-category_ids"></div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">
                                    Name:
                                </label>
                                <input type="text" class="form-control m-input--square" name="name" id="name" value="{{$design->name}}">
                                <div class="form-control-feedback error-name"></div>
                            </div>

                            <div class="row" x-data="{recommend:{{$design->recommend?'true':'false'}}}">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">
                                            Active?
                                        </label>
                                        <div>
                                            <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                                <label>
                                                    <input type="checkbox" @if($design->status) checked @endif id="status" name="status">
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="premium" class="form-control-label">
                                            Premium?
                                        </label>
                                        <div>
                                            <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                                <label>
                                                    <input type="checkbox" @if($design->premium) checked @endif id="premium" name="premium" >
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="recommend" class="form-control-label">
                                            Recommended?
                                        </label>
                                        <div>
                                            <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                                <label>
                                                    <input type="checkbox" id="recommend" name="recommend" x-on:click="recommend=!recommend" @if($design->recommend) checked @endif>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12"  x-bind:class="{'d-none':!recommend}">
                                    <div class="form-group">
                                        <label for="order" class="form-control-label">
                                            Recommended Page Sort Order:
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="order" id="order" value="{{$design->order}}">
                                        <div class="form-control-feedback error-order"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                        <div class="col-6">
                            @if(count($groups) > 0)
                                <div class="tw-col-span-2">
                                    <h5><b>Pairs</b></h5>
                                    <hr class="mb-3">
                                    <div class="row">
                                        @foreach ($groups as $group)
                                            <div class="col-6">
                                                <h5>{{ $group->title }}</b></h5>
                                                <div class="form-group">
                                                    <select name="pair_ids[{{$group->id}}]" id="{{ $group->id }}_attachment_select" class="tw-mt-3 selectpicker" data-live-search="true" data-width="100%">
                                                        <option value="">Select...</option>
                                                        @foreach ($group->designs as $design)
                                                            <option value="{{$design->id}}" @if(in_array($design->id, $pairs)) selected @endif>
                                                                {{ $design->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-control-feedback error-favicon"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="w-100 text-right">
                                <br>
                                <button type="submit" class="btn m-btn--square m-btn--custom btn-outline-success smtBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
      let categories = {!! $categories !!};
      let groups = {!! $groups !!};
    </script>
    <script src="{{asset('assets/js/admin/graphic-design/itemEdit.js')}}"></script>
@endsection
