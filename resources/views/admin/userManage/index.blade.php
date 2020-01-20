@extends('layouts.master')

@section('title', 'User Management')
@section('style')

@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['User Management']" :menuLinks="[route('admin.userManage.index')]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.userManage.create')}}" label="New User" type="success"/>
    </div>
@endsection

@section('content')
    <x-layout.tab-btn-area>
        <x-layoutItems.switch-action-btn name="active" />
        <x-layoutItems.switch-action-btn name="inactive" class="info" />
        <x-layoutItems.switch-action-btn name="delete" class="danger"/>
        <x-layoutItems.switch-action-btn name="send_verification" />
        <x-layoutItems.switch-action-btn name="account_creation" />
    </x-layout.tab-btn-area>

    <x-layout.tabs-wrapper>
        <x-layoutItems.tab-item name="all" label="All Users" active="1"/>
        <x-layoutItems.tab-item name="active" label="Active Users" active="0"/>
        <x-layoutItems.tab-item name="inactive" label="Inactive Users" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        @include('components.admin.userManageTable', ['selector'=>'datatable-all'])
    </x-layout.portletBody>

    <x-layout.portletBody id="active_area" active="0">
        @include('components.admin.userManageTable', ['selector'=>'datatable-active'])
    </x-layout.portletBody>

    <x-layout.portletBody id="inactive_area" active="0">
        @include('components.admin.userManageTable', ['selector'=>'datatable-inactive'])
    </x-layout.portletBody>

    <div class="modal fade" id="userDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">DeleteUser</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn m-btn--square m-btn--custom btn-outline-primary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn m-btn--square m-btn--custom btn-outline-info" >Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/userManage/index.js')}}"></script>
@endsection
