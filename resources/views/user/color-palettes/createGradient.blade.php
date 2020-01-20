@extends('layouts.master')

@section('title', 'Logo Types Color Palette')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Logo Types', 'Color Category', 'Palettes']" :menuLinks="[route('user.logotypes.index'), route('user.color-palettes.index')]" />
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="palette_area">
        <div class="m-portlet__head bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Gradient Color Palette (<span class="palette_count">0</span>)
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="palette_body" id="admin_vue">
                <gradient-palette
                    :palette_id="0"
                    :attrs_prop="null"
                    name_prop=""
                ></gradient-palette>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('logo/js/admin/admin-vue.js?'.time())}}"></script>
@endsection
