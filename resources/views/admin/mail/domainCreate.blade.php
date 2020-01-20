@extends('layouts.master')

@section('title', 'Mail Service Management')
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
                    <span class="m-nav__link-text">Mail Service</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Add new domain</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <x-layout.portletBody id="all_area" active="1">
        <form action="{{route('admin.mail.domain.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <x-form.instruction>
                    If the domain is registered in our website, you will need to change DNS records yourself in domain registrar.
                    <a href="" target="_blank">Here</a> is detailed instruction. <br>
                    Please go to your DNS provider and add/change <mark>MX</mark> record to <mark>10 mail.bizinabox.com</mark>
                </x-form.instruction>
                <br>
                <x-form.domain
                    name="domain"
                    label="Domain"
{{--                    tooltip="Only accepting root domain. Not allowed for subdomain."--}}
                    placeholder="website.com"
                >

                    <i class="la la-info-circle tooltip_icon"
                       title='{{$tooltip[1]}}'
                       data-page="{{$view_name}}"
                       data-id="1"
                    ></i>

                </x-form.domain>
                <x-form.textarea name="description" label="Description" />

                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="max_aliases"
                            label="Max Aliases"
                            value="5"
                            tooltip="Max aliases limit."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[2]}}'
                               data-page="{{$view_name}}"
                               data-id="2"
                            ></i>

                        </x-form.input>
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="max_mail_boxes"
                            label="Max Mailboxes"
                            value="5"
                            tooltip="Max user counts limit."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[3]}}'
                               data-page="{{$view_name}}"
                               data-id="3"
                            ></i>

                        </x-form.input>
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            type="number"
                            name="default_mailbox_quota"
                            label="Default Mailbox Quota (MB)"
                            value="3072"
                            tooltip="Default mailbox quota when create new user."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[4]}}'
                               data-page="{{$view_name}}"
                               data-id="4"
                            ></i>

                        </x-form.input>
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            type="number"
                            name="max_quota_per_mailbox"
                            label="Max Quota per Mailbox (MB)"
                            value="10240"
                            tooltip="Max quota per mailbox."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[5]}}'
                               data-page="{{$view_name}}"
                               data-id="5"
                            ></i>

                        </x-form.input>
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            type="number"
                            name="domain_total_quota"
                            label="Domain Total Quota (MB)"
                            value="10240"
                            tooltip="Total Quota for this domain."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[6]}}'
                               data-page="{{$view_name}}"
                               data-id="6"
                            ></i>

                        </x-form.input>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rate_limit" class="form-control-label">
                                Rate limit:

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[7]}}'
                                   data-page="{{$view_name}}"
                                   data-id="7"
                                ></i>


                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control text-right m-input--square" value="" name="rate_limit" id="rate_limit">
                                <div class="input-group-append" style="width:150px;">
                                    <select class="form-control m-bootstrap-select selectpicker" name="rate_limit_unit" id="rate_limit_unit">
                                        <option value="s">msgs / second</option>
                                        <option value="m">msgs / minute</option>
                                        <option value="h">msgs / hour</option>
                                        <option value="d">msgs / day</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-control-feedback error-rate_limit"></div>
                        </div>

                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <x-form.checkbox name="status" label="Active" checked="checked"/>
                        <i class="la la-info-circle tooltip_icon"
                           title='{{$tooltip[8]}}'
                           data-page="{{$view_name}}"
                           data-id="8"
                        ></i>
                    </div>
                    <div class="col-md-6 text-right">
                        <x-form.a label="Back" link="{{route('admin.mail.domain.index')}}"/>
                        <x-form.button label="Submit"/>
                    </div>
                </div>
            </div>
        </form>
    </x-layout.portletBody>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/mail/domainCreate.js')}}"></script>
@endsection
