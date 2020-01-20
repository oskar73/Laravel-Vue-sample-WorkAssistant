@extends('layouts.master')

@section('title', 'Getting Started New Website')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/template/preview.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/slider.min.css')}}">
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link">
                    <span class="m-nav__link-text">User Dashboard</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Setup My New Website</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="tw-w-full tw-shadow tw-bg-gray-200">
                <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                    <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-between tw-items-center">
                        <a class="hover:tw-text-gray-900" style="text-decoration: none">Setup New Website</a>
                    </h5>
                </div>
            </div>
            <div class="tw-bg-white tw-mb-4 sidebar-tab">
                <ul class="sortable tw-pt-2 tw-py-3 sidebar-tab-ul">
                    <li class="tab-item">
                        <a class="tab-link tab-active tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#package" href="#" data-step="1" style="text-decoration: none">
                            1. Package Choice for Website
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#name" href="#" data-step="2" style="text-decoration: none">
                            2. Choose Name
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#domain" href="#" data-step="3" style="text-decoration: none">
                            3. Choose Domain
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#template" href="#" data-step="4" style="text-decoration: none">
                            4. Choose Template
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#module" href="#" data-step="5" style="text-decoration: none">
                            5. Choose Apps
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#setting" href="#" data-step="6" style="text-decoration: none">
                            6. Basic Setting
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group !tw-text-[#0574bf] hover:!tw-text-[#0574bfcc] tw-px-4 sm:tw-px-6 tab_step_btn"
                           data-area="#review" href="#" data-step="7" style="text-decoration: none">
                            Review Details
                            <div class="check_mark_area"></div>
                        </a>
                    </li>
                </ul>
            </div>
            <div><span class="progress_percentage">0</span>% completed</div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped bg-success progress_bar" role="progressbar"
                     style="width: 0%">
                </div>
            </div>
        </div>
        <div class="col-lg-10 col-md-8">
            <x-layout.portlet active="1" id="widget_area" withHead="0">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200 tw-relative">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-center tw-items-center">
                                    Package Choice for Website
                                </h5>
                            </div>
                            <div class="tw-mb-8 tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                                 style="text-decoration: none">
                                <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                    <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                        {{ json_decode($package->item)->name }}
                                    </h5>
                                </div>
                            </div>
                            <i class='fa fa-check tw-border-2 tw-border-green-500 tw-rounded-full tw-p-2 tw-text-green-500 tw-absolute tw-right-2 tw-bottom-2'></i>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200 tw-relative">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-center tw-items-center">
                                    Choose Name
                                </h5>
                            </div>
                            <div data-area="#name" data-step="2"
                                 class="tab_step_btn tw-mb-8 tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                                 style="text-decoration: none">
                                <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                    <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                        {{ $progress->data['name'] ?? 'Click to Choose Name' }}
                                    </h5>
                                </div>
                            </div>
                            @if (isset($progress->step) && $progress->step > 2)
                                <i class='fa fa-check tw-border-2 tw-border-green-500 tw-rounded-full tw-p-2 tw-text-green-500 tw-absolute tw-right-2 tw-bottom-2'></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200 tw-relative">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-center tw-items-center">
                                    Choose Domain
                                </h5>
                            </div>
                            <div data-area="#domain" data-step="3"
                                 class="tab_step_btn tw-mb-8 tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                                 style="text-decoration: none">
                                <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                    @if (isset($progress->step) && $progress->step > 3)
                                        <div class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">{{ $progress->data['subdomain'].'.'.optional(option("ssh", []))['root_domain'] }}</div>

                                        @if ($progress->data['domain_type'] === 'subdomain')
                                            <div class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center">
                                                No domain name added
                                            </div>
                                        @else
                                            <div class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">{{ $progress->data['domain'] }}</div>
                                        @endif
                                    @else
                                        <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                            Click to Choose Domain</h5>
                                    @endif
                                </div>
                            </div>
                            @if (isset($progress->step) && $progress->step > 3)
                                <i class='fa fa-check tw-border-2 tw-border-green-500 tw-rounded-full tw-p-2 tw-text-green-500 tw-absolute tw-right-2 tw-bottom-2'></i>
                            @elseif(!isset($progress->step) || $progress->step != 3)
                                <i class='fa fa-lock tw-p-2 tw-text-red-500 tw-absolute tw-left-2 tw-bottom-2'></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200 tw-relative">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-center tw-items-center">
                                    Choose Template
                                </h5>
                            </div>
                        </div>
                        <div data-area="#template" data-step="4"
                             class="tab_step_btn tw-mb-8 tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                             style="text-decoration: none">
                            @if($template)
                                <div class="tw-mx-auto tw-p-8" style="max-width: 240px">
                                    <figure data-href="{{$template->getFirstMediaUrl("preview")}}"
                                            class="w-100 progressive replace m-0">
                                        <img src="{{$template->getFirstMediaUrl("preview","thumb")}}"
                                             alt="{{$template->name}}" class="preview img-full" />
                                    </figure>
                                </div>
                            @else
                                <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                    <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                        Click to Choose Template</h5>
                                </div>
                            @endif
                            @if(isset($progress->step) && $progress->step > 4)
                                <i class='fa fa-check tw-border-2 tw-border-green-500 tw-rounded-full tw-p-2 tw-text-green-500 tw-absolute tw-right-4 tw-bottom-4'></i>
                            @elseif(!isset($progress->step) || $progress->step != 4)
                                <i class='fa fa-lock tw-p-2 tw-text-red-500 tw-absolute tw-left-2 tw-bottom-2'></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200 tw-relative">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-center tw-items-center">
                                    Choose Apps
                                </h5>
                            </div>
                        </div>
                        <div data-area="#module" data-step="5"
                             class="tab_step_btn tw-mb-8 tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                             style="text-decoration: none">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                    Click to Choose Apps</h5>
                            </div>
                            @if(isset($progress->step) && $progress->step > 5)
                                <i class='fa fa-check tw-border-2 tw-border-green-500 tw-rounded-full tw-p-2 tw-text-green-500 tw-absolute tw-right-4 tw-bottom-4'></i>
                            @elseif(!isset($progress->step) || $progress->step != 5)
                                <i class='fa fa-lock tw-p-2 tw-text-red-500 tw-absolute tw-left-2 tw-bottom-2'></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200 tw-relative">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-center tw-items-center">
                                    Basic Setting
                                </h5>
                            </div>
                        </div>
                        <div data-area="#setting" data-step="6"
                             class="tab_step_btn tw-mb-8 tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                             style="text-decoration: none">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                    Basic Setting</h5>
                            </div>
                            @if(isset($progress->step) && $progress->step > 6)
                                <i class='fa fa-check tw-border-2 tw-border-green-500 tw-rounded-full tw-p-2 tw-text-green-500 tw-absolute tw-right-4 tw-bottom-4'></i>
                            @elseif(!isset($progress->step) || $progress->step != 6)
                                <i class='fa fa-lock tw-p-2 tw-text-red-500 tw-absolute tw-left-2 tw-bottom-2'></i>
                            @endif
                        </div>
                    </div>
                </div>
            </x-layout.portlet>

            <x-layout.portlet active="0" id="name_area" label="Choose Website Name">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <x-form.input label="Name" name="name" value="{{ $progress->data['name'] ?? '' }}" />
                        <p class="mt-3">※ You can change it later if needed.</p>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#package"
                               href="#/package">Previous</a>
                            <a class="btn btn-outline-success m-btn--custom name_area_next" href="#">Next – Choose
                                Domain</a>
                        </div>
                    </div>
                </div>
            </x-layout.portlet>

            <x-layout.portletFrame active="0" id="domain_area">
                <x-layout.portletHead
                        label="Choose Domain: <span class='chosen_domain_name'></span>"></x-layout.portletHead>
                <div class="m-portlet__body">
                    <div class="container">
                        <div class="domain_input_section">
                            <div class="form-group tw-max-w-lg">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">https://</span>
                                    </div>
                                    <input type="text" class="form-control m-input text-right" name="subdomain"
                                           id="subdomain" placeholder="Enter subdomain name" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text bizinasite_domain">.{{optional(option("ssh"))['root_domain']}}</span>
                                        <a href="javascript:void(0);" class="btn btn-info" id="sub_domain_btn">
                                            Check Availability
                                        </a>
                                    </div>
                                </div>
                                <div class="form-control-feedback error-subdomain"></div>
                            </div>

                            <div class="box_area mb-5">
                                <p>
                                    Please choose a subdomain to make sure it is available. This will be used for you to
                                    be able to review your website as you build it. It will also be used if you choose
                                    not to hook up a domain name.
                                </p>
                            </div>
                        </div>

                        <div class="domain_type_section" style="display: none">
                            <div class="mb-5">
                                <button id="btn_domain_hosted" data-value="hosted" data-area="h_domain_type"
                                        class="btn btn-outline-info domain-connect-btn mb-2">Register a New Domain Name
                                </button>
                                <button id="btn_domain_connected" data-value="connected" data-area="c_domain_type"
                                        class="btn btn-outline-info domain-connect-btn mb-2">Connect a Current Domain
                                    Name
                                </button>
                                <button id="btn_domain_subdomain" data-value="subdomain" data-area="s_domain_type"
                                        class="btn btn-outline-info domain-connect-btn mb-2">Do Not Connect a Domain
                                    Name
                                </button>
                            </div>

                            <div class="domain_area h_domain_type d-none">
                                <div class="box_area mb-5">
                                    <p>
                                        If you have chosen a plan with a free domain registraton for a year, then this
                                        frst-year fee will already be paid. Please note that afer the frst year, you
                                        will need to pay for all additonal years.
                                    </p>
                                </div>
                                <div class="text-right">
                                    <a href="#" class="btn btn-success p-2 mb-1 reload_domain_btn" title="refresh"><i
                                                class="flaticon-refresh"> </i></a>
                                    <a href="{{route('user.domain.search')}}" class="btn btn-success p-2 mb-1"
                                       target="_blank">Purchase New Domain</a>
                                </div>
                                <div class="purchased_domain_append">
                                    <x-layoutItems.loading />
                                </div>
                            </div>
                            <div class="domain_area c_domain_type d-none">
                                <div class="box_area mb-5">
                                    <p>Before adding your custom domain, ensure that it is pointing to our server by
                                        following these steps:</p>
                                    <p>On your domain registrar, create an A RECORD</p>
                                    <p>Point the A record to the following IP address 23.22.168.33</p>
                                </div>
                                <div class="form-group tw-max-w-lg">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">https://</span>
                                        </div>
                                        <input type="text" class="form-control m-input" name="connect_domain"
                                               id="connect_domain" placeholder="Connect custom domain">
                                        <div class="input-group-append">
                                            <a href="javascript:void(0);" class="btn btn-info" id="connect_domain_btn">
                                                Connect
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-control-feedback error-connect_domain"></div>
                                </div>
                                <div class="connected_domain_append">
                                    <x-layoutItems.loading />
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#name" href="#/name">Previous</a>
                            <a class="btn btn-outline-success m-btn--custom domain_area_next" href="#">Next – Choose
                                Template</a>
                        </div>
                    </div>
                </div>
            </x-layout.portletFrame>

            <x-layout.portlet active="0" id="template_area" label="Choose Template">
                <div class="tw-text-lg tw-font-bold">Chosen Template</div>
                <div class="chosen_template_result mb-4"></div>
                <div class="tw-w-full tw-mx-auto tw-flex tw-max-w-[1440px]">
                    <div class="template-categories pt-2">
                        <form class="" action="" method="post" enctype="multipart/form-data">
                            <div class="bz-input-base">
                                <input class="form-control" id="keyword" type="text" name="keyword"
                                       placeholder="Search..." autocomplete="off">
                                <i class="fas fa-search"></i>
                            </div>
                        </form>

                        <div class="filter-widget mb-0 mt-2 filter-box tw-mt-4">
                            <p class="view_by_txt mb-3">Filter by</p>
                            <div class="fw-size-choose fw-filter-choose">
                                <div class="sc-item">
                                    <input type="radio" name="filterBy" id="sort-featured" value="featured" checked>
                                    <label for="sort-featured"><span>Featured</span></label>
                                </div>
                                <div class="sc-item">
                                    <input type="radio" name="filterBy" id="sort-new" value="new">
                                    <label for="sort-new"><span>New</span></label>
                                </div>
                                <div class="sc-item">
                                    <input type="radio" name="filterBy" id="sort-popular" value="popular">
                                    <label for="sort-popular"><span>Most Popular</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-widget">
                            <p class="view_by_txt">Filter by Category</p>
                            <ul class="category-menu">
                                <li class="all"><a href="#/all">All Categories</a></li>
                                @foreach($templateCategories as $key=>$category)
                                    <li class="{{$category->slug}}">
                                        <a href="#/{{$category->slug}}">{{$category->name}} </a>
                                        @if($category->approvedSubCategories->count()!==0)
                                            <ul class="sub-menu">
                                                @foreach($category->approvedSubCategories as $subcat)
                                                    <li class="{{$subcat->slug}}">
                                                        <a href="#/{{$category->slug}}/{{$subcat->slug}}">{{$subcat->name}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="tw-w-full tw-p-3">
                        <div class="templates_result">
                            <div class="tw-text-center tw-min-h-28 tw-pt-6"><i class="fa fa-spinner fa-spin fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#domain"
                       href="#/domain">Previous</a>
                    <a class="btn btn-outline-success m-btn--custom template_area_next" href="#">Next – Choose Apps</a>
                </div>
            </x-layout.portlet>

            <x-layout.portletFrame active="0" id="module_area">
                @if(!(empty(array_diff($module_wishes, $module_recommended)) && empty(array_diff($module_recommended, $module_wishes))) && count($module_recommended))
                    <div class="custom_alert" id="recommended_alert">
                        <div class="custom_alert_bell">
                            <i class="flaticon-bell-1"></i>
                        </div>
                        <div class="title border-bottom">Recommended Apps</div>
                        <div class="content pt-2 tw-flex tw-justify-between tw-items-center tw-gap-2">
                            <p>Please note your chosen apps are different than the recommended apps for this package. If
                                you would like to update to the recommended apps, please click here.</p>
                            <button class="btn btn-outline-success m-btn--custom" id="use_recommended_btn">Use
                                recommended apps
                            </button>
                        </div>
                    </div>
                @endif
                <x-layout.portletHead label="Choose Apps">
                    <div class="text-white">
                        Total Available: <span class="total_module_count">0</span>, Featured: <span
                                class="total_fmodule_count">0</span>
                    </div>
                </x-layout.portletHead>
                <div class="tw-w-full tw-flex tw-flex-col tw-py-2 tw-px-8">
                    <div class="tw-text-lg tw-font-bold">Chosen Apps</div>
                    <div class="owl-carousel" id="chosen_app_gallery"></div>
                </div>
                <div class="m-portlet__body">
                    <div class="tw-w-full tw-mx-auto tw-flex tw-max-w-[1440px]">
                        <div class="tw-w-80 tw-pt-3">
                            <form class="" action="" method="post" enctype="multipart/form-data">
                                <div class="bz-input-base">
                                    <input class="form-control" id="keyword" type="text" name="keyword"
                                           placeholder="Search..." autocomplete="off">
                                    <i class="fas fa-search"></i>
                                </div>
                            </form>

                            <div class="filter-widget mb-0 filter-box tw-mt-4">
                                <p class="view_by_txt mb-2">Filter by</p>
                                <div class="fw-size-choose fw-filter-choose">
                                    <div class="sc-item">
                                        <input type="radio" name="filterBy" id="sort-featured" value="featured" checked>
                                        <label for="sort-featured"><span>Featured</span></label>
                                    </div>
                                    <div class="sc-item">
                                        <input type="radio" name="filterBy" id="sort-new" value="new">
                                        <label for="sort-new"><span>New</span></label>
                                    </div>
                                    <div class="sc-item">
                                        <input type="radio" name="filterBy" id="sort-popular" value="popular">
                                        <label for="sort-popular"><span>Most Popular</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-widget">
                                <p class="view_by_txt">Filter by Category</p>
                                <ul class="category-menu">
                                    <li class="all"><a href="#/all">All Categories</a></li>
                                    @foreach($moduleCategories as $key=>$category)
                                        <li class="{{$category->slug}}">
                                            <a href="#/{{$category->slug}}">{{$category->name}}</a>
                                            @if($category->approvedSubCategories->count()!==0)
                                                <ul class="sub-menu">
                                                    @foreach($category->approvedSubCategories as $subcat)
                                                        <li class="{{$subcat->slug}}">
                                                            <a href="#/{{$category->slug}}/{{$subcat->slug}}">{{$subcat->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="tw-w-full tw-p-3">
                            <div class="modules_result">
                                <div class="tw-text-center tw-min-h-28 tw-pt-6"><i
                                            class="fa fa-spinner fa-spin fa-3x"></i></div>
                            </div>
                        </div>
                        <div class="tw-w-96 tw-pt-3">
                            <div class="tw-w-full tw-flex tw-flex-col tw-gap-4">
                                <div class="tw-w-full tw-flex tw-flex-col tw-gap-2">
                                    <h6 class="tw-text-center tw-text-lg tw-font-semibold">My Chosen Apps</h6>
                                    <div class="selected_apps"></div>
                                </div>
                                <div class="tw-w-full tw-flex tw-flex-col tw-gap-2">
                                    <h6 class="tw-text-center tw-text-lg tw-font-semibold">Available Apps</h6>
                                    <div class="available_apps"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#template" href="#/template">Previous</a>
                        <a class="btn btn-outline-success m-btn--custom module_area_next" href="#">Next – Choose Basic
                            Setting</a>
                    </div>
                </div>
            </x-layout.portletFrame>

            <x-layout.portlet active="0" id="change_package_area" label="Change/Upgrade Package">
                <div class="tw-w-full tw-mx-auto tw-flex tw-max-w-[1440px] tw-mb-4">
                    <div class="tw-w-full tw-px-4">
                        <div class="tw-flex tw-w-full tw-justify-between tw-items-center">
                            <span class="tw-text-lg tw-font-bold" id="package_label">Available Packages For Your Chosen Number Of Apps</span>
                            <span class="btn btn-outline-success" id="switch_package_visibility">View All</span>
                        </div>
                        <div class="package_result">
                            <div class="tw-text-center tw-min-h-28 tw-pt-6"><i
                                        class="fa fa-spinner fa-spin fa-3x"></i></div>
                        </div>
                    </div>
                    <div class="tw-w-96 tw-pt-3">
                        <div class="tw-w-full tw-flex tw-flex-col tw-gap-4">
                            <div class="tw-w-full tw-flex tw-flex-col tw-gap-2">
                                <h6 class="tw-text-center tw-text-lg tw-font-semibold">My Chosen Apps</h6>
                                <div class="selected_apps"></div>
                            </div>
                            <div class="tw-w-full tw-flex tw-flex-col tw-gap-2">
                                <h6 class="tw-text-center tw-text-lg tw-font-semibold">Available Apps</h6>
                                <div class="available_apps"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-layout.portlet>

            <x-layout.portlet active="0" id="setting_area" label="Basic Setting">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="form-group">
                            <label for="status">
                                Status
                                <i class="fa fa-info-circle tooltip_3" title="Status"></i>
                            </label>
                            <select name="status" id="status" class="m-bootstrap-select selectpicker w-100">
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="maintenance">Maintenance Mode</option>
                            </select>
                            <div class="form-control-feedback error-status"></div>
                        </div>
                        <div class="mt-3">
                            <div class="mt-3 custom_credential">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="storage" class="form-control-label">Storage Limit (GB)</label>
                                            <input type="text" class="form-control m-input--square" name="storage"
                                                   id="storage" readonly>
                                            <div class="form-control-feedback error-storage"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="page_limit" class="form-control-label">Additional Page
                                                Limit</label>
                                            <input type="text" class="form-control m-input--square" name="page_limit"
                                                   id="page_limit" readonly>
                                            <div class="form-control-feedback error-page_limit"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Admin Email</label>
                                            <input type="text" class="form-control m-input--square" name="email"
                                                   id="email">
                                            <div class="form-control-feedback error-email"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-control-label">Admin Password</label>
                                            <input type="password" class="form-control m-input--square" name="password"
                                                   id="password">
                                            <div class="form-control-feedback error-password"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="credentials" id="credentials"> use same email and password
                                with this account.
                                <span></span>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between mt-5">
                            <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#module" href="#/module">Previous</a>
                            <a class="btn btn-outline-success m-btn--custom setting_area_next" href="#">Next – Review
                                Details</a>
                        </div>
                    </div>
                </div>
            </x-layout.portlet>
            <x-layout.portlet active="0" id="review_area" label="Review Detail">
                <x-form.form action="{{route('user.website.getting.submit')}}">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <h3>New Website Details</h3>
                                <div class=" table-responsive">
                                    <table class="table table-hover table-bordered table-item-center table-layout-fixed">
                                        <tr>
                                            <td>Domain</td>
                                            <td><span class="review_domain"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Website Name</td>
                                            <td><span class="review_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Modules</td>
                                            <td><span class="review_module"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Storage Limit</td>
                                            <td><span class="review_storage"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Additional Pages</td>
                                            <td><span class="review_page"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Template</td>
                                            <td><span class="review_template"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td><span class="review_status"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Admin Credentials</td>
                                            <td><span class="review_credentials"></span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#setting"
                                       href="#/setting">Previous</a>
                                    <button type="submit" class="btn btn-outline-success m-btn--custom smtBtn">Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-form.form>
            </x-layout.portlet>
            <x-layout.portlet active="0" id="launch_area" label="Website Status">
                <div class="launch_append">
                </div>
            </x-layout.portlet>
        </div>
    </div>
    <div class="template_preview_window"></div>

    {{-- Module Section Modal --}}
    <div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div id="video_content" class="modal-content"></div>
        </div>
    </div>

    <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div id="view_content" class="modal-content"></div>
        </div>
    </div>

    <div class="modal fade" id="apps_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div id="apps_content" class="modal-content"></div>
        </div>
    </div>

    <div class="modal fade" id="package_confirm_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div id="package_confirm_content" class="modal-content">Do you really want to switch to this package?</div>
        </div>
    </div>
@endsection
@section('script')
    <script>
      var root_domain = "{{optional(option("ssh"))['root_domain']}}"
      var module_array = @JSON($moduleJson);
      var module_wishes = @JSON($module_wishes);
      var module_recommended = @JSON($module_recommended);
      var currentProgress = @JSON($progress);
      var currentPackage = @JSON($package);
      var userTypeUrl = '/account' //Need to set as we are sharing same started.js for both admin and user
    </script>
    <script src="{{asset('assets/js/slider.min.js')}}"></script>
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{asset('assets/js/user/website/resume.js')}}"></script>
@endsection
