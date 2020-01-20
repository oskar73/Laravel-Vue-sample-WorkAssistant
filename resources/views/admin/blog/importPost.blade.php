@extends('layouts.master')

@section('title', 'Create Blog Post')

@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Blog', 'Post', 'Import']" :menuLinks="[]" />
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile tab_area area-active md:mt-0 mt-[50px]">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Blog  Import
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <form action="{{ route('admin.blog.post.importPageView') }}" class="blog-import" id="import_form" method="POST">
                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <label>Host Url*</label>
                            <div class="input-group align-items-center">
                                <div class="search-prefix">https://</div>
                                <input type="text" name="host" id="host" class="form-control" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Blog Search</label>
                            <input type="text" name="search" id="search" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Username*</label>
                            <input type="text" name="username" id="username" class="form-control" autocomplete="off" required>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Rest API Password*</label>
                            <input type="text" name="password" id="password" class="form-control" autocomplete="off" required>
                        </div>
                    </div>
                    <a href="https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/#Getting-Credentials" target="_blank">How to get wordpress rest api credentials?</a>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary smtBtn h-100">Search</button>
                    </div>
                </form>
                <hr class="my-4" />
                <div class="col-12">
                    <div class="text-right">
                        <button type="button" class="mb-3 btn m-btn--square m-btn--custom btn-outline-success import-btn" style="display: none;">Import</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover ajaxTable datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="no-sort">
                                    <input name="select_all" class="select-all checkbox" value="1" type="checkbox">
                                </th>
                                <th>
                                    Category
                                </th>
                                <th class="w-100">
                                    Title
                                </th>
                                <th>
                                    Tags
                                </th>
                                <th>
                                    Author
                                </th>
                            </tr>
                            </thead>
                            <tbody class="result_area"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/blog/importPost.js')}}"></script>
@endsection
