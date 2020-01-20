@extends('layouts.master')

@section('title', 'Mail Service')
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
                <a href="{{route('admin.mail.domain.index')}}" class="m-nav__link">
                    <span class="m-nav__link-text">{{$domain->domain}}</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Accounts</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <x-form.a label="Back" link="{{route('admin.mail.domain.index')}}"/>
        <x-form.a label="Add New Account" type="success" link="{{route('admin.mail.account.create', $domain->id)}}"/>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">{{$domain->domain}} (<span class="all_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            @include('components.admin.mailAccountTable', ['selector'=>'datatable-all', 'all'=>0])
        </div>
    </div>

@endsection
@section('script')
    <script>var domain_id="{{$domain->id}}"</script>
    <script src="{{asset('assets/js/admin/mail/account.js')}}"></script>
@endsection
