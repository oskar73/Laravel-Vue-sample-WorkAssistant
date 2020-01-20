@extends('layouts.master')

@section('title', 'User Management')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['User Management', 'Edit User']" :menuLinks="[]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.userManage.index')}}" label="Back" />
    </div>
@endsection
@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#profile" href="#/profile">Profile</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#password" href="#/password">Password</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="profile_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.userManage.updateProfile', $user->id)}}" id="profileForm">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group position-relative text-center pt-5 slimdiv">
                            {{-- <label for="image" class="btn btn-outline-info m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air choose_btn_container">
                                <i class="la la-edit"></i>
                            </label>
                            <input type="file" accept="image/*" class="form-control m-input--square d-none" id="image" >
                            <img id="avatar" class="image_upload_output w-300px" src="{{$user->avatar()}}" /> --}}

                            <label for="image" class="form-control-label"> Upload Profile Photo</label>
                            <input type="file" name="image" id="image" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="first_name">
                                First Name
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[1]}}'
                                   data-page="{{$view_name}}"
                                   data-id="1"
                                ></i>
                            </label>
                            <input type="text" class="form-control m-input m-input--square" name="first_name"
                                   id="first_name" placeholder="First Name" value="{{$user->first_name}}">
                            <div class="form-control-feedback error-first_name"></div>
                        </div>
                        <div class="form-group">
                            <label for="name">
                                Last Name
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[1]}}'
                                   data-page="{{$view_name}}"
                                   data-id="1"
                                ></i>
                            </label>
                            <input type="text" class="form-control m-input m-input--square" name="last_name"
                                   id="last_name" placeholder="Last Name" value="{{$user->last_name}}">
                            <div class="form-control-feedback error-last_name"></div>
                        </div>
                        <div class="form-group">
                            <label for="name">
                                Username
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[2]}}'
                                   data-page="{{$view_name}}"
                                   data-id="2"
                                ></i>
                            </label>
                            <div class="input-group m-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="text" class="form-control m-input" name="username"
                                       aria-describedby="basic-addon1" value="{{$user->username}}">
                            </div>
                            <div class="form-control-feedback error-username"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">
                                Email address
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[3]}}'
                                   data-page="{{$view_name}}"
                                   data-id="3"
                                ></i>
                            </label>

                            <div class="input-group m-input--square">
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Email Address" value="{{$user->email}}">
                                <div class="input-group-append">
                                <span class="input-group-text">
                                    <label class="m-checkbox m-checkbox--single m-checkbox--state m-checkbox--state-success mb-2">
                                        <input type="checkbox" @if($user->email_verified_at) checked
                                               @endif name="verified">
                                        <span></span>
                                    </label>
                                    &nbsp; Verified
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[4]}}'
                                       data-page="{{$view_name}}"
                                       data-id="4"
                                    ></i>
                                </span>
                                </div>
                            </div>
                            <div class="form-control-feedback error-email"></div>
                        </div>

                        <div class="form-group">
                            <label for="roles" class="form-control-label">
                                Choose Roles:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[5]}}'
                                   data-page="{{$view_name}}"
                                   data-id="5"
                                ></i>
                            </label>
                            <select name="roles[]" id="roles" class="roles select2" multiple>
                                <option></option>
                                <option value="admin" @if($user->hasRole('admin')) selected @endif>Admin</option>
                                <option value="employee" @if($user->hasRole('employee')) selected @endif>Employee
                                </option>
                                <option value="client" @if($user->hasRole('client')) selected @endif>Client</option>

                            </select>
                            <div class="form-control-feedback error-appCats"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="timezone">
                                Timezone
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[6]}}'
                                   data-page="{{$view_name}}"
                                   data-id="6"
                                ></i>
                            </label>
                            {!! getTimezoneList("name='timezone' class='form-control selectpicker' id='timezone' data-live-search='true' value='".$user->timezone."'") !!}
                            <div class="form-control-feedback error-timezone"></div>
                        </div>

                        <div class="form-group">
                            <label for="username">Time Format:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[7]}}'
                                   data-page="{{$view_name}}"
                                   data-id="7"
                                ></i>
                            </label>
                            <select name="time_format" id="timeformat" class="form-control selectpicker">
                                <option value="Y-m-d H:i:s">2020-05-16 07:30:00</option>
                                <option value="m/d/Y H:i:s">05/16/2020 07:30:00</option>
                            </select>
                            <div class="form-control-feedback error-time_format"></div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[8]}}'
                                   data-page="{{$view_name}}"
                                   data-id="8"
                                ></i>
                            </label>
                            <select name="status" id="status" class="form-control selectpicker">
                                <option value="active" @if($user->status=='active') selected @endif>Active</option>
                                <option value="inactive" @if($user->status=='inactive') selected @endif>Inactive
                                </option>
                            </select>
                            <div class="form-control-feedback error-status"></div>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[8]}}'
                                   data-page="{{$view_name}}"
                                   data-id="8"
                                ></i>
                            </label>
                            <select name="gender" id="gender" class="form-control selectpicker">
                                <option value="male"
                                        @if(isset($user->socialAccount) && $user->socialAccount->gender=='male') selected @endif>
                                    Male
                                </option>
                                <option value="female"
                                        @if(isset($user->socialAccount) && $user->socialAccount->gender=='female') selected @endif>
                                    Female
                                </option>
                            </select>
                            <div class="form-control-feedback error-gender"></div>
                        </div>
                        <div class="form-group text-right">
                            <button class="btn m-btn--square  btn-outline-success m-btn m-btn--custom smtBtn"
                                    type="submit">Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="password_area">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form action="{{route('admin.userManage.updatePassword', $user->id)}}" id="passwordForm">
                        @csrf
                        <div class="form-group m-form__group">
                            <label for="new_password">New Password:</label>
                            <input type="password" class="form-control m-input--square" name="new_password"
                                   id="new_password" placeholder="New Password">
                            <div class="form-control-feedback error-new_password"></div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control m-input--square" id="confirm_password"
                                   name="confirm_password" placeholder="Confirm Password">
                            <div class="form-control-feedback error-confirm_password"></div>
                        </div>
                        <div class="mt-3 text-right">
                            <button type="submit"
                                    class="btn m-btn--square  btn-outline-success m-btn m-btn--custom pswBtn">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>
      var timezone = "{{$user->timezone}}",
        format = "{{$user->timeformat}}",
        maxImageSize = "{{config('custom.variable.max_image_size')}}"

      @if($user->getFirstMediaUrl("avatar"))
        window.thumbNailUrl = '{{$user->getFirstMediaUrl("avatar")}}'
        @endif
    </script>
    <script src="{{asset('assets/js/admin/userManage/edit.js')}}"></script>
@endsection
