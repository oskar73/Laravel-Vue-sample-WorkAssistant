@extends('layouts.master')

@section('title', 'Websites')
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
                    <span class="m-nav__link-text">Websites</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">List</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.website.getting.started')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Create New Website</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">All (<span class="all_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#active" href="#/active">Active (<span class="active_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#inactive" href="#/inactive">Inactive (<span class="inactive_count">0</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#expired" href="#/expired">Expired (<span class="expired_count">0</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            @include('components.admin.websiteTable', ['selector'=>'datatable-all', 'user'=>1])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="active_area">
        <div class="m-portlet__body">
            @include('components.admin.websiteTable', ['selector'=>'datatable-active', 'user'=>1])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="inactive_area">
        <div class="m-portlet__body">
            @include('components.admin.websiteTable', ['selector'=>'datatable-inactive', 'user'=>1])
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="expired_area">
        <div class="m-portlet__body">
            @include('components.admin.websiteTable', ['selector'=>'datatable-expired', 'user'=>1])
        </div>
    </div>
    <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('user.website.delete')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Are you absolutely sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>This action <strong>cannot</strong> be undone. This will permanently delete the <strong class="website_domain"></strong> site and its all relative fields.</p>
                        <p>Please type <strong class="website_domain"></strong> to confirm</p>
                        <input type="text" class="form-control m-input" id="domain_input" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button id="_confirm" type="submit" class="btn btn-danger" disabled>Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/websites/index.js')}}"></script>
    <script>
        var domain = ''
        $(document).ready(function() {
            $(document).on('click', '.del-btn', function(e){
                var id = $(this).data('id')
                domain = $(this).data('domain')
                if (id && domain) {
                    $('#id').val(id)
                    $('.website_domain').html(domain)
                    $('#confirm-modal').modal('toggle');
                }
            })
            $(document).on('keyup', '#domain_input', function() {
                if ($('#domain_input').val() == domain) $('#_confirm').attr('disabled', false)
                else $('#_confirm').attr('disabled', true)
            })
        })
    </script>
@endsection
