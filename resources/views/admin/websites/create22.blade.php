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
                    <span class="m-nav__link-text">Create</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Website Create</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.website.list.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="name" class="form-control-label">
                                Websit Name:
                                <i class="fa fa-info-circle tooltip_1" title="You can name website. optional."></i>
                            </label>
                            <input type="text" class="form-control m-input--square" name="name" id="name">
                            <div class="form-control-feedback error-name"></div>
                        </div>
                        <div class="m-form__group form-group">
                            <label class="form-control-label"> Choose Domain:</label>
                            <hr>
                            <div class="m-radio-list">
                                <label class="m-radio m-radio--state-success">
                                    <input type="radio" name="domain_type" value="subdomain" data-area="s_domain_type"> Pick from Subdomain
                                    <i class="fa fa-info-circle tooltip_1" title="Choose unique subdomain name. Minimum characters are 4."></i>
                                    <span></span>
                                </label>
                                <div class="domain_area s_domain_type d-none p-3 mt-3 mb-3">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">https://</span>
                                        </div>
                                        <input type="text" class="form-control m-input text-right" name="subdomain" placeholder="Enter domain name">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.{{optional(option("ssh"))['root_domain']}}</span>
                                        </div>
                                    </div>
                                    <div class="form-control-feedback error-subdomain"></div>
                                </div>
                                <label class="m-radio m-radio--state-brand">
                                    <input type="radio" name="domain_type" value="connected" data-area="c_domain_type"> Connect your own domain
                                    <i class="fa fa-info-circle tooltip_2" title="You can connect your own domain. For detailed explanation, please click <a href='http://localhost'>this link.</a>"></i>
                                    <span></span>
                                </label>
                                <div class="domain_area c_domain_type d-none  p-3 mt-3 mb-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">https://</span>
                                            </div>
                                            <input type="text" class="form-control m-input" name="connect_domain" id="connect_domain" placeholder="Enter your domain name">
                                            <div class="input-group-append">
                                                <a href="javascript:void(0);" class="btn btn-brand" id="submit_domain">
                                                    <i class="la la-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="form-control-feedback error-connect_domain"></div>
                                    </div>
                                    <div class="connected_domains">
                                    </div>
                                </div>

                                <label class="m-radio m-radio--state-primary">
                                    <input type="radio" name="domain_type" value="hosted" data-area="h_domain_type"> Choose from bizinabox hosted domain (purchased, transferred)
                                    <i class="fa fa-info-circle tooltip_2" title="You can purchase new domain here."></i>
                                    <span></span>
                                </label>
                                <div class="domain_area h_domain_type d-none  p-3 mt-3 mb-3">
                                    <div class="text-right">
                                        <a href="{{route('admin.domain.search')}}" class="btn btn-success p-2 mb-1">Purchase New Domain</a>
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
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner" class="form-control-label"> Choose Owner:</label>
                                    <select name="owner" id="owner" class="selectpicker" data-live-search="true" data-width="100%">
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" >{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-owner"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="storage" class="form-control-label">Storage Limit (GB):</label>
                                    <input type="text" class="form-control m-input--square" name="storage" id="storage" value="5">
                                    <div class="form-control-feedback error-storage"></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p>Admin Credentials</p>

                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" name="credentials" id="credentials" checked> use same email and password
                            <span></span>
                        </label>
                        <div class="row mt-3 custom_credential d-none">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input type="text" class="form-control m-input--square" name="email" id="email">
                                    <div class="form-control-feedback error-email"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Password</label>
                                    <input type="password" class="form-control m-input--square" name="password" id="password">
                                    <div class="form-control-feedback error-password"></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="modules" class="form-control-label">Choose Modules:</label>
                            <select name="modules" id="modules" class="modules w-100" multiple="true">
                                <option value="Blog">Blog</option>
                                <option value="Blog Ads">Blog Ads</option>
                                <option value="E-commerce">E-commerce</option>
                                <option value="Directory">Directory</option>
                                <option value="Page Ads">Page Ads</option>
                                <option value="Additional Page">Additional Page</option>
                            </select>
                            <div class="form-control-feedback error-modules"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">
                                        Status
                                        <i class="fa fa-info-circle tooltip_3" title="Status"></i>
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
                                    <label for="status_by_owner">
                                        Status by Owner
                                        <i class="fa fa-info-circle tooltip_3" title="status_by_owner"></i>
                                    </label>
                                    <select name="status_by_owner" id="status_by_owner" class="m-bootstrap-select selectpicker w-100">
                                        <option value="active">Active</option>
                                        <option value="pending">Pending</option>
                                        <option value="maintenance">Maintenance Mode</option>
                                    </select>
                                    <div class="form-control-feedback error-status_by_owner"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <br>
                            <a href="{{route('admin.website.list.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square m-1">Back</a>
                            <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn m-1">Submit</button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="template" class="form-control-label">Choose Template:</label>
                            <select name="template" id="template" class="selectpicker" data-live-search="true" data-width="100%">
                                <option selected disabled hidden>Choose Template</option>
                                @foreach($templates as $template)
                                    <option value="{{$template->id}}" data-img="{{$template->getFirstMediaUrl('preview')}}" data-slug="{{$template->slug}}">{{$template->name}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-template"></div>
                        </div>
                        <div class="preview_template">

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
