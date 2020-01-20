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
                <a href="{{route('admin.mail.account.index', $account->domain->id)}}" class="m-nav__link">
                    <span class="m-nav__link-text">{{$account->domain->domain}}</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Edit Account</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <x-layout.portletBody id="all_area" active="1">
        <form action="{{route('admin.mail.account.update', $account->id)}}" id="submit_form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <h3>Email Address: {{$account->email}}</h3> <br>
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input
                            name="name"
                            label="Name"
                            value="{{$account->name}}"
                            tooltip="This is name of user."
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            type="number"
                            name="quota"
                            label="Quota (MB) (max: {{$account->domain->max_quota_per_mailbox}})"
                            value="{{$account->quota}}"
                            tooltip="Quota."
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            type="password"
                            name="password"
                            label="Password"
                            value=""
                            placeholder="If unchanged leave blank"
                            tooltip="Password of account."
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            type="password"
                            name="password_confirmation"
                            label="Confirm Password"
                            value=""
                            tooltip="Confirm Password of account."
                        />
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <x-form.checkbox name="status" label="Active" checked="{{$account->status=='active'? 'checked':''}}"/>
                    </div>
                    <div class="col-md-4">
                        <x-form.checkbox name="force_password_update" label="Force Password Update at next login" checked="{{$account->force_password_update? 'checked':''}}" />
                    </div>
                    <div class="col-md-4 text-right">
                        <x-form.a label="Back" link="{{route('admin.mail.account.index', $account->domain->id)}}"/>
                        <x-form.button label="Submit"/>
                    </div>
                </div>
            </div>
        </form>
    </x-layout.portletBody>
@endsection
@section('script')
    <script>var redirect="{{route('admin.mail.account.index', $account->domain->id)}}"</script>
    <script src="{{asset('assets/js/admin/mail/accountCreate.js')}}"></script>
@endsection
