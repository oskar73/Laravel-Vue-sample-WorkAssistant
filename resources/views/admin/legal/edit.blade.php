@extends('layouts.master')

@section('title', 'Legal Pages')
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
                    <span class="m-nav__link-text">Legal Pages</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Edit</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile md-pt-50">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{$page->title}}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">

        </div>
    </div>
    <div class="m-portlet__body">
        <div class="container">
            <form action="{{route('admin.legalPage.update', $page->slug)}}" id="submit_form">
                @csrf
                <div class="form-group">
                    <label for="title">
                        Title
                        <i class="fa fa-info-circle tooltip_3" title="title"></i>
                    </label>
                    <input type="text" class="form-control m-input m-input--square" name="title" id="title" value="{{$page->title}}">
                    <div class="form-control-feedback error-title"></div>
                </div>
                <div class="form-group">
                    <label for="keywords">
                        Keywords
                        <i class="fa fa-info-circle tooltip_3" title="keywords"></i>
                    </label>
                    <input type="text" class="form-control m-input m-input--square" name="keywords" id="keywords" value="{{$page->keywords}}">
                    <div class="form-control-feedback error-keywords"></div>
                </div>
                <div class="form-group">
                    <label for="keywords">
                        Description
                        <i class="fa fa-info-circle tooltip_3" title="description"></i>
                    </label>
                    <textarea class="form-control m-input m-input--square" name="description" id="description">{{$page->description}}</textarea>
                    <div class="form-control-feedback error-description"></div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="body">
                        Page Content
                        <i class="fa fa-info-circle tooltip_3" title="body"></i>
                    </label>
                    <textarea class="form-control m-input m-input--square" name="body" id="body">{!! $page->body !!}</textarea>
                    <div class="form-control-feedback error-body"></div>
                </div>
                <div class="text-right">
                    <a href="{{route('admin.legalPage.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Back</a>
                    <button class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2 smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/legalPage/edit.js')}}"></script>
@endsection
