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
                <a href="{{route('admin.mail.account.index', $domain->id)}}" class="m-nav__link">
                    <span class="m-nav__link-text">{{$domain->domain}}</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Create Account</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <x-layout.portletBody id="all_area" active="1">
        <form action="{{route('admin.mail.account.store', $domain->id)}}" id="submit_form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.subdomain
                            label="Email Account"
                            name="username"
                            domain=" {{ '@' . $domain->domain }}"
                            placeholder="username"
                            tooltip="This is email address."
                        >
                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[1]}}'
                               data-page="{{$view_name}}"
                               data-id="1"
                            ></i>
                        </x-form.subdomain>
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="name"
                            label="Name"
                            value=""
                            tooltip="This is name of user."
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
                            type="number"
                            name="quota"
                            label="Quota (MB) (max: {{$domain->max_quota_per_mailbox}})"
                            value="{{$domain->default_mailbox_quota}}"
                            tooltip="Quota."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[3]}}'
                               data-page="{{$view_name}}"
                               data-id="3"
                            ></i>
                        </x-form.input>

                    </div>
                    <div class="col-md-3">
                        <x-form.input
                            type="password"
                            name="password"
                            label="Password"
                            value=""
                            tooltip="Password of account."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[4]}}'
                               data-page="{{$view_name}}"
                               data-id="4"
                            ></i>
                        </x-form.input>
                    </div>
                    <div class="col-md-3">
                        <x-form.input
                            type="password"
                            name="password_confirmation"
                            label="Confirm Password"
                            value=""
                            tooltip="Confirm Password of account."
                        >

                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[5]}}'
                               data-page="{{$view_name}}"
                               data-id="5"
                            ></i>
                        </x-form.input>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <x-form.checkbox name="status" label="Active" checked="checked"/>

                        <i class="la la-info-circle tooltip_icon"
                           title='{{$tooltip[6]}}'
                           data-page="{{$view_name}}"
                           data-id="6"
                        ></i>
                    </div>
                    <div class="col-md-4">
                        <x-form.checkbox name="force_password_update" label="Force Password Update"/>

                        <i class="la la-info-circle tooltip_icon"
                           title='{{$tooltip[7]}}'
                           data-page="{{$view_name}}"
                           data-id="7"
                        ></i>
                    </div>
                    <div class="col-md-4 text-right">
                        <x-form.a label="Back" link="{{route('admin.mail.account.index', $domain->id)}}"/>
                        <x-form.button label="Submit"/>
                    </div>
                </div>
            </div>
        </form>
    </x-layout.portletBody>
@endsection
@section('script')
    <script>var redirect = "{{route('admin.mail.account.index', $domain->id)}}"</script>
    <script src="{{asset('assets/js/admin/mail/accountCreate.js')}}"></script>
@endsection
