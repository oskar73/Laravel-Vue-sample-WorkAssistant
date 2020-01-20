@extends('layouts.master')

@section('title', 'Account Setting')
@section('style')
@endsection
@section('breadcrumb')
<div class="col-md-6 text-left">
    <x-layout.breadcrumb :menus="['Account', 'Setting']" />
</div>
@endsection
@php
    $address = json_decode(user()->address);
@endphp
@section('content')
<div class="container">
    <h1>My Account</h1>
    <form method="POST" action="{{route('account.setting.update')}}">@csrf
        <div class="card mt-5">
            <div class="card-header bg-white">
                <h5 class="text-secondary mt-2 mb-2">Update Account Info</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" value="{{user()->id}}" name="user_id">
                    <div class="col-md-4">
                        <x-form.input name="first_name" label="First Name" value="{{user()->first_name}}" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="last_name" label="Last Name" value="{{user()->last_name}}" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input name="email" label="Email" value="{{user()->email}}" />
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input name="date_of_birth" class="form-control" value="{{user()->birthday}}" id="date_of_birth" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="phone" value="{{user()->phone_no}}" label="Phone Number"/>
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="address1" value="{{$address?->address1}}" label="Address1" placeholder="Enter Street Address, P.O. Box, Military Address"/>
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="address2" value="{{$address?->address2}}" label="Address2" placeholder="Enter Apt, Suite, Floor (Optional)"/>
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="city" value="{{$address?->city}}" label="City"/>
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="state" value="{{$address?->state}}" label="State"/>
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="zipcode" value="{{$address?->zipcode}}" label="Zip/Postal Code"/>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country (*)</label>
                            <select name="country" id="country" class="selectpicker form-control" data-width="100%" data-live-search="true">
                                @foreach(\App\Models\Country::get(["iso", "nicename"]) as $country)
                                    <option value="{{$country->iso}}" @if($address?->country==$country->iso) selected @else @if($country->iso=='US') selected @endif @endif>{{$country->nicename}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-country"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="timezone">
                                Timezone
                                <i class="fa fa-info-circle tooltip_1" title="Timezone"></i>
                            </label>
                            {!! getTimezoneList("name='timezone' class='form-control' data-live-search='true' value='".user()->timezone."'", user()->timezone) !!}
                            <div class="form-control-feedback error-timezone"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form.selectpicker label="Time Format:" name="timeformat" search="false">
                            <option value="Y-m-d H:i:s" @if(user()->timeformat==='Y-m-d H:i:s') selected @endif>2020-05-16 07:30:00</option>
                            <option value="m/d/Y H:i:s" @if(user()->timeformat==='m/d/Y H:i:s') selected @endif>05/16/2020 07:30:00</option>
                        </x-form.selectpicker>
                    </div>
                    <div class="col-md-6">
                        <x-form.select label="Gender" name="gender" class="tw-w-full">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </x-form.select>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                <button type="submit" class="btn"> Cancel</button>
            </div>
        </div>
    </form>
    <form action="{{route('account.password.update')}}" method="POST">@csrf
        <div class="card mt-5">
            <div class="card-header bg-white">
                <h5 class="text-secondary mt-2 mb-2">Change Password</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-form.input type="password" name="old_password" label="Old Password" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input type="password" name="new_password" label="New Password" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input type="password" name="confirm_password" label="Confirm Password" />
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Change Password</button>
                <button type="submit" class="btn"> Cancel</button>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="{{asset('assets/js/account/profile.js')}}"></script>
@endsection
