@extends('layouts.master')

@section('title', 'Widget List')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Widget']" :menuLinks="[]" />
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div id="sortable" class="row" style="font-family: Segoe UI">
        @foreach ($items as $item)
        <div data-id="{{ $item->id }}" class="col-md-3 col-sm-6 col-12 widget-card mb-4">
            <div class="tw-w-full tw-shadow tw-bg-gray-200">
                <div class="tw-px-4 sm:tw-px-6 tw-pt-4 sm:tw-pt-6">
                    <h5 class="tw-mb-3 tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-between tw-items-center">
                        @if ($item->link)
                        <a href="{{ $item->link }}">{{ $item->title }}</a>
                        @else
                        {{ $item->title }}
                        @endif
                        <i data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-description="{{ $item->description }}" data-status="{{ $item->status }}" data-link="{{ $item->link }}" class='fa fa-pen fa-sm tw-text-gray-600 tw-cursor-pointer edit-btn'></i>
                    </h5>
                    <p class="tw-text-sm tw-font-normal tw-text-gray-500">{{ $item->description }}</p>
                </div>
                <div class="tw-bg-white tw-px-2 sm:tw-px-3 tw-py-3">
                    <ul data-id={{ $item->id }} class="tw-mb-4 sortable tw-py-2">
                        @foreach ($item->items as $widget)
                        <li data-id="{{ $widget->id }}" class="tw-cursor-move hover:tw-bg-gray-100 tw-py-1.5 tw-px-2 sm:tw-px-3 hover:tw-shadow">
                            <a class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group">
                                <span class="tw-flex-1 tw-whitespace-nowrap tw-text-[#0574bf]">{{ $widget->title }}</span>
                                <i data-category="{{ $item->id }}" data-id="{{ $widget->id }}" data-title="{{ $widget->title }}" data-url="{{ $widget->url }}" class='fa fa-pen fa-sm tw-mr-2 tw-text-gray-600 tw-cursor-pointer edit-widget-btn'></i>
                                <i data-id="{{ $widget->id }}" data-title="{{ $widget->title }}" class='fa fa-trash fa-sm tw-text-gray-600 tw-cursor-pointer delete-widget-btn'></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <button data-widget="{{ $item->id }}" class="tw-flex tw-items-center tw-p-3 tw-text-base tw-font-bold tw-text-gray-900 tw-rounded-lg tw-group tw-border tw-border-dashed tw-w-full hover:tw-bg-gray-50 new-widget-btn">
                        <span class="tw-flex-1 tw-ms-3 tw-whitespace-nowrap">New Widget</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="modal fade" id="create_widget_modal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Widget</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form action="{{ route('admin.widget.store') }}" id="create_widget_form">
                    @csrf
                    <input type="hidden" name="id" id="category_id" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title" class="form-control-label">Widget Title*</label>
                            <input type="text" class="form-control m-input--square minh-50" name="title" id="title" value="" required>
                            <div class="form-control-feedback error-title"></div>
                        </div>
                        <div class="form-group">
                            <label for="link" class="form-control-label">Link(Optional)</label>
                            <input type="text" class="form-control m-input--square minh-50" name="link" id="link" value="">
                            <div class="form-control-feedback error-link"></div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description(Optional)</label>
                            <textarea class="form-control m-input--square minh-100" name="description" id="description" value=""></textarea>
                            <div class="form-control-feedback error-description"></div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="form-control-label">Active?
                                <i class="la la-info-circle tooltip_icon" title='{{ $tooltip[5] }}'
                                    data-page="{{ $view_name }}" data-id="5"></i>
                            </label>
                            <div>
                                <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                    <label>
                                        <input type="checkbox" checked="checked" id="status" name="status">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success widget-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create_widget_item_modal" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="widget_item_title">New Widget</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form action="{{ route('admin.widget.store.item') }}" id="create_widget_item_form">
                    @csrf
                    <input id="category" type="hidden" name="category" />
                    <input id="id" type="hidden" name="id" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="item_title" class="form-control-label">Title*</label>
                            <input type="text" class="form-control m-input--square minh-50" name="title" id="item_title" value="" required>
                            <div class="form-control-feedback error-title"></div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="form-control-label">Url*</label>
                            <input type="text" class="form-control m-input--square minh-50" name="url" id="url" value="" required>
                            <div class="form-control-feedback error-url"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success widget-item-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('assets/js/admin/widget.js') }}"></script>
@endsection
