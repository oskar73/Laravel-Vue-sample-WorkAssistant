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
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">All Domains</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <x-form.a label="Add New Domain" type="success" link="{{route('admin.mail.domain.create')}}"/>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">All Domains (<span class="all_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active">Active Domains(<span class="active_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive">Inactive Domains(<span class="inactive_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#allAccount" href="#/allAccount">All Accounts(<span class="allAccount_count">0</span>)</a></li>
        </ul>
    </div>
    <x-layout.portletBody id="all_area" active="1">
        @include('components.admin.mailDomainTable', ['selector'=>'datatable-all', 'user'=>1])
    </x-layout.portletBody>

    <x-layout.portletBody id="active_area" active="0">
        @include('components.admin.mailDomainTable', ['selector'=>'datatable-active', 'user'=>1])
    </x-layout.portletBody>

    <x-layout.portletBody id="inactive_area" active="0">
        @include('components.admin.mailDomainTable', ['selector'=>'datatable-inactive', 'user'=>1])
    </x-layout.portletBody>

    <x-layout.portletBody id="allAccount_area" active="0">
        @include('components.admin.mailAccountTable', ['selector'=>'datatable-allAccount', 'all'=>1])
    </x-layout.portletBody>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/mail/domain.js')}}"></script>
@endsection
