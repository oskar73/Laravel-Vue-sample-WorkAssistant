@extends('layouts.master')

@section('title', 'User Management')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['User Management', 'Create User']" :menuLinks="[]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.userManage.index')}}" label="Back"/>
    </div>
@endsection
@section('content')
    <x-layout.portlet id="all_area" active="1" label="Create User">
        <x-form.form action="{{route('admin.userManage.store')}}">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        {{-- <x-form.thumbnail label="Upload Profile Photo">
                            {{asset("assets/img/default.jpg")}}
                        </x-form.thumbnail> --}}
                        <div class="slim slimdiv"
                                data-download="true"
                                data-label="Drop or choose profile photo"
                                data-max-file-size="{{config('custom.variable.max_image_size')}}"
                                data-instant-edit="true"
                                data-button-remove-title="Upload"
                                data-ratio="1:1">
                            <input type="file" name="image" />
                        </div>
{{--                        <div class="form-group position-relative text-center pt-5">--}}
{{--                            <label for="image" class="btn btn-outline-info m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air choose_btn_container">--}}
{{--                                <i class="la la-edit"></i>--}}
{{--                            </label>--}}
{{--                            <input type="file" accept="image/*" class="form-control m-input--square d-none" id="image" >--}}
{{--                            <img id="avatar" class="image_upload_output width-300px border height-300" />--}}
{{--                        </div>--}}
                    </div>

                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">
                                        Username
                                    </label>
                                    <div class="input-group m-input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">@</span>
                                        </div>
                                        <input type="text" class="form-control m-input" name="username" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="form-control-feedback error-username"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <x-form.input name="pin_number" label="PIN Number" type="number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">
                                Email address
                            </label>

                            <div class="input-group m-input--square">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <label class="m-checkbox m-checkbox--single m-checkbox--state m-checkbox--state-success mb-2">
                                            <input type="checkbox" name="verified">
                                            <span></span>
                                        </label>
                                        &nbsp; Verified
                                    </span>
                                </div>
                            </div>
                            <div class="form-control-feedback error-email"></div>
                        </div>

                        <x-form.input name="password" label="Password" type="password"/>

                        <div class="form-group">
                            <label for="roles" class="form-control-label">
                                Choose Roles:
                            </label>
                            <select name="roles[]" id="roles" class="roles select2" multiple>
                                <option ></option>
                                <option value="admin" >Admin</option>
                                <option value="employee" >Employee</option>
                                <option value="client" >Client</option>
                            </select>
                            <div class="form-control-feedback error-appCats"></div>
                        </div>

                        <x-form.selectpicker name="status" label="Status" search="false">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </x-form.selectpicker>

                        <br>

                        <x-form.checkbox label="Subscribe to Newsletter" name="subscribe" checked="checked"/>

                        <div class="form-group text-right">
                            <x-form.smtBtn label="Submit" />
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
        var ratio_width = 1,
            ratio_height= 1
    </script>
    <script src="{{asset('assets/js/account/image_crop.js')}}"></script>
    <script src="{{asset('assets/js/admin/userManage/create.js')}}"></script>
@endsection
