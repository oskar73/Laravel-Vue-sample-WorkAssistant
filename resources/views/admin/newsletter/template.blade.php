@extends('layouts.master')

@section('title', 'Newsletter Templates')
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
                    <span class="m-nav__link-text">Newsletter</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Templates</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="#"
           class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2" id="create_btn">Create</a>
    </div>
@endsection

@section('content')
    <div class="tw-w-full tw-flex tw-justify-center tw-items-center" id="content">
        <div class="text-center"><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i></div>
    </div>

    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="create_modal_form" method="post" enctype="multipart/form-data"
                      action="{{route('admin.newsletter.template.store')}}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-control-label">
                                Name:
                            </label>
                            <input type="text" class="form-control m-input--square" name="name" id="name">
                            <div class="form-control-feedback error-name"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                                data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rename_modal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rename Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="rename_modal_form" method="post" enctype="multipart/form-data"
                      action="{{route('admin.newsletter.template.store')}}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="new_name" class="form-control-label">
                                Name:
                            </label>
                            <input type="text" class="form-control m-input--square" name="name" id="new_name">
                            <div class="form-control-feedback error-name"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                                data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/newsletter/template.js')}}"></script>
@endsection
