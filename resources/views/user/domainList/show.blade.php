@extends('layouts.master')

@section('title', 'Domain Detail')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{ route('user.domainList.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Domain</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Details</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.domainList.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger mb-2"> Back</a>
    </div>
@endsection

@section('content')
<div class="domain_search_area">
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="#/detail"> Domain Detail</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#ns" href="#/ns"> Nameservers</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#dns" href="#/dns"> Advanced DNS</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
        <div class="m-portlet__body">

        <div class="row">
            <div class="col-md-3">
                <br>
                <h3><i class="fa fa-globe f-20"></i> <span class="domain_name">{{$domain->name}}</span></h3>
            </div>
            <div class="col-md-2">
                <p>Created Date: <i class="fa fa-info-circle tooltip_1" title="Domain created date"></i></p>
                <p class="f-20 created"><i class='fa fa-spinner fa-spin fa-2x fa-fw load-1'></i></p>
            </div>
            <div class="col-md-2">
                <p>Expires At: <i class="fa fa-info-circle tooltip_1" title="Domain expired date"></i></p>
                <p class="f-20 expires"><i class='fa fa-spinner fa-spin fa-2x fa-fw load-1'></i></p>
            </div>
            <div class="col-md-2">
                <p>Domain Locked Status: <i class="fa fa-info-circle tooltip_1" title="Domain locked status"></i></p>
                <i class='fa fa-spinner fa-spin fa-2x fa-fw load-2'></i>
                <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                    <label>
                        <input type="checkbox" checked="checked" id="isLocked" class="status_checkbox">
                    </label>
                </span>
            </div>
            <div class="col-md-2">
                <p>WhoIsGuard Protection: <i class="fa fa-info-circle tooltip_1" title="WhoIsGuard Protection"></i></p>
                <i class='fa fa-spinner fa-spin fa-2x fa-fw load-1'></i>
                <span class="m-switch m-switch--outline m-switch--icon m-switch--info">
                    <label>
                        <input type="checkbox" checked="checked" id="whoisguard" class="status_checkbox">
                    </label>
                </span>
            </div>
        </div>
        <hr>
        <h4>Contact Details</h4> <br>
            <form action="{{route('user.domainList.updateContact', $domain->id)}}" method="POST" id="contact-form">
                @csrf
                @include('components.domain.contact')
                <div class="text-right">
                    <button type="submit" class="btn btn-success mb-1 tw-bg-green-600 p-2 smtBtn">Update Contact</button>
                </div>
            </form>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="ns_area">
        <div class="m-portlet__body">
            <div class="nameserver_area">
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-bordered ajaxTable datatable">
                            <thead>
                                <tr>
                                    <th>Nameserver</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody class="ns_tbody">
                                <tr><td colspan="5" class="h-200 load-5" ><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i></td></tr>
                            </tbody>
                            <div class="d-flex justify-content-between">
                                <a href="javascript:void(0);" class="btn btn-success mb-1 p-2 nsaddBtn">+Add</a>
                                <div class="text-right">
                                    <a href="javascript:void(0);" class="btn btn-primary mb-1 p-2 nsresetBtn">Set Default Nameserver</a>
                                    <a href="javascript:void(0);" class="btn btn-success mb-1 p-2 nsupdateBtn">Update</a>
                                </div>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="dns_area">
        <div class="m-portlet__body">
            <div class="dns_error">

            </div>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Host</th>
                            <th>Value</th>
                            <th>TTL(S)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="dns_tbody">
                        <tr><td colspan="5" class="h-200 load-4" ><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i></td></tr>
                    </tbody>
                    <div class="d-flex justify-content-between">
                        <a href="javascript:void(0);" class="btn btn-success mb-1 p-2 addBtn">+Add</a>
                        <a href="javascript:void(0);" class="btn btn-success mb-1 p-2 updateBtn">Update</a>
                    </div>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script> var domain_id = "{{$domain->id}}";</script>
    <script src="{{asset('assets/js/user/domainList/show.js')}}"></script>
@endsection
