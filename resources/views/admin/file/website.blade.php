@extends('layouts.master')

@section('title', 'File Management')
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
                    <span class="m-nav__link-text">File</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Websites</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Websites</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <div class="table-responsive">
                <table class="table table-hover ajaxTable datatable datatable-all">
                    <thead>
                    <tr>
                        <th>
                            <input name="select_all" class="selectAll checkbox" value="1" type="checkbox" data-area="datatable-all">
                        </th>
                        <th>
                            Domain
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            User
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Storage
                        </th>
                        <th>
                            Created At
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="loading-tbody"><tr><td colspan="8" style="height:200px;"></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/file/website.js')}}"></script>
@endsection
