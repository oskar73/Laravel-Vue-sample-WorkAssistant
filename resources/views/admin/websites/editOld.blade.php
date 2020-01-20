@extends('layouts.master')

@section('title', 'Websites')
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
                    <span class="m-nav__link-text">Edit</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.website.list.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Back</a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="sidebar-tab">
                <ul class="sidebar-tab-ul">
                    <li class="tab-item">
                        <a class="tab-link tab-active" data-area="#basic" href="#/basic">Basic Setting</a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#domain" href="#/domain">
                            Domain
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#owner" href="#/owner">
                            Owner
                        </a>
                    </li>
                    <li class="tab-item">
                        <a class="tab-link" data-area="#module" href="#/module">
                            Modules
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-10 col-md-8">
            <div class="m-portlet m-portlet--mobile tab_area area-active" id="basic_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Basic Setting
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">
                    <form action="{{route('admin.website.list.basicUpdate', $website->id)}}" id="basic_form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label for="name">
                                        Name
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[1]}}'
                                           data-page="{{$view_name}}"
                                           data-id="1"
                                        ></i>

                                    </label>
                                    <input type="text" class="form-control m-input m-input--square" name="name" id="name" value="{{$website->name}}">
                                    <div class="form-control-feedback error-name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="status_by_owner">
                                        Status By Owner
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[2]}}'
                                           data-page="{{$view_name}}"
                                           data-id="2"
                                        ></i>

                                    </label>
                                    <select name="status_by_owner" id="status_by_owner" class="m-bootstrap-select selectpicker w-100">
                                        <option value="active" @if($website->status_by_owner=='active') selected @endif>Active</option>
                                        <option value="pending" @if($website->status_by_owner=='pending') selected @endif>Pending</option>
                                        <option value="maintenance" @if($website->status_by_owner=='maintenance') selected @endif>Maintenance Mode</option>
                                    </select>
                                    <div class="form-control-feedback error-status_by_owner"></div>
                                </div>
                                <div class="form-group">
                                    <label for="status">
                                        Status
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[3]}}'
                                           data-page="{{$view_name}}"
                                           data-id="3"
                                        ></i>

                                    </label>
                                    <select name="status" id="status" class="m-bootstrap-select selectpicker w-100">
                                        <option value="active" @if($website->status=='active') selected @endif>Active</option>
                                        <option value="pending" @if($website->status=='pending') selected @endif>Pending</option>
                                        <option value="canceled" @if($website->status=='canceled') selected @endif>Canceled</option>
                                        <option value="expired" @if($website->status=='expired') selected @endif>Expired</option>
                                    </select>
                                    <div class="form-control-feedback error-status"></div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn m-btn--square  btn-outline-success m-btn m-btn--custom smtBtn">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area" id="domain_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Domain Setting
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="container domain_result">
                    </div>
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area" id="owner_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Change Owner Credentials
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <form action="" id="profileForm">
                                @csrf
                                <div class="form-group">
                                    <label for="owner_email">Email address
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[4]}}'
                                           data-page="{{$view_name}}"
                                           data-id="4"
                                        ></i>
                                    </label>
                                    <input type="email" class="form-control m-input m-input--square" name="owner_email" id="owner_email" placeholder="Owner email" value="{{$website->owner->email}}">
                                    <div class="form-control-feedback error-owner_email"></div>
                                </div>
                                <div class="form-group">
                                    <label for="owner_password">Password
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[5]}}'
                                           data-page="{{$view_name}}"
                                           data-id="5"
                                        ></i>
                                    </label>
                                    <input type="password" class="form-control m-input m-input--square" name="owner_password" id="owner_password" placeholder="New Password">
                                    <div class="form-control-feedback error-owner_password"></div>
                                </div>
                                <div class="form-group text-right">
                                    <button class="btn btn-success smtBtn" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile tab_area" id="module_area">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Module Setting
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">

                    <form action="{{route('admin.website.list.updateModule', $website->id)}}" id="module_form">
                        @csrf

                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="module_limit" class="form-control-label">Module Limit:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[6]}}'
                                               data-page="{{$view_name}}"
                                               data-id="6"
                                            ></i>
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="module_limit" id="module_limit" value="{{$website->module_limit}}">
                                        <div class="form-control-feedback error-module_limit"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="featured_module_limit" class="form-control-label">Featured Module Limit:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[7]}}'
                                               data-page="{{$view_name}}"
                                               data-id="7"
                                            ></i>
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="featured_module_limit" id="featured_module_limit" value="{{$website->fmodule_limit}}">
                                        <div class="form-control-feedback error-featured_module_limit"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="modules" class="form-control-label">Choose Modules:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[8]}}'
                                       data-page="{{$view_name}}"
                                       data-id="8"
                                    ></i>
                                </label>
                                <select name="modules[]" id="modules" class="modules" multiple="true">
                                    <option></option>
                                    @foreach($modules as $module)
                                        <option value="{{$module->id}}" @if(in_array($module->slug, $website->activeModules->pluck("slug")->toArray())) selected @endif>{{$module->name}} @if($module->featured) ( Featured ) @endif</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-modules"></div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn m-btn--square  btn-outline-success m-btn m-btn--custom smtBtn">
                                    Update
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="domain_connect_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Connect your domain</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">https://</span>
                            </div>
                            <input type="text" class="form-control m-input" name="connect_domain" id="connect_domain" placeholder="Enter your domain name">
                        </div>
                        <div class="form-control-feedback error-connect_domain"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="connect_domain_btn">Connect</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="custom_domain_edit_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Subdomain domain</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">https://</span>
                            </div>
                            <input type="text" class="form-control m-input text-right" name="subdomain" id="subdomain" placeholder="Enter domain name">
                            <div class="input-group-append">
                                <span class="input-group-text">.{{optional(option("ssh"))['root_domain']}}</span>
                            </div>
                        </div>
                        <div class="form-control-feedback error-subdomain"></div>
                        <input type="hidden" id="subdomain_id" name="id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update_domain_btn">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script> var website_id = "{{$website->id}}"</script>
    <script src="{{asset('assets/js/admin/websites/edit.js')}}"></script>
@endsection
