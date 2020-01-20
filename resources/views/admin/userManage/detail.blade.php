@extends('layouts.master')

@section('title', 'User Management')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['User Management', 'User Detail']" :menuLinks="[]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.userManage.index')}}" label="Back"/>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="sidebar-tab">
                <div class="mb-3 text-center">
                    <img id="avatar" class="image_upload_output maxw-100 wh-200px m-auto" src="{{$user->avatar()}}"/>
                </div>

                <ul class="sidebar-tab-ul">
                    <li class="tab-item"><a class="tab-link tab-active" data-area="#profile" href="#/profile">Profile</a></li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#website" href="#/website">
                            Website
                            @if($user->websites->count())<span class="count_area">({{$user->websites->count()}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#domain" href="#/domain">
                            Domain
                            @if($user->domains_count)<span class="count_area">({{$user->domains_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#readyMadeBiz" href="#/readyMadeBiz">
                            Ready Made BIZ
                            @if($user->readymades_count)<span class="count_area">({{$user->readymades_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#package" href="#/package">
                            Package
                            @if($user->packages_count)<span class="count_area">({{$user->packages_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#plugin" href="#/plugin">
                            Plugin
                            @if($user->plugins_count)<span class="count_area">({{$user->plugins_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#service" href="#/service">
                            Service
                            @if($user->services_count)<span class="count_area">({{$user->services_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#lacarte" href="#/lacarte">
                            Lacarte
                            @if($user->lacartes_count)<span class="count_area">({{$user->lacartes_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#blog" href="#/blog">
                            Blog
                            @if($user->posts_count)<span class="count_area">({{$user->posts_count}})</span> @endif
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#blogAds" href="#/blogAds">
                            Blog Advertisement
                            @if($user->blog_ads_listings_count)<span class="count_area">({{$user->blog_ads_listings_count}})</span> @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-10 col-md-8">
            <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="profile_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Profile
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="form-group">
                                <label for="account_number">
                                    PIN Number
                                    <i class="fa fa-info-circle tooltip_3" title="account_number"></i>
                                </label>
                                <input type="text" class="form-control m-input m-input--square" name="account_number" id="account_number" value="{{$user->pin_number}}" readonly>
                                <div class="form-control-feedback error-account_number"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">
                                    Name
                                    <i class="fa fa-info-circle tooltip_3" title="Name"></i>
                                </label>
                                <input type="text" class="form-control m-input m-input--square" name="name" id="name" value="{{$user->name}}" readonly>
                                <div class="form-control-feedback error-name"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">
                                    Username
                                    <i class="fa fa-info-circle tooltip_3" title="Username"></i>
                                </label>
                                <div class="input-group m-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input type="text" class="form-control m-input" name="username" readonly value="{{$user->username}}">
                                </div>
                                <div class="form-control-feedback error-username"></div>
                            </div>
                            <div class="form-group">
                                <label for="email">
                                    Email address
                                    <i class="fa fa-info-circle tooltip_3" title="Email Address"></i>
                                </label>

                                <div class="input-group m-input--square">
                                    <input type="email" class="form-control" name="email" id="email" value="{{$user->email}}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <label class="m-checkbox m-checkbox--single m-checkbox--state m-checkbox--state-success mb-2">
                                                <input type="checkbox" name="verified" @if($user->email_verified_at) checked @endif onclick="return false;">
                                                <span></span>
                                            </label>
                                            &nbsp; Verified
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control-feedback error-email"></div>
                            </div>
                            <div class="form-group">
                                <label for="timezone">
                                    Timezone
                                    <i class="fa fa-info-circle tooltip_3" title="Timezone"></i>
                                </label>
                                <input type="text" class="form-control m-input m-input--square" name="timezone" id="timezone" value="{{$user->timezone}}" readonly>
                                <div class="form-control-feedback error-timezone"></div>
                            </div>
                            <div class="form-group">
                                <label for="timeformat">Time Format:
                                    <i class="fa fa-info-circle tooltip_3"
                                       title="This is your time format.">
                                    </i>
                                </label>
                                <input type="text" class="form-control m-input m-input--square" name="time_format" id="time_format" value="{{$user->timeformat}}" readonly>
                                <div class="form-control-feedback error-time_format"></div>
                            </div>
                            <div class="form-group">
                                <label for="roles" class="form-control-label">
                                    User Roles:
                                    <i class="fa fa-info-circle tooltip_3"
                                       title="This is user roles. Default users have 'user' role.">
                                    </i>
                                </label>
                                <select name="roles[]" id="roles" class="roles select2" multiple>
                                    <option ></option>
                                    <option value="admin" @if($user->hasRole('admin')) selected @else disabled @endif>Admin</option>
                                    <option value="employee" @if($user->hasRole('employee')) selected @else disabled @endif>Employee</option>
                                    <option value="client" @if($user->hasRole('client')) selected @else disabled @endif>Client</option>

                                </select>
                                <select label="Gender" name="gender" class="tw-w-full">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <div class="form-control-feedback error-appCats"></div>
                            </div>
                            <div class="text-right">
                                <a href="{{route('admin.userManage.edit', $user->id)}}" class="btn m-btn--square  btn-outline-success m-btn m-btn--custom">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="website_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Website
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include('components.admin.websiteTable', ['selector'=>'datatable-all', 'user'=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="domain_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Domain
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">
                    @include("components.admin.domainListTable", ['selector'=>'datatable-all', 'user'=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="readyMadeBiz_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Ready Made BIZ
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.pPackageTable", ['selector'=>'datatable-all', "user"=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="package_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Package
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.pPackageTable", ['selector'=>'datatable-all', "user"=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="plugin_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                               Plugin
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.pPluginTable", ['selector'=>'datatable-all', "user"=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="service_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Service
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.pServiceTable", ['selector'=>'datatable-all', "user"=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="lacarte_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                lacarte
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.pLacarteTable", ['selector'=>'datatable-all', "user"=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="blog_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Blog
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.blogPostTable", ['selector'=>'datatable-all', 'user'=>0])
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="blogAds_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                blogAds
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>

                <div class="m-portlet__body">
                    @include("components.admin.blogAdsListingTable", ['selector'=>'datatable-all', 'user'=>0])
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>var user_id="{{$user->id}}";</script>
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script src="{{asset('assets/js/admin/userManage/detail.js')}}"></script>
@endsection
