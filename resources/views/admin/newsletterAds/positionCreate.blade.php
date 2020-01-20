@extends('layouts.master')

@section('title', 'Newsletter Advertisement Position Create')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Newsletter Advertisement', 'Position', 'Create']"
                             :menuLinks="['', route('admin.newsletterAds.position.index'), '']" />
    </div>
@endsection

@section('content')
    <x-layout.tabs-wrapper>
        <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Position Detail</a></li>
        <li class="tab-item"><a class="tab-link" href="javascript:void(0);">Price</a></li>
        <li class="tab-item"><a class="tab-link" href="javascript:void(0);">Default Listing</a></li>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        <x-form.form action="{{route('admin.newsletterAds.position.store')}}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <x-form.input name="name" label="Name" />

                    <x-form.textarea label="Description" name="description"></x-form.textarea>

                    <x-form.select label="Ads Type" name="type" class="non_search_select2">
                        <option></option>
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.status label="Approve?" name="status" disabled="disabled" />
                </div>
                <div class="col-md-6">
                    <div class="slim slimdiv"
                         data-download="true"
                         data-label="Drop or choose screenshot"
                         data-max-file-size="10"
                         data-instant-edit="true"
                         data-button-remove-title="Upload"
                         data-ratio="4:3">
                        <input type="file" name="image" />
                    </div>
                </div>
            </div>
            <div class="text-right mt-4">
                <x-form.a link="{{route('admin.newsletterAds.position.index')}}" label="Back" />
                <x-form.smtBtn type="success" label="Next" />
            </div>
        </x-form.form>
    </x-layout.portletBody>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/newsletterAds/positionCreate.js')}}"></script>
@endsection
