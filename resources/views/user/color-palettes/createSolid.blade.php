@extends('layouts.master')

@section('title', 'Logo Types Color Palette')
@section('style')
    <link href="{{s3_asset('vendors/colorSwatch/style.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .colors .random-colors .gen {
            width:20% !important;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Logo Types', 'Color Category', 'Palettes']" :menuLinks="[route('user.logotypes.index'), route('user.color-palettes.index')]" />
    </div>
@endsection

@section('content')
    <div class="color_setting">
        <div id="color_body">
            <div class="mode">
                <button class="lightModeClk" id="light-icon"></button>
                <button class="darkModeClk" id="dark-icon"></button>
            </div>

            <div class="content">
                <div class="colors pb-0">
                    <form action="{{route("user.color-palettes.store",'solid')}}" method="POST" id="submit_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="wrap-btn pt-3">
                                    <button type="button" class="rdm-btn setRdmClk" >Randomize here</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="m-portlet__head-tools mt-3 mb-3 text-right">
                                    <input type="text" class="form-control maxw-300 d-inline-block mr-2" name="name" placeholder="Name" value="">
                                    <a href="{{route('user.color-palettes.index')}}#/solid" class="btn m-btn--square  btn-outline-primary m-2">
                                        Back
                                    </a>
                                    <button type="submit" class="btn m-btn--square  btn-outline-info m-2 smtBtn">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="random-colors menu_colors">
                            <div class="gen text-center">
                                <div class="child-color" ><input class="jscolor" id="js1" name="color1"></div>
                                <div class="hexcolor">
                                    <h3>#17405B</h3>
                                    <i class="jfa-copy " title="Copy"></i>
                                    <i class="jfa-lock " title="Lock/Unlock this color"></i>
                                    <label for="from_file1">
                                        <i class="fa fa-cloud-upload-alt " title="Pick color from file.">
                                            <input type="file" class="from_file d-none" id="from_file1" data-id="js1">
                                        </i>
                                    </label>
                                </div>
                            </div>

                            <div class="gen text-center">
                                <div class="child-color"><input class="jscolor" id="js2" name="color2"></div>
                                <div class="hexcolor">
                                    <h3>#0A8BA1</h3>
                                    <i class="jfa-copy " title="Copy"></i>
                                    <i class="jfa-lock " title="Lock/Unlock this color"></i>
                                    <label for="from_file2">
                                        <i class="fa fa-cloud-upload-alt " title="Pick color from file.">
                                            <input type="file" class="from_file d-none" id="from_file2" data-id="js2">
                                        </i>
                                    </label>
                                </div>
                            </div>

                            <div class="gen text-center">
                                <div class="child-color"><input class="jscolor" id="js3" name="color3"></div>
                                <div class="hexcolor">
                                    <h3>#09C1C5</h3>
                                    <i class="jfa-copy " title="Copy"></i>
                                    <i class="jfa-lock " title="Lock/Unlock this color"></i>
                                    <label for="from_file3">
                                        <i class="fa fa-cloud-upload-alt " title="Pick color from file.">
                                            <input type="file" class="from_file d-none" id="from_file3" data-id="js3">
                                        </i>
                                    </label>
                                </div>
                            </div>

                            <div class="gen text-center">
                                <div class="child-color"><input class="jscolor" id="js4" name="color4"></div>
                                <div class="hexcolor">
                                    <h3>#F1F3F4</h3>
                                    <i class="jfa-copy " title="Copy"></i>
                                    <i class="jfa-lock " title="Lock/Unlock this color"></i>
                                    <label for="from_file4">
                                        <i class="fa fa-cloud-upload-alt " title="Pick color from file.">
                                            <input type="file" class="from_file d-none" id="from_file4" data-id="js4">
                                        </i>
                                    </label>
                                </div>
                            </div>
                            <div class="gen text-center">
                                <div class="child-color"><input class="jscolor" id="js5" name="color5"></div>
                                <div class="hexcolor">
                                    <h3>#F1F3F4</h3>
                                    <i class="jfa-copy " title="Copy"></i>
                                    <i class="jfa-lock " title="Lock/Unlock this color"></i>
                                    <label for="from_file5">
                                        <i class="fa fa-cloud-upload-alt " title="Pick color from file.">
                                            <input type="file" class="from_file d-none" id="from_file5" data-id="js5">
                                        </i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="myModal" data-backdrop="static">
        <div class="jgjmodalcenter">
            <canvas id="canvas_picker" class="jgjcanvas"></canvas>
            <input type="color" class="jgjpickedcolor" value="#ffffff" >
            <a href="javascript:void(0);" class="btn btn-danger m-btn--sm jgjmodalclosebtn"  data-dismiss="modal">X</a>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/jscolor/jscolor.js')}}"></script>
    <script src="{{s3_asset('vendors/colorSwatch/colorSwatch.js')}}"></script>
    <script src="{{asset('assets/js/user/color-palettes/solidCreate.js')}}"></script>
@endsection
