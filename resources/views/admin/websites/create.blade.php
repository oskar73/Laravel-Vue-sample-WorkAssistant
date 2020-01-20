@extends('layouts.master')

@section('title', 'Website')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Website</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Create</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.website.list.index')}}" class="btn m-btn--square  btn-outline-info m-btn m-btn--custom">
            Back
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="sidebar-tab">
                <ul class="sidebar-tab-ul">
                    <li class="tab-item">
                        <a class="tab-link tab-active" data-area="#template" href="#/template">
                            1. Choose Template
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#domain" href="#/domain">
                            2. Choose Domain
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#module" href="#/module">
                            3. Choose Module
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#confirm" href="#/confirm">
                            4. Basic Setting & Confirm
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-10 col-md-8">

            <form action="{{route('admin.website.list.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="template_area">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    1. Choose Template
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">

                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="text-right mb-3">
                            <a class="btn m-btn--square  btn-outline-success m-btn m-btn--custom tab-link next1" data-area="#domain" href="#/domain">
                                Next
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="template">Choose Template (Don't worry, you can change it later)
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[1]}}'
                                           data-page="{{$view_name}}"
                                           data-id="1"
                                        ></i>
                                    </label>
                                    <select name="template" id="template" class="select_picker" data-live-search="true" data-width="100%">
                                        <option selected disabled hidden>Choose Template</option>
                                        @foreach($templates as $template)
                                            <option value="{{$template->id}}"
                                                    data-img="{{$template->getFirstMediaUrl('preview')}}"
                                                    data-slug="{{$template->slug}}"
                                                    data-header="{{$template->header_id}}"
                                                    data-footer="{{$template->footer_id}}"
                                            >{{$template->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-template"></div>
                                </div>
                                <div class="preview_template">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="header">Choose Header Type
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[2]}}'
                                           data-page="{{$view_name}}"
                                           data-id="2"
                                        ></i>
                                    </label>
                                    <select name="header" id="header" class="select_picker" data-live-search="true" data-width="100%">
                                        <option selected disabled hidden>Choose Header Type</option>
                                        @foreach($headers as $header)
                                            <option value="{{$header->id}}" >{{$header->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-header"></div>
                                </div>
                                <div class="form-group">
                                    <label for="footer">Choose Footer Type
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[3]}}'
                                           data-page="{{$view_name}}"
                                           data-id="3"
                                        ></i>
                                    </label>
                                    <select name="footer" id="footer" class="select_picker" data-live-search="true" data-width="100%">
                                        <option selected disabled hidden>Choose Footer Type</option>
                                        @foreach($footers as $footer)
                                            <option value="{{$footer->id}}" >{{$footer->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-footer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="domain_area">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    2. Choose Domain
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">

                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="d-flex justify-content-between mb-3">
                            <a class="btn m-btn--square  btn-outline-info m-btn m-btn--custom tab-link" data-area="#template" href="#/template">
                                Back
                            </a>
                            <a class="btn m-btn--square  btn-outline-success m-btn m-btn--custom tab-link" data-area="#module" href="#/module">
                                Next
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="m-option h-cursor">
                                    <span class="m-option__control">
                                        <span class="m-radio m-radio--brand m-radio--check-bold">
                                            <input type="radio" name="domain_type" value="subdomain" data-area="s_domain_type">
                                            <span></span>
                                        </span>
                                    </span>
                                    <span class="m-option__label">
                                        <span class="m-option__head">
                                            <span class="m-option__title">
                                                Pick from subdomain

                                                <i class="la la-info-circle tooltip_icon"
                                                   title='{{$tooltip[4]}}'
                                                   data-page="{{$view_name}}"
                                                   data-id="4"
                                                ></i>

                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-md-4">
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
                                                Pick from connected domain

                                                <i class="la la-info-circle tooltip_icon"
                                                   title='{{$tooltip[5]}}'
                                                   data-page="{{$view_name}}"
                                                   data-id="5"
                                                ></i>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="m-option h-cursor">
                                    <span class="m-option__control">
                                        <span class="m-radio m-radio--brand m-radio--check-bold">
                                            <input type="radio" name="domain_type" value="hosted" data-area="h_domain_type">
                                            <span></span>
                                        </span>
                                    </span>
                                    <span class="m-option__label">
                                        <span class="m-option__head">
                                            <span class="m-option__title">
                                                Pick from purchased domain
                                                    <i class="la la-info-circle tooltip_icon"
                                                       title='{{$tooltip[6]}}'
                                                       data-page="{{$view_name}}"
                                                       data-id="6"
                                                    ></i>

                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <div class="domain_area s_domain_type d-none p-3 mt-3 mb-3">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">https://</span>
                                            </div>
                                            <input type="text" class="form-control m-input text-right" name="subdomain" id="subdomain" placeholder="Enter domain name" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text bizinasite_domain">.{{optional(option("ssh"))['root_domain']}}</span>
                                            </div>
                                        </div>
                                        <div class="form-control-feedback error-subdomain"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="domain_area c_domain_type d-none  p-3 mt-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">https://</span>
                                                </div>
                                                <input type="text" class="form-control m-input" name="connect_domain" id="connect_domain" placeholder="Connect custom domain">
                                                <div class="input-group-append">
                                                    <a href="javascript:void(0);" class="btn btn-brand" id="submit_domain">
                                                        <i class="la la-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="form-control-feedback error-connect_domain"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="connected_domains">
                                </div>
                            </div>
                            <div class="domain_area h_domain_type d-none  p-3 mt-3 mb-3">
                                <div class="text-right">
                                    <a href="{{route('user.domain.search')}}" class="btn btn-success p-2 mb-1">Purchase New Domain</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered ajaxTable datatable">
                                        <thead>
                                        <tr>
                                            <th>Choose</th>
                                            <th>Domain Name</th>
                                            <th>Connected Status</th>
                                            <th>Registered Date</th>
                                            <th>Expire Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($domains as $domain)
                                            <tr>
                                                <td>
                                                    @if($domain->pointed==1)
                                                        <input type="radio" name="hosted_domain" value="{{$domain->id}}">
                                                    @endif
                                                </td>
                                                <td>{{$domain->name}}</td>
                                                <td>
                                                    @if($domain->pointed==1)
                                                        <span class="c-badge c-badge-success">Connected</span>
                                                    @else
                                                        <span class="c-badge c-badge-danger hover-handle" >Not Connected</span>
                                                    @endif
                                                </td>
                                                <td>{{$domain->created_at}}</td>
                                                <td>{{$domain->expired_at}}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5">None</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="module_area">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    3. Choose Module
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">

                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="d-flex justify-content-between mb-3">
                            <a class="btn m-btn--square  btn-outline-info m-btn m-btn--custom tab-link" data-area="#domain" href="#/domain">
                                Back
                            </a>
                            <a class="btn m-btn--square  btn-outline-success m-btn m-btn--custom tab-link" data-area="#confirm" href="#/confirm">
                                Next
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="module_limit" class="form-control-label">Module Limit:
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[7]}}'
                                           data-page="{{$view_name}}"
                                           data-id="7"
                                        ></i>
                                    </label>
                                    <input type="text" class="form-control m-input--square" name="module_limit" id="module_limit" value="5">
                                    <div class="form-control-feedback error-module_limit"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="featured_module_limit" class="form-control-label">Featured Module Limit:
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[8]}}'
                                           data-page="{{$view_name}}"
                                           data-id="8"
                                        ></i>
                                    </label>
                                    <input type="text" class="form-control m-input--square" name="featured_module_limit" id="featured_module_limit" value="5">
                                    <div class="form-control-feedback error-featured_module_limit"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="modules" class="form-control-label">Choose Modules:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[9]}}'
                                   data-page="{{$view_name}}"
                                   data-id="9"
                                ></i>
                            </label>
                            <select name="modules[]" id="modules" class="modules" multiple="true">
                                <option></option>
                                @foreach($modules as $module)
                                    <option value="{{$module->id}}">{{$module->name}} @if($module->featured) (Featured) @endif</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-modules"></div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="confirm_area">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    4. Basic Setting and Confirm
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">

                        </div>
                    </div>
                    <div class="m-portlet__body">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="name" class="form-control-label">
                                            Website Name:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[10]}}'
                                               data-page="{{$view_name}}"
                                               data-id="10"
                                            ></i>

                                        </label>
                                        <input type="text" class="form-control m-input--square" name="name" id="name">
                                        <div class="form-control-feedback error-name"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="storage" class="form-control-label">Storage Limit (GB):
                                                    <i class="la la-info-circle tooltip_icon"
                                                       title='{{$tooltip[11]}}'
                                                       data-page="{{$view_name}}"
                                                       data-id="11"
                                                    ></i>
                                                </label>
                                                <input type="text" class="form-control m-input--square" name="storage" id="storage" value="5">
                                                <div class="form-control-feedback error-storage"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="page" class="form-control-label">Additional Page Limit:
                                                    <i class="la la-info-circle tooltip_icon"
                                                       title='{{$tooltip[12]}}'
                                                       data-page="{{$view_name}}"
                                                       data-id="12"
                                                    ></i>
                                                </label>
                                                <input type="text" class="form-control m-input--square" name="page" id="page" value="5">
                                                <div class="form-control-feedback error-page"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_by_owner">
                                            Status By Owner
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[13]}}'
                                               data-page="{{$view_name}}"
                                               data-id="13"
                                            ></i>
                                        </label>
                                        <select name="status_by_owner" id="status_by_owner" class="m-bootstrap-select selectpicker w-100">
                                            <option value="active">Active</option>
                                            <option value="pending">Pending</option>
                                            <option value="maintenance">Maintenance Mode</option>
                                        </select>
                                        <div class="form-control-feedback error-status_by_owner"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">
                                            Status
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[14]}}'
                                               data-page="{{$view_name}}"
                                               data-id="14"
                                            ></i>

                                        </label>
                                        <select name="status" id="status" class="m-bootstrap-select selectpicker w-100">
                                            <option value="active">Active</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                        <div class="form-control-feedback error-status"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="owner" class="form-control-label"> Choose Owner:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[15]}}'
                                               data-page="{{$view_name}}"
                                               data-id="15"
                                            ></i>
                                        </label>
                                        <select name="owner" id="owner" class="select_picker" data-live-search="true" data-width="100%">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}" >{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-feedback error-owner"></div>
                                    </div>
                                    <div class="mt-3">
                                        <br>
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="credentials" id="credentials" > use same email and password with this account.
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[16]}}'
                                               data-page="{{$view_name}}"
                                               data-id="16"
                                            ></i>

                                            <span></span>
                                        </label>
                                        <div class="mt-3 custom_credential">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email" class="form-control-label">Email
                                                            <i class="la la-info-circle tooltip_icon"
                                                               title='{{$tooltip[17]}}'
                                                               data-page="{{$view_name}}"
                                                               data-id="17"
                                                            ></i>
                                                        </label>
                                                        <input type="text" class="form-control m-input--square" name="email" id="email">
                                                        <div class="form-control-feedback error-email"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password" class="form-control-label">Password
                                                            <i class="la la-info-circle tooltip_icon"
                                                               title='{{$tooltip[18]}}'
                                                               data-page="{{$view_name}}"
                                                               data-id="18"
                                                            ></i>
                                                        </label>
                                                        <input type="password" class="form-control m-input--square" name="password" id="password">
                                                        <div class="form-control-feedback error-password"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <a class="btn m-btn--square  btn-outline-info m-btn m-btn--custom tab-link" data-area="#module" href="#/module">
                                    Back
                                </a>
                                <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn m-1">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/websites/create.js')}}"></script>
@endsection
