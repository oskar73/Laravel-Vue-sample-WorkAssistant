@extends('layouts.master')

@section('title', 'Tickets')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Ticket', 'List']"/>
    </div>
@endsection

@section('content')

    <x-layout.tab-btn-area>
        <x-layoutItems.switch-action-btn action="close" name="Close"/>
        <x-layoutItems.switch-action-btn action="delete" name="Delete" class="danger"/>
    </x-layout.tab-btn-area>

    <x-layout.tabs-wrapper>
        <x-layoutItems.tab-item name="opened" label="Opened Tickets" active="1"/>
        <x-layoutItems.tab-item name="all" label="All Tickets" active="0"/>
        <x-layoutItems.tab-item name="answered" label="Answered Tickets" active="0"/>
        <x-layoutItems.tab-item name="closed" label="Closed Tickets" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="opened_area" active="1">
        @include("components.admin.ticketTable", ['selector'=>'datatable-opened', 'user'=>1])
    </x-layout.portletBody>
    <x-layout.portletBody id="all_area" active="0">
        @include("components.admin.ticketTable", ['selector'=>'datatable-all', 'user'=>1])
    </x-layout.portletBody>

    <x-layout.portletBody id="answered_area" active="0">
        @include("components.admin.ticketTable", ['selector'=>'datatable-answered', 'user'=>1])
    </x-layout.portletBody>

    <x-layout.portletBody id="closed_area" active="0">
        @include("components.admin.ticketTable", ['selector'=>'datatable-closed', 'user'=>1])
    </x-layout.portletBody>

@endsection
@section('script')
    <script src="{{asset('assets/js/admin/ticket/item.js')}}"></script>
@endsection
