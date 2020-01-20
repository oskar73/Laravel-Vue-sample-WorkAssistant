@extends('layouts.master')

@section('title', 'Logo Types Fonts')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Graphic Designs', 'Fonts']"/>
    </div>
    <div class="col-md-6 text-right">
        <label class="btn m-btn--custom btn-outline-info" for="upload-fonts">
            Upload New Fonts
            <input type="file" hidden id="upload-fonts" multiple  accept=".ttf,.woff,.otf"/>
        </label>
    </div>
@endsection

@section('content')

    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__head bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Fonts (<span class="all_count">0</span>)
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('admin.graphics.font.refresh') }}" class="tw-hidden tw-bg-white tw-px-4 tw-py-2 tw-rounded tw-text-blue-500">Refresh Font CSS</a>
            </div>
        </div>
        <div class="m-portlet__body">
            @include('components.admin.graphic-designs.font', ['selector'=>'datatable-all'])
        </div>
    </div>


    <x-layout.modal title="Upload fonts" id="upload_fonts_modal">
        <div class="mt-1 d-flex justify-content-between align-items-center mb-3">
            <b class="text-16px">File</b>
            <b class="text-16px">Font Name</b>
        </div>
        <div class="fonts-container"></div>
    </x-layout.modal>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/graphic-design/font.js')}}"></script>
@endsection
