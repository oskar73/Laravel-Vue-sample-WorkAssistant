@extends('layouts.master')

@section('title', 'Template Sections')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Template', 'Sections']" />
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);"
            class="ml-auto btn m-btn--square m-btn--custom btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')

    <x-layout.tab-btn-area>
        <x-layoutItems.switch-action-btn action="active" name="Active" />
        <x-layoutItems.switch-action-btn action="inactive" name="Inactive" class="info" />
        <x-layoutItems.switch-action-btn action="delete" name="Delete" class="danger" />
    </x-layout.tab-btn-area>

    <x-layout.tabs-wrapper>
        <x-layoutItems.tab-item name="category" label="Category" active="1" />
        <x-layoutItems.tab-item name="section" label="All Sections" active="0" />
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="category_area" active="1">
        <x-layoutItems.loading />
    </x-layout.portletBody>

    <div class="w-100" id="app">
        <x-layout.portletBody id="section_area" active="0">
            <x-layoutItems.loading />
        </x-layout.portletBody>
    </div>

    <x-form.form action="{{ route('admin.template.section.category.store') }}">
        <x-form.modal id="create_modal" title="Section Category" smtBtnClass="smtBtn">
            <input type="hidden" name="category_id" id="category_id" />

            <x-form.input name="name" label="Name" />

            <x-form.select label="Choose Module" name="module" class="form-control m-input--square">
                <option></option>
                @foreach ($modules as $item)
                    <option value="{{ $item->slug }}">{{ $item->name }}</option>
                @endforeach
            </x-form.select>

            <x-form.input name="limit_per_page" label="Limit per page" type="number" />

            <x-form.textarea name="description" label="Description" />

            <div class="row">
                <div class="col-6">
                    <x-form.status name="recommended" label="Recommended?" />
                </div>
                <div class="col-6">
                    <x-form.status name="status" label="Active?" checked="checked" />
                </div>
            </div>
        </x-form.modal>
    </x-form.form>
@endsection
@section('script')
    <script src="{{ asset('assets/js/admin/template/section.js') }}"></script>
@endsection
