@extends('layouts.master')

@section('title', 'Template Sections')
@section('style')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/medium-editor.min.css" type="text/css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/themes/tim.min.css" type="text/css" media="screen" charset="utf-8">
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Template', 'Sections', 'Create']"/>
    </div>
@endsection

@section('content')
    <x-form.form action="{{route('admin.template.section.item.store', $category->id)}}">
        <div class="w-100"  x-data="initialData" x-init="$watch('property', value => console.log(value))">
            <div class="section_control_panel_area">
                <div class="row">
                    <div class="col-md-7">
                        <x-form.input name="name" label="Section Name"/>
                        <div class="d-flex align-items-center justify-content-between">
                            <x-form.status name="status" label="Status"/>
                            <div>
                                <x-form.a link="{{route('admin.template.section.index')}}" class="m-1" label="Back"/>
                                <x-form.smtBtn label="Submit" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="show_more_area">
                    <div class="col-12">
                        <div class="form-group" style="height: 1000px; margin-bottom: 50px">
                            <label for="jsonData">Data:</label>
                            <div id="jsonData" class="ace_editor h-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form.form>
@endsection
@section('script')
    <script src="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/js/medium-editor.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.11/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/json-editor/2.5.3/jsoneditor.min.js"></script>
    <script src="{{asset('assets/js/admin/template/sectionCreate.js?'.time())}}"></script>
@endsection
