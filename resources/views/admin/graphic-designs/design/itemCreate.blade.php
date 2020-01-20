@extends('layouts.master')

@section('title', 'Graphic Design Items')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Graphic Design', 'Import New Design']" />
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('admin.graphics.design.index') }}" class="ml-auto btn m-btn--square btn-outline-info m-btn--custom mb-2">Back</a>
    </div>
@endsection

@section('content')
    <div x-cloak x-data="createDesignData" class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__head bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Import New Design
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            @if(count($categories) > 0)
                <form action="{{ route('admin.graphics.design.store') }}" method="POST" enctype="multipart/form-data" id="submit_form">
                    @csrf
                    <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                        <div class="form-group m-form__group">
                            <label for="preview_image" class="form-control-label">
                                Preview Image *
                            </label>
                            <input type="file" name="preview_image" id="preview_image" />
                        </div>
                        <div class="tw-col-span-2 ">
                            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <h5><b>1. Load SVG Logo</b></h5>
                                    <hr>
                                    <div class="form-group m-form__group tw-mt-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="svg_file" name="svg_file" accept="image/svg+xml">
                                            <label class="custom-file-label" for="svg_file">Choose file</label>
                                        </div>
                                        <div class="form-control-feedback error-svg_file"></div>
                                    </div>
                                    <h5><b>3. Font name</b></h5>
                                    <hr>
                                    <div class="form-group m-form__group">
                                        <label for="font_names" class="form-control-label mt-2">
                                            <span class="font_name_result"></span>
                                        </label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="font_names" name="font_names">
                                            <label class="custom-file-label" for="font_names">Choose file</label>
                                        </div>
                                        <div class="form-control-feedback error-font_names"></div>
                                    </div>
                                    <h5><b>4. Load fonts</b></h5>
                                    <hr>
                                    <div class="form-group m-form__group">
                                        <label for="font1" class="form-control-label mt-2">
                                            Font1 (Optional)
                                        </label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="font1" name="first_font">
                                            <label class="custom-file-label" for="font1">Choose file</label>
                                        </div>
                                        <div class="form-control-feedback error-font1"></div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="font2" class="form-control-label">
                                            Font2 (Optional)
                                        </label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="font2" name="second_font">
                                            <label class="custom-file-label" for="font2">Choose file</label>
                                        </div>
                                        <div class="form-control-feedback error-font2"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group tw-w-60">
                                        <label for="category">Choose Graphic</label>
                                        <select name="graphic_id" id="graphic_id" x-model="graphic_id" class="form-control">
                                            <template x-for="graphic in graphics">
                                                <option :value="graphic.id" x-text="graphic.title"></option>
                                            </template>
                                        </select>
                                        <div class="form-control-feedback error-category"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Choose Category</label>
                                        <select name="category_ids[]" id="category_ids" multiple class="tw-px-2 focus:tw-px-2 form-control">
                                            <template x-for="category in graphicCategories">
                                                <option :value="category.id" x-text="category.name"></option>
                                            </template>
                                        </select>
                                        <div class="form-control-feedback error-category_ids"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">
                                            Name:
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="name" id="name">
                                        <div class="form-control-feedback error-name"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="global_order" class="form-control-label">
                                            All category sort order
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="global_order"
                                               id="global_order"
                                        >
                                        <div class="form-control-feedback error-global_order"></div>
                                    </div>
                                    <div class="row" x-data="{ recommend: false }">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="status" class="form-control-label">
                                                    Active?
                                                </label>
                                                <div>
                                        <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                            <label>
                                                <input type="checkbox" checked="checked" id="status" name="status">
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
                                                <input type="checkbox" id="premium" name="premium">
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
                                                <input type="checkbox" id="recommend" name="recommend"
                                                       x-on:click="recommend=!recommend"
                                                >
                                                <span></span>
                                            </label>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3" x-bind:class="{ 'd-none': !recommend }">
                                            <div class="form-group">
                                                <label for="order" class="form-control-label">
                                                    Sort Order:
                                                </label>
                                                <input type="text" class="form-control m-input--square" name="order"
                                                       id="order"
                                                >
                                                <div class="form-control-feedback error-order"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(count($groups) > 0)
                                    <div class="tw-col-span-2">
                                        <h5><b>Pairs</b></h5>
                                        <hr class="mb-3">
                                        <div class="row">
                                            @foreach ($groups as $group)
                                                <div x-show="graphic_id != {{$group->id}}" class="col-6">
                                                    <h5>{{ $group->title }}</b></h5>
                                                    <div class="form-group">
                                                        <select name="pair_ids[{{$group->id}}]" id="{{ $group->id }}_attachment_select" class="tw-mt-3 selectpicker" data-live-search="true" data-width="100%">
                                                            <option value="">Select...</option>
                                                            @foreach ($group->designs as $design)
                                                                <option value="{{$design->id}}">
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

                                <div class="w-100 text-right tw-col-span-2 tw-mt-3">
                                    <button type="submit" class="btn m-btn--square m-btn--custom btn-outline-success smtBtn">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="tw-flex tw-items-center tw-bg-yellow-500 tw-text-white tw-font-bold tw-px-4 tw-py-3" role="alert">
                    <i class="mdi mdi-information-outline tw-text-xl tw-mr-2"></i>
                    <p>No categories, Please create category first.</p>
                </div>
                <div class="tw-mt-3">
                    <a href="{{route('admin.graphics.category.index')}}" class="btn btn-info">Create Category</a>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
      let categories = {!! $categories !!};
      let groups = {!! $groups !!};
      let graphics = {!! $graphics !!};
    </script>
    <script src="{{ asset('assets/js/admin/graphic-design/itemCreate.js') }}"></script>
@endsection
