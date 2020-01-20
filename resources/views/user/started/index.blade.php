@extends('layouts.master')

@section('title', 'Getting Started')
@section('style')
    <style>
    </style>
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Getting Started']" :menuLinks="[]" />
    </div>
@endsection

@section('content')
     <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="sidebar-tab">
                    <ul class="sidebar-tab-ul">
                        <li class="tab-item">
                            <a class="tab-link username_link tw-cursor-pointer" data-area="#setup-username">
                               1. Username & Pin Number
                            </a>
                        </li>
                        <li class="tab-item">
                            <a class="tab-link demographic_link tw-cursor-pointer" data-area="#setup-demographic">
                                2. Demographics
                            </a>
                        </li>
                        <li class="tab-item">
                            <a class="tab-link tw-cursor-pointer" data-area="#setup-time">
                                3. Timezone and Format
                            </a>
                        </li>
                        <li class="tab-item">
                            <a class="tab-link tw-cursor-pointer" data-area="#profile">
                                4. Profile
                            </a>
                        </li>
                        <li class="tab-item">
                            <a class="tab-link tw-cursor-pointer" data-area="#intro">
                                5. Introduction
                            </a>
                        </li>
                    </ul>
                    <div><span class="progress_percentage">{{user()->getCompletedPercentage()}}</span>% completed</div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-success progress_bar" role="progressbar"
                             style="width: 25%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <x-layout.portlet active="1" id="first_screen">
                    <div class="text-center pt-5">
                        <h1 class="fs-3-5"><b>Getting Started Quick Setup!</b></h1>
                    </div>
                    <div class="text-center pt-5">
                        <h2>{{ option('getting-started.video.title','') }}</h2>
                    </div>
                    <div class="text-center pt-1">
                        @if (option('getting-started.video.url',''))
                            @if (option('getting-started.video.isYouTube',''))
                                <iframe width="560" height="315" src={{ "https://www.youtube.com/embed/" . option('getting-started.video.url','') }} frameborder="0" allowfullscreen style="display: inline-block"></iframe>
                            @else
                                <div class="tw-relative" style="height:315px !important;">
                                    <video id="videoElem" style="height:315px !important; width: 560px !important; background: black; color: white; margin:auto">
                                        <source src="{{option('getting-started.video.url','')}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                    <div id="videoElemOverlay" class="tw-flex tw-justify-center tw-items-center mt-5 tw-absolute tw-bottom-0 tw-right-0 tw-h-full tw-w-full" style="padding: 40px; gap: 10px;">
                                        <span class="mdi mdi-play-circle-outline" style="font-size: 75px; cursor: pointer" onclick="handlePlay()"></span>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="text-center pt-1">
                        <h2 class="mt-5">{{ option('getting-started.video.description','Hello! Please complete these steps to finish setup.') }}</h2>
                    </div>

                    <div class="text-right mt-5">
                        <a href="#/setup-username" class="btn m-btn--square m-btn--custom btn-lg btn-outline-success tab-link" data-area="#setup-username">Next</a>
                    </div>

                </x-layout.portlet>

                <x-layout.portlet active="0" id="setup-username_area" label="Username & Pin Number">
                    <x-form.form action="{{route('user.getting.started.username')}}" id="username_form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 text-info tw-flex tw-gap-2 tw-justify-center">
                                    <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-info">
                                        <div class="m-alert__icon">
                                            <i class="flaticon-exclamation-1"></i>
                                            <span></span>
                                        </div>
                                        <div class="m-alert__text">
                                            <p class="fs-16 mb-0">
                                                <strong>Please write these down to use when contacting customer service</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <div class="input-group m-input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@</span>
                                            </div>
                                            <input type="text" class="form-control m-input" name="username" value="{{user()->username}}">
                                        </div>
                                        <div class="form-control-feedback error-username"></div>
                                    </div>
                                    <br>
                                    <x-form.input name="pin_number" value="{{user()->pin_number}}" label="Pin Number"/>
                                    <br>
                                    <div class="text-right">
                                        <button type="submit" class="btn m-btn--custom btn-outline-success smtBtn">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-form.form>
                </x-layout.portlet>
                <x-layout.portlet active="0" id="setup-demographic_area" label="Demographics">
                    <x-form.form action="{{route('user.getting.started.demographics')}}" id="demographic_form">
                        @php
                            $address = json_decode(user()->address);
                        @endphp
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="autocomplete" class="form-control-label">
                                            Address1: <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control m-input--square"
                                            name="address1"
                                            id="autocomplete"
                                            value="{{$address?->address1}}"
                                            placeholder="Enter Street Address, P.O. Box, Military Address"
                                            required
                                        >
                                        <div class="form-control-feedback error-address1"></div>
                                    </div>

                                    <input type="hidden" id="latitude" name="latitude" class="form-control"  value="{{$address?->latitude}}">
                                    <input type="hidden" id="longitude" name="longitude" class="form-control"  value="{{$address?->longitude}}">

                                    <x-form.input name="address2" value="{{$address?->address2}}" label="Address2" placeholder="Enter Apt, Suite, Floor (Optional)"/>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-form.input name="city" value="{{$address?->city}}" label="City" required />
                                        </div>
                                        <div class="col-md-6">
                                            <x-form.input name="state" value="{{$address?->state}}" label="State" required />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-form.input name="zipcode" value="{{$address?->zipcode}}" label="Zip/Postal Code" required />
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
                                    </div>
                                    <div class="form-control-feedback error-latitude"></div>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#setup-username" href="#/setup-username">Previous</a>
                                        <button type="submit" class="btn m-btn--custom btn-outline-success smtBtn">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-form.form>
                </x-layout.portlet>

                <x-layout.portlet active="0" id="setup-time_area" label="Timezone & Time Format">
                    <x-form.form action="{{route('user.getting.started.time')}}" id="time_form">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label for="timezone">
                                            Timezone
                                            <i class="fa fa-info-circle tooltip_1" title="Timezone"></i>
                                        </label>
                                        {!! getTimezoneList("name='timezone' class='form-control' id='timezone' data-live-search='true' value='".user()->timezone."'") !!}
                                        <div class="form-control-feedback error-timezone"></div>
                                    </div>
                                    <br>
                                    <x-form.selectpicker label="Time Format:" name="timeformat" search="false">
                                        <option value="Y-m-d H:i:s" @if(user()->timeformat==='Y-m-d H:i:s') selected @endif>2020-05-16 07:30:00</option>
                                        <option value="m/d/Y H:i:s" @if(user()->timeformat==='m/d/Y H:i:s') selected @endif>05/16/2020 07:30:00</option>
                                    </x-form.selectpicker>
                                    <br>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#setup-demographic" href="#/setup-demographic">Previous</a>
                                        <button type="submit" class="btn m-btn--custom btn-outline-success smtBtn">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-form.form>
                </x-layout.portlet>
                <x-layout.portlet id="profile_area" active="0" label="Profile">
                    <x-form.form action="{{route('account.profile.update')}}" id="profileForm">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="slim slimdiv"
                                            data-download="true"
                                            data-max-file-size="{{config('custom.variable.max_image_size')}}"
                                            data-instant-edit="true"
                                            data-button-remove-title="Upload"
                                            data-ratio="1:1">
                                        <input type="file" name="image" id="image" />
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <x-form.input name="first_name" label="First Name" value="{{user()->first_name}}"/>

                                    <x-form.input name="last_name" label="Last Name" value="{{user()->last_name}}"/>

                                    <x-form.input type="email" name="email" label="Email address" value="{{user()->email}}"/>

                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth: <span class="text-danger">*</span></label>
                                        <input  name="birthday" class="form-control" value="{{user()->birthday}}" id="date_of_birth" required />
                                        <div class="form-control-feedback error-birthday"></div>
                                    </div>

                                    <x-form.input name="phone" value="{{user()->phone_no}}" label="Phone Number" />

                                    <x-form.select label="Gender" name="gender" class="tw-w-full">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </x-form.select>

                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#setup-time_area" href="#/setup-time_area">Previous</a>
                                        <button type="submit" class="btn m-btn--custom btn-outline-success smtBtn">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-form.form>
                </x-layout.portlet>
{{--                don't need to display previous value--}}
                <x-layout.portlet id="intro_area" active="0" label="Introduction">
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
                                    <x-form.input name="facebook" label="Facebook" />
                                    <x-form.input name="twitter" label="Twitter" />
                                    <x-form.input name="linkedin" label="LinkedIn" />
                                    <x-form.input name="youtube" label="YouTube" />
                                    <x-form.input name="vk" label="VK" />
                                    <x-form.input name="instagram" label="Instagram" />
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <x-form.smtBtn label="Submit" />
                            </div>
                        </div>
                    </x-form.form>
                </x-layout.portlet>
                <x-layout.portlet active="0" id="setup-complete_area" label="Welcome!">
                    <div class="pt-5 container">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-info">
                                    <div class="m-alert__icon">
                                        <i class="flaticon-exclamation-1"></i>
                                        <span></span>
                                    </div>
                                    <div class="m-alert__text">
                                        <p class="fs-16 mb-0"><strong>{{option('getting-started.complete.title','Please note some important tools to assist with navigation and Help')}}</strong></p>

                                    </div>
                                </div>
                                <div class="mt-5 p-4 pl-5 border border-success">
                                    @if (option('getting-started.complete.content',''))
                                    {!! option('getting-started.complete.content','') !!}
                                    @else
                                    <div class="row mb-3">
                                        <div class="col-2 text-right">
                                            <i class="la la-info-circle tooltip_3" title="Requirements, Extra explanations."></i>
                                        </div>
                                        <div class="col-10">
                                            : If you mouse hover this, it will show requirements and extra explanations.
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-2 text-right">
                                            "Help"
                                        </div>
                                        <div class="col-10">
                                            :
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-2 text-right">
                                            <a href="{{route('user.tutorial.index')}}" class="c-badge c-badge-success h-cursor">Tutorial</a>
                                        </div>
                                        <div class="col-10">
                                            : If you click this button, it will redirect to tutorial page directly.
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-2 text-right">
                                            "Tickets"
                                        </div>
                                        <div class="col-10">
                                            :
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between mt-5">
                                    <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#setup-time" href="#/setup-time">Previous</a>
                                    <a href="{{route('user.getting.started.complete')}}" class="btn m-btn--square m-btn--custom btn-outline-success tab-link complete_btn" onclick="btnLoading('.complete_btn')">Complete</a>
                                </div>

                            </div>
                        </div>
                    </div>

                </x-layout.portlet>
            </div>
        </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ option('google_map_key', '') }}&libraries=places" ></script>
    <script src="{{asset('assets/js/user/started.js')}}"></script>
    <script>
        function handlePlay() {
            document.getElementById('videoElem').play()
            $('#videoElemOverlay').hide()
        }
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                let address1 = '';
                let address2 = '';

                place.address_components.forEach(component => {
                    if (component.types.includes('street_number')) {
                        address1 += component.long_name + ' ';
                    } else if (component.types.includes('route')) {
                        address1 += component.long_name;
                    } else if (component.types.includes('subpremise') || component.types.includes('floor') || component.types.includes('room')) {
                        address2 += component.long_name + ' ';
                    }
                    if (component.types.includes('locality')) {
                        $('#city').val(component.long_name)
                    } else if (component.types.includes('postal_code')) {
                        $('#zipcode').val(component.long_name)
                    } else if (component.types.includes('country')) {
                        // $('#country').val(component.long_name)
                    } else if (component.types.includes('administrative_area_level_1')) {
                        $('#state').val(component.long_name)
                    }
                });

                $('#autocomplete').val(address1)
                $('#address2').val(address2.trim())

                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
            });
        }
    </script>
@endsection
