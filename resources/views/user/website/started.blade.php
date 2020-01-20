@extends('layouts.master')

@section('title', 'Getting Started New Website')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Website', 'Getting Started']" :menuLinks="[route('user.website.index')]" />
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="sidebar-tab">
                <ul class="sidebar-tab-ul">
                    <li class="tab-item">
                        <a class="tab-link tab-active tab_step_btn" data-area="#package" href="#" data-step="1">
                            1. Package Choice for Website <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tab_step_btn" data-area="#name" href="#" data-step="2">
                            2. Choose Name <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tab_step_btn" data-area="#template" href="#" data-step="3">
                            3. Choose Template <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tab_step_btn" data-area="#domain" href="#" data-step="4">
                            4. Pick Domain <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tab_step_btn" data-area="#module" href="#" data-step="5">
                            5. Choose Modules <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tab_step_btn" data-area="#setting" href="#" data-step="6">
                            6. Basic Setting <div class="check_mark_area"></div>
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link tab_step_btn" data-area="#review" href="#" data-step="7">
                            Review Details <div class="check_mark_area"></div>
                        </a>
                    </li>
                </ul>
                <div><span class="progress_percentage">0</span>% completed</div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success progress_bar" role="progressbar"
                         style="width: 0%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-10 col-md-8">

            <x-layout.portlet active="1" id="package_area" label="Package Choice for Website">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-item-center">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    Package/Ready Made Biz Name
                                </th>
                                <th class="text-center">
                                    Quantity
                                </th>
                                <th class="text-center">
                                    Date Purchased
                                </th>
                                <th class="text-center">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                                @foreach($package->progresses as $progress)
                                <tr>
                                    <td>{{$progress->data['name']?? ''}}</td>
                                    <td></td>
                                    <td>{{$package->created_at->toDateString()}}</td>
                                    <td>
                                        <a class="btn btn-outline-info m-btn--sm tab-link package_resume_btn"
                                           data-area="#name"
                                           href="#/name"
                                           data-id="{{$package->id}}"
                                           data-module="{{$package->module}}"
                                           data-fmodule="{{$package->featured_module}}"
                                           data-storage="{{$package->storage}}"
                                           data-page="{{$package->page}}"
                                           data-modules="{{$package->modules}}"
                                           data-data="{{json_encode($progress->data)}}"
                                           data-step="{{$progress->step}}"
                                           data-resume="{{$progress->id}}"
                                           data-sltemplate="{{ $template ? $template->id : 0 }}"
                                        >Resume</a>
                                    </td>
                                </tr>
                                @endforeach
                                @if($package->newCount)
                                    <tr>
                                        <td>{{$package->getName()}}</td>
                                        <td>
                                            @if ($package->newCount < 0)
                                            Unlimited
                                            @else
                                            {{$package->newCount}}
                                            @endif
                                        </td>
                                        <td>{{$package->created_at->toDateString()}}</td>
                                        <td>
                                            <a class="btn btn-outline-success m-btn--sm tab-link package_start_btn"
                                               data-area="#name"
                                               href="#/name"
                                               data-id="{{$package->id}}"
                                               data-module="{{$package->module}}"
                                               data-fmodule="{{$package->featured_module}}"
                                               data-storage="{{$package->storage}}"
                                               data-page="{{$package->page}}"
                                               data-modules="{{$package->modules}}"
                                               data-sltemplate="{{ $template ? $template->id : 0 }}"
                                            >Start</a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4">No item</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="{{route('package.index')}}" class="btn btn-outline-info m-btn--sm mb-2" target="_blank">Purchase New</a>
                </div>
            </x-layout.portlet>

            <x-layout.portlet active="0" id="name_area" label="Choose Website Name">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <x-form.input label="Name" name="name" />
                        <p class="mt-3">※ You can change it later if needed.</p>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#package" href="#/package">Previous</a>
                            <a class="btn btn-outline-success m-btn--custom name_area_next" href="#" >Next</a>
                        </div>
                    </div>
                </div>
            </x-layout.portlet>

            <x-layout.portlet active="0" id="template_area" label="Choose Template">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#name" href="#/name">Previous</a>
                        <a class="btn btn-outline-success m-btn--custom template_area_next" href="#">Next</a>
                    </div>
                    <hr>
                    <div class="d-flex w-100 justify-content-between">
                        <div class="form-group">
                            <input name="template_search" id="template_search" class="form-control" placeholder="Type keyword..." autocomplete="off">
                        </div>
                        <div class="form-group w-200px">
                            <select name="category" id="category" class="selectpicker" data-live-search="true" data-width="100%">
                                <option value="all" selected>All categories</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->slug}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-option--sm d-flex align-items-center">
                            <label for="template_filter">Filter by:</label>
                            <label class="m-option h-cursor">
                                    <span class="m-option__control">
                                        <span class="m-radio m-radio--brand m-radio--check-bold">
                                            <input type="radio" name="template_filter" value="featured" checked>
                                            <span></span>
                                        </span>
                                    </span>
                                <span class="m-option__label">
                                        <span class="m-option__head">
                                            <span class="m-option__title">
                                                Featured
                                            </span>
                                        </span>
                                    </span>
                            </label>
                            <label class="m-option h-cursor">
                                    <span class="m-option__control">
                                        <span class="m-radio m-radio--brand m-radio--check-bold">
                                            <input type="radio" name="template_filter" value="new">
                                            <span></span>
                                        </span>
                                    </span>
                                <span class="m-option__label">
                                        <span class="m-option__head">
                                            <span class="m-option__title">
                                                New
                                            </span>
                                        </span>
                                    </span>
                            </label>
                            <label class="m-option h-cursor">
                                    <span class="m-option__control">
                                        <span class="m-radio m-radio--brand m-radio--check-bold">
                                            <input type="radio" name="template_filter" value="popular">
                                            <span></span>
                                        </span>
                                    </span>
                                <span class="m-option__label">
                                        <span class="m-option__head">
                                            <span class="m-option__title">
                                                Popular
                                            </span>
                                        </span>
                                    </span>
                            </label>
                            <label class="m-option h-cursor">
                                    <span class="m-option__control">
                                        <span class="m-radio m-radio--brand m-radio--check-bold">
                                            <input type="radio" name="template_filter" value="popular">
                                            <span></span>
                                        </span>
                                    </span>
                                <span class="m-option__label">
                                        <span class="m-option__head">
                                            <span class="m-option__title">
                                                Liked
                                            </span>
                                        </span>
                                    </span>
                            </label>
                        </div>
{{--                        <div class="selected_template">--}}
{{--                        </div>--}}
                    </div>
                    <hr />
                    <div class="templates_container container custom-scroll-h">
                        <x-layoutItems.loading />
                    </div>
                </div>
            </x-layout.portlet>
                <x-layout.portletFrame active="0" id="domain_area" >
                    <x-layout.portletHead label="Choose Domain: <span class='chosen_domain_name'></span>"></x-layout.portletHead>
                    <div class="m-portlet__body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="domain_type" value="hosted" data-area="h_domain_type" checked>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Register
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="domain_type" value="connected" data-area="c_domain_type">
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Connect
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="domain_type" value="subdomain" data-area="s_domain_type" >
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Subdomain
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-8">

                                    <div class="domain_area h_domain_type">
                                        <div class="box_area mb-5">
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                <br>
                                                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                <br>
                                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                                <br>
                                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <a href="#" class="btn btn-success p-2 mb-1 reload_domain_btn" title="refresh"><i class="flaticon-refresh"> </i></a>
                                            <a href="{{route('user.domain.search')}}" class="btn btn-success p-2 mb-1" target="_blank">Purchase New Domain</a>
                                        </div>
                                        <div class="purchased_domain_append">
                                            <x-layoutItems.loading />
                                        </div>
                                    </div>
                                    <div class="domain_area c_domain_type d-none">

                                        <div class="box_area mb-5">
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                <br>
                                                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                <br>
                                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                                <br>
                                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                        <div class="form-group tw-max-w-lg">
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">https://</span>
                                                </div>
                                                <input type="text" class="form-control m-input" name="connect_domain" id="connect_domain" placeholder="Connect custom domain">
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
                                    <div class="domain_area s_domain_type d-none">

                                        <div class="box_area mb-5">
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                <br>
                                                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                                <br>
                                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                                <br>
                                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>

                                        <div class="form-group tw-max-w-lg">
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">https://</span>
                                                </div>
                                                <input type="text" class="form-control m-input text-right" name="subdomain" id="subdomain" placeholder="Enter subdomain name" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text bizinasite_domain">.{{optional(option("ssh"))['root_domain']}}</span>
                                                    <a href="javascript:void(0);" class="btn btn-info" id="sub_domain_btn">
                                                        Check Availability
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="form-control-feedback error-subdomain"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-5">
                                <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#template" href="#/template">Previous</a>
                                <a class="btn btn-outline-success m-btn--custom domain_area_next" href="#">Next</a>
                            </div>
                        </div>
                    </div>
                </x-layout.portletFrame>

            <x-layout.portletFrame active="0" id="module_area">
                <x-layout.portletHead label="Choose Modules">
                    <div class="text-white">
                        Total Available: <span class="total_module_count">0</span>, Featured: <span class="total_fmodule_count">0</span>
                    </div>
                </x-layout.portletHead>
                <div class="m-portlet__body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" name="module_search" id="module_search" class="form-control" placeholder="Type keyword...">
                                </div>
                                <div class="form-group m-option--sm">
                                    <label for="template_filter">Filter by:</label>
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="module_filter" value="featured" checked>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Featured
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="module_filter" value="new">
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    New
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="module_filter" value="popular">
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Popular
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                    <label class="m-option h-cursor">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="module_filter" value="popular">
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Liked
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group mt-3 fs-16">
                                   <b> Total Available: <span class="total_module_count">0</span></b> <br>
                                    <b> Featured: <span class="total_fmodule_count">0</span></b>
                                </div>
                            </div>
                            <div class="col-lg-10 mb-3">
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <p>List of Modules (<span class="module_count">0</span>)</p>
                                        <div class="modules_append">
                                            <x-layoutItems.loading />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <p>About Module</p>
                                        <div class="about_module_append custom-scroll-h p-3">

                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <p>Selected Modules</p>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-item-center table-hover">
                                                <thead>
                                                <tr>
                                                    <td class="text-center">Featured</td>
                                                    <td class="text-center">Module Name</td>
                                                    <td class="text-center">Unselect</td>
                                                </tr>
                                                </thead>
                                                <tbody class="selected_modules_append">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        {{-- <p>Lists of Features</p> --}}
                                        <div class="list_features_append">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 mt-3">

                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#domain" href="#/domain">Previous</a>
                            <a class="btn btn-outline-success m-btn--custom module_area_next" href="#">Next</a>
                        </div>
                    </div>
                </div>
            </x-layout.portletFrame>

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
                                                <input type="text" class="form-control m-input--square" name="storage" id="storage" readonly>
                                                <div class="form-control-feedback error-storage"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="page_limit" class="form-control-label">Additional Page Limit</label>
                                                <input type="text" class="form-control m-input--square" name="page_limit" id="page_limit" readonly>
                                                <div class="form-control-feedback error-page_limit"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-control-label">Admin Email</label>
                                                <input type="text" class="form-control m-input--square" name="email" id="email">
                                                <div class="form-control-feedback error-email"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password" class="form-control-label">Admin Password</label>
                                                <input type="password" class="form-control m-input--square" name="password" id="password">
                                                <div class="form-control-feedback error-password"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="m-checkbox m-checkbox--state-success">
                                    <input type="checkbox" name="credentials" id="credentials" > use same email and password with this account.
                                    <span></span>
                                </label>
                            </div>
                            <div class="d-flex justify-content-between mt-5">
                                <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#module" href="#/module">Previous</a>
                                <a class="btn btn-outline-success m-btn--custom setting_area_next" href="#">Next</a>
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
                                    <a class="btn btn-outline-info m-btn--custom tab-link" data-area="#setting" href="#/setting">Previous</a>
                                    <button type="submit" class="btn btn-outline-success m-btn--custom smtBtn">Launch!</button>
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
@endsection
@section('script')
    <script>
        var root_domain = "{{optional(option("ssh"))['root_domain']}}";
        var module_array = @JSON($moduleJson);
        var module_wishes = @JSON($module_wishes);
        var userTypeUrl = '/account' //Need to set as we are sharing same started.js for both admin and user
    </script>
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{asset('assets/js/user/website/started.js')}}"></script>
@endsection
