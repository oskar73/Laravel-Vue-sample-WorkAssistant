@extends('layouts.master')

@section('title', 'Logo Types Color Palette')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Logo Types', 'Color Category']" :menuLinks="[route('user.logotypes.index')]" />
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#gradient" href="#/gradient"> Gradient (<span class="gradient_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#solid" href="#/solid"> Solid (<span class="solid_count">0</span>)</a></li>
        </ul>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="gradient_area">
        <div class="m-portlet__body">
            <div class="text-right">
                <div class="text-right">
                    <a href="#" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info sortBtn mb-2" data-gradient="1">Sort Order</a>
                    <a href="{{route('user.color-palettes.create', ['type'=>'gradient'])}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2 create_cat_btn" >Add New</a>
                </div>
            </div>
            <div class="gradient_body">
                <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="solid_area">
        <div class="m-portlet__body">
            <div class="text-right">
                <a href="#" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info sortBtn mb-2" data-gradient="0">Sort Order</a>
                <a href="{{route('user.color-palettes.create', ['type'=>'solid'])}}" class="ml-auto btn m-btn--sm m-btn--square btn-outline-success mb-2 create_cat_btn" >Add New</a>
            </div>
            <div class="solid_body">
                <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/user/color-palettes/index.js')}}"></script>
@endsection
