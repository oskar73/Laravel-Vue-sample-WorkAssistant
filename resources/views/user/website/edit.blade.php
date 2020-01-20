@extends('layouts.master')

@section('title', 'Website')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Website', 'Edit']" :menuLinks="[route('user.website.index')]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('user.website.index')}}" label="Back" type="info"/>
    </div>
@endsection

@section('content')

    <x-layout.tabs-wrapper>
        <x-layoutItems.normal-tab title="Basic Setting" link="basic" active="1"/>
        <x-layoutItems.normal-tab title="Domain" link="domain" active="0"/>
        <x-layoutItems.normal-tab title="Owner" link="owner" active="0"/>
        <x-layoutItems.normal-tab title="Modules" link="module" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="basic_area" active="1">
        <form action="{{route('user.website.updateBasic', $website->id)}}" id="basic_form">
            @csrf
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="form-group">
                        <label for="name">
                            Name
                            <i class="fa fa-info-circle tooltip_3" title="Name"></i>
                        </label>
                        <input type="text" class="form-control m-input m-input--square" name="name" id="name" value="{{$website->name}}">
                        <div class="form-control-feedback error-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="status">
                            Status
                            <i class="fa fa-info-circle tooltip_3" title="Status"></i>
                        </label>
                        <select name="status" id="status" class="m-bootstrap-select selectpicker w-100">
                            <option value="active" @if($website->status_by_owner=='active') selected @endif>Active</option>
                            <option value="pending" @if($website->status_by_owner=='pending') selected @endif>Pending</option>
                            <option value="maintenance" @if($website->status_by_owner=='maintenance') selected @endif>Maintenance Mode</option>
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
    </x-layout.portletBody>
    <x-layout.portletBody id="domain_area" active="0">
        <div class="container domain_result">
        </div>
    </x-layout.portletBody>
    <x-layout.portletBody id="owner_area" active="0">
        <form action="{{route('user.website.updateOwner', $website->id)}}" id="owner_form">
            @csrf
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="form-group">
                        <label for="owner_email">Email address</label>
                        <input type="email" class="form-control m-input m-input--square" name="owner_email" id="owner_email" placeholder="Owner email" value="{{$website->owner->email}}">
                        <div class="form-control-feedback error-owner_email"></div>
                    </div>
                    <div class="form-group">
                        <label for="owner_password">Password</label>
                        <input type="password" class="form-control m-input m-input--square" name="owner_password" id="owner_password" placeholder="New Password">
                        <div class="form-control-feedback error-owner_password"></div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn m-btn--square  btn-outline-success m-btn m-btn--custom smtBtn">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </x-layout.portletBody>
    <x-layout.portletBody id="module_area" active="0">
        <form action="{{route('user.website.updateModule', $website->id)}}" id="module_form">
            @csrf
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="alert alert-primary m-alert m-alert--air m-alert--outline" role="alert">
                        Module Limit:{{$website->module_limit==-1?'Unlimited':$website->module_limit}},
                        Featured Module Limit: {{$website->fmodule_limit==-1?'Unlimited':$website->fmodule_limit}}
                    </div>
                    <div class="form-group">
                        <label for="modules" class="form-control-label">Choose Modules:</label>
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
            </div>
        </form>
    </x-layout.portletBody>

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
    <script>var website_id = "{{$website->id}}"</script>
    <script src="{{asset('assets/js/user/website/edit.js')}}"></script>
@endsection
