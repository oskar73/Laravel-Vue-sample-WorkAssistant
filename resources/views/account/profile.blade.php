@extends('layouts.master')

@section('title', 'Profile & Security')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Account', 'Profile']" />
    </div>
@endsection
@section('content')

    <x-layout.tabs-wrapper>
        @if(!(user()->isPasswordForceUpdateNeed()))
            <x-layoutItems.normal-tab title="Profile" link="profile" active="1" />
            <x-layoutItems.normal-tab title="Introduction" link="intro" active="0" />
        @endif
        <x-layoutItems.normal-tab title="Password" link="password" active="0" />
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="profile_area" active="1">
        <x-form.form action="{{route('account.profile.update')}}" id="profileForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        {{-- <div class="form-group position-relative text-center pt-5">
                            <label for="image" class="btn btn-outline-info m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air choose_btn_container">
                                <i class="la la-edit"></i>
                            </label>
                            <input type="file" accept="image/*" class="form-control m-input--square d-none" id="image" >
                            <img id="avatar" class="image_upload_output w-300px" src="{{auth()->user()->avatar()}}" />
                        </div> --}}
                        <div class="slimdiv"
                             data-download="true"
                             data-max-file-size="{{config('custom.variable.max_image_size')}}"
                             data-instant-edit="true"
                             data-button-remove-title="Upload"
                             data-ratio="1:1">
                            <input type="file" name="image" id="image" />
                        </div>
                    </div>
                    <div class="col-md-8">

                        <x-form.input name="first_name" label="First Name" value="{{user()->first_name}}" />

                        <x-form.input name="last_name" label="Last Name" value="{{user()->last_name}}" />

                        <x-form.input type="email" name="email" label="Email address" value="{{user()->email}}" />

                        <x-form.input type="date" name="birthday" label="Birthday" value="{{user()->birthday}}" />

                        <div class="form-group">
                            <label for="timezone">
                                Timezone
                                <i class="fa fa-info-circle tooltip_1" title="Timezone"></i>
                            </label>
                            {!! getTimezoneList("name='timezone' class='form-control selectpicker' id='timezone' data-live-search='true' value='".user()->timezone."'") !!}
                            <div class="form-control-feedback error-timezone"></div>
                        </div>

                        <x-form.selectpicker label="Time Format:" name="time_format" search="false">
                            <option value="Y-m-d H:i:s">2020-05-16 07:30:00</option>
                            <option value="m/d/Y H:i:s">05/16/2020 07:30:00</option>
                        </x-form.selectpicker>

                        <x-form.select label="Gender" name="gender" class="tw-w-full">
                            <option value="male"
                                    @if(isset($socialAccount) && $socialAccount->gender === 'male') selected @endif>Male
                            </option>
                            <option value="female"
                                    @if(isset($socialAccount) && $socialAccount->gender === 'female') selected @endif>
                                Female
                            </option>
                        </x-form.select>

                        <div class="form-group text-right">
                            <x-form.smtBtn label="Submit" />
                        </div>
                    </div>
                </div>
            </div>
        </x-form.form>
    </x-layout.portletBody>
    <x-layout.portletBody id="intro_area" active="0">
        <x-form.form action="{{route('account.profile.update')}}" id="introForm">
            <div class="container-fluid">
                <div class="row tw-h-full">
                    <div class="col-md-8 tw-h-full d-flex flex-column">
                        <div class="form-group tw-h-full">
                            <label for="intro" class="form-control-label">Description:</label>
                            <textarea
                                    class="form-control m-input--square minh-100 white-space-pre-line"
                                    name="intro"
                                    id="intro"
                            ></textarea>
                            <div class="form-control-feedback error-intro"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="facebook" label="Facebook"
                                      value="{{ isset($socialAccount) ? $socialAccount->facebook : '' }}" />
                        <x-form.input name="twitter" label="Twitter"
                                      value="{{ isset($socialAccount) ? $socialAccount->twitter : '' }}" />
                        <x-form.input name="linkedin" label="LinkedIn"
                                      value="{{ isset($socialAccount) ? $socialAccount->linkedin : '' }}" />
                        <x-form.input name="youtube" label="YouTube"
                                      value="{{ isset($socialAccount) ? $socialAccount->youtube : '' }}" />
                        <x-form.input name="vk" label="VK"
                                      value="{{ isset($socialAccount) ? $socialAccount->vk : '' }}" />
                        <x-form.input name="instagram" label="Instagram"
                                      value="{{ isset($socialAccount) ? $socialAccount->instagram : '' }}" />
                    </div>
                </div>
                <div class="form-group text-right">
                    <x-form.smtBtn label="Submit" />
                </div>
            </div>
        </x-form.form>
    </x-layout.portletBody>
    <x-layout.portletBody id="password_area" active="0">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                @if(user()->isPasswordForceUpdateNeed())
                    <x-form.alert-danger title="You need to update your old password to secure your account." />
                    <br>
                @endif
                <x-form.form action="{{route('account.password.update')}}" id="passwordForm">

                    @if(user()->password!=null)
                        <x-form.input type="password" name="old_password" label="Old Password:" />
                    @endif
                    <x-form.input type="password" name="new_password" label="New Password:" />
                    <x-form.input type="password" name="confirm_password" label="Confirm Password:" />

                    <div class="mt-3 text-right">
                        <button type="submit" class="btn m-btn--square  btn-outline-info m-btn m-btn--custom pswBtn">
                            Submit
                        </button>
                    </div>
                </x-form.form>
            </div>
        </div>
    </x-layout.portletBody>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>
      var timezone = "{{user()->timezone}}",
        format = "{{user()->timeformat}}",
        introduction = {!! json_encode(user()->introduction) !!},
        maxImageSize = "{{config('custom.variable.max_image_size')}}"

      @if(user()->getFirstMediaUrl("avatar"))
        window.thumbNailUrl = '{{user()->getFirstMediaUrl("avatar")}}'
        @endif
    </script>
    <script type="text/javascript" src="{{asset('assets/js/account/profile.js')}}"></script>
@endsection
