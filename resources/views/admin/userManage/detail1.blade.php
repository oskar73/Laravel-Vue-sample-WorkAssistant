@extends('layouts.master')

@section('title', 'User Management')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['User Management', 'User Detail']" :menuLinks="[route('admin.userManage.index')]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.userManage.index')}}" label="Back"/>
        <x-form.a link="{{route('admin.userManage.edit', $user->id)}}" label="Edit" type="success"/>
    </div>
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile " id="profile_area" m-portlet="true">
        <div class="m-portlet__head bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Profile
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 text-center">
                                <img id="avatar" class="image_upload_output maxw-100 wh-200px m-auto" src="{{$user->avatar()}}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-flex justify-content-between mb-0">
                                <span>PIN Number:</span><span> {{$user->pin_number}}</span>
                            </div>
                            <hr class="mt-0">
                            <div class="form-group d-flex justify-content-between mb-0">
                                <span>Name:</span><span> {{$user->name}}</span>
                            </div>
                            <hr class="mt-0">
                            <div class="form-group d-flex justify-content-between mb-0">
                                <span>Username:</span><span>  {{'@' . $user->username}}</span>
                            </div>
                            <hr class="mt-0">
                            <div class="form-group d-flex justify-content-between mb-0">
                                <span>Email address:
                                    <i class="fa fa-check-circle @if($user->email_verified_at) text-success @endif" title="@if($user->email_verified_at) Verified @else Unverified @endif"></i>  </span>
                                <span> {{$user->email}}</span>
                            </div>
                            <hr  class="mt-0">
                            <div class="" x-data={show:false}>
                                <a href="#" class="underline" x-show="!show" x-on:click="show=!show">Show more</a>
                                <div class="show_more_area" x-show="show">
                                    <div class="form-group d-flex justify-content-between mb-0">
                                        <span>Phone:</span>
                                        <span> {{$user->phone_no}}</span>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group d-flex justify-content-between mb-0">
                                        <span>Address:</span>
                                        <span> {{$user->address1}}</span>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group d-flex justify-content-between mb-0">
                                        <span>Timezone:</span>
                                        <span> {{$user->timezone}}</span>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group d-flex justify-content-between mb-0">
                                        <span>Time Format:</span>
                                        <span> {{$user->timeformat}}</span>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="form-group d-flex justify-content-between mb-0">
                                        <span>Role:</span>
                                        <div>
                                            @if($user->hasRole('admin'))
                                                <span class="c-badge c-badge-success">Admin</span>
                                            @endif
                                            @if($user->hasRole('employee'))
                                                <span class="c-badge c-badge-success">Employee</span>
                                            @endif
                                            @if($user->hasRole('client'))
                                                <span class="c-badge c-badge-success">Client</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                </div>
                                <a href="#" class="underline"  x-show="show" x-on:click="show=!show">Show less</a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-7">
                    <ul class="list-style-none-ul row">
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#website" href="#/website">
                               <span>
                                   <i class="fa fa-layer-group"></i>
                                   Website
                               </span>
                                @if($user->websites->count())<span class="count_area">({{$user->websites->count()}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#domain" href="#/domain">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                   Domain
                               </span>
                                @if($user->domains_count)<span class="count_area">({{$user->domains_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#readyMadeBiz" href="#/readyMadeBiz">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Ready Made BIZ
                               </span>
                                @if($user->readymades_count)<span class="count_area">({{$user->readymades_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#package" href="#/package">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Package
                               </span>
                                @if($user->packages_count)<span class="count_area">({{$user->packages_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#plugin" href="#/plugin">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Plugin
                               </span>

                                @if($user->plugins_count)<span class="count_area">({{$user->plugins_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#service" href="#/service">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Service
                               </span>
                                @if($user->services_count)<span class="count_area">({{$user->services_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#lacarte" href="#/lacarte">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Lacarte
                               </span>

                                @if($user->lacartes_count)<span class="count_area">({{$user->lacartes_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#blog" href="#/blog">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Blog Posts
                               </span>

                                @if($user->posts_count)<span class="count_area">({{$user->posts_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#blogAds" href="#/blogAds">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Blog Advertisement
                               </span>
                                @if($user->blog_ads_listings_count)<span class="count_area">({{$user->blog_ads_listings_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#order" href="#/order">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Orders
                               </span>
                                @if($user->orders_count)<span class="count_area">({{$user->orders_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#subscription" href="#/subscription">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Subscriptions
                               </span>
                                @if($user->subscription_order_items_count)<span class="count_area">({{$user->subscription_order_items_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#transaction" href="#/transaction">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Transactions
                               </span>
                                @if($user->transactions_count)<span class="count_area">({{$user->transactions_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#purchase_followup" href="#/purchase_followup">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Purchase Follow-up Forms
                               </span>
                                @if($user->purchase_followups_count)<span class="count_area">({{$user->purchase_followups_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#appointment" href="#/appointment">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Appointments
                               </span>
                                @if($user->appointments_count)<span class="count_area">({{$user->appointments_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#ticket" href="#/ticket">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Tickets
                               </span>
                                @if($user->tickets_count)<span class="count_area">({{$user->tickets_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#login" href="#/login">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Login Activity
                               </span>
                                @if($user->logins_count)<span class="count_area">({{$user->logins_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#directory_listings" href="#/directory_listings">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                Directories
                               </span>
                                @if($user->directory_listings_count)<span class="count_area">({{$user->directory_listings_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#portfolios" href="#/portfolios">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                   Portfolios
                               </span>
                                @if($user->portfolio_count)<span class="count_area">({{$user->portfolio_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#designs" href="#/designs">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                   Designs
                               </span>
                                @if($user->designs_count)<span class="count_area">({{$user->designs_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#coupons" href="#/coupons">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                   Coupons
                               </span>
                                @if($user->coupons_count)<span class="count_area">({{$user->coupons_count}})</span> @endif
                            </a>
                        </li>
                        <li class="tab-item col-md-4">
                            <a class=" tab-link li_a_item d-flex justify-content-between" data-area="#transcripts" href="#/transcripts">
                                <span>
                                   <i class="fa fa-layer-group"></i>
                                   Transcripts
                               </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area" id="website_area"  m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Website
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.websiteTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="domain_area"  m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Domain
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.domainListTable", ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="readyMadeBiz_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Ready Made BIZ
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include("components.admin.pPackageTable", ['selector'=>'datatable-all', "user"=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="package_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Package
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include("components.admin.pPackageTable", ['selector'=>'datatable-all', "user"=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="plugin_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Plugin
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.pPluginTable", ['selector'=>'datatable-all', "user"=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="service_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Service
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.pServiceTable", ['selector'=>'datatable-all', "user"=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="lacarte_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Lacarte
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.pLacarteTable", ['selector'=>'datatable-all', "user"=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="blog_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Blog
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.blogPostTable", ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="blogAds_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Blog Advertisement
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.blogAdsListingTable", ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="order_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Orders
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include("components.admin.orderTable", ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="subscription_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Subscription
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.subscriptionTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="transaction_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Transaction
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.transactionTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="purchase_followup_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Purchase Follo-up Forms
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.formTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50"  m-portlet="true" id="appointment_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Appointments
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.appointmentTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area"  m-portlet="true" id="ticket_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Tickets
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.ticketTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area"  m-portlet="true" id="login_area">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Login Activity
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.loginTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area" id="directory_listings_area"  m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Directory Listings
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.directoryListingTable', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area" id="portfolios_area"  m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Portfolios
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area" id="designs_area"  m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Designs
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include('components.admin.graphic-designs.design-item', ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area" id="coupons_area"  m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Coupons
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @include("components.admin.couponTable", ['selector'=>'datatable-all', 'user'=>0])
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile tab_area" id="transcripts_area" m-portlet="true">
        <div class="m-portlet__head  bg-333">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text text-white">
                        Transcripts
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-angle-down  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-expand  text-white"></i>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="#" m-portlet-tool="remove" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close  text-white"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body" @if($user->transcript_id != 0) style="height: 70vh !important" @endif>
            @if($user->transcript_id != 0)
                <iframe src={{ "/admin/livechat/chatbox?transcript=true&tab=" . ($user->hasRole('admin') ? 'user' : 'guest') . "&perPage=15&page=1&item=" . $user->transcript_id }} height="100%" width="100%"></iframe>
            @else
                <div class="text-center">No converstions with  this user</div>
            @endif
        </div>
    </div>

@endsection
@section('script')
    <script>var user_id="{{$user->id}}";</script>
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script src="{{asset('assets/js/admin/userManage/detail.js')}}"></script>
@endsection
