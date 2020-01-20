@extends('layouts.master')

@section('title', 'Tutorials')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Tutorials']"/>
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.tutorial.item.create')}}" label="Create" type="success"/>
    </div>
@endsection

@section('content')
    <x-layout.tab-btn-area>
        <x-layoutItems.switch-action-btn name="active" />
        <x-layoutItems.switch-action-btn name="inactive" class="info" />
        <x-layoutItems.switch-action-btn name="delete" class="danger"/>
    </x-layout.tab-btn-area>

    <x-layout.tabs-wrapper>
        <x-layoutItems.tab-item name="all" label="All Items" active="1"/>
        <x-layoutItems.tab-item name="active" label="Active Items" active="0"/>
        <x-layoutItems.tab-item name="inactive" label="Inactive Items" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        <x-layoutItems.loading />
    </x-layout.portletBody>

    <x-layout.portletBody id="active_area" active="0">
        <x-layoutItems.loading />
    </x-layout.portletBody>

    <x-layout.portletBody id="inactive_area" active="0">
        <x-layoutItems.loading />
    </x-layout.portletBody>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/tutorial/index.js')}}"></script>
@endsection
