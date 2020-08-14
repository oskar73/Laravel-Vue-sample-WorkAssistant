@extends('layouts.master')

@section('title', 'Template Sections')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Template', 'Sections', 'Edit']"/>
    </div>
@endsection

@section('content')
    <div class="w-100" x-data="categoryData"> 
        <div class="section_control_panel_area">
            <x-form.form action="{{route('admin.template.section.item.update', $section->id)}}">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Section Category</label>
                                    <select class="selectpicker" name="category" data-live-search="true" data-width="100%" @change="onCategoryChange">
                                        <template x-for="(category,index) of categories")>
                                            <option :value="category.id" :selected="category.id=={{$section->category->id}}" x-text="category.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Sections</label>
                                    <select class="selectpicker" data-live-search="true" data-width="100%" @change="onSectionChange">
                                        <template x-for="(section,index) of sections")>
                                            <option :value="section.id" :selected="section.id=={{$section->id}}" x-text="section.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <input class="form-control" value="{{$section->name}}" name="name" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <x-form.status name="status" label="Status" checked="{{$section->status==1? 'checked': ''}}"/>
                            <div>
                                <x-form.a link="{{route('admin.template.section.index')}}" class="m-1" label="Back"/>
                                <x-form.smtBtn label="Submit" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-form.form>
            <div class="show_more_area">
                <div class="col-12">
                    <div class="form-group" style="height: 1000px; margin-bottom: 50px">
                        <div id="jsonData" class="ace_editor h-100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.11/ace.js"></script>
    <script src="{{s3_asset('vendors/colorpicker/vanilla-color-picker.min.js')}}"></script>
    <script>
        window.sectionData = {!! json_encode($section->data) !!};
        window.categories = {!! $categories !!};
        window.sections = {!! $section->category->sections !!};
    </script>
    <script src="{{asset('assets/js/admin/template/sectionEdit.js')}}"></script>
@endsection 
                                                                                            