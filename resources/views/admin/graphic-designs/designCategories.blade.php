@extends('layouts.master')


@section('title', 'Graphic Design Categories')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Design', 'Categories']" />
    </div>
    <div class="col-md-6 text-right">
{{--        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--custom m-btn--sm btn-outline-info sortBtn mb-2">Sort Order</a>--}}
        <a href="javascript:void(0);" class="ml-auto btn m-btn--square m-btn--custom m-btn--sm btn-outline-success createBtn mb-2">Create</a>
    </div>
@endsection

@section('content')
    <div class="tw-bg-white tw-p-4 tw-border tw-rounded" id="all_area">
        <div class="tw-w-48">
            <select name="country" id="graphic_id" class="form-control" data-width="100%"
                    data-live-search="true">
                @foreach ($graphics as $item)
                    <option value="{{ $item->slug }}" @if ($item->slug == $graphic->slug) selected @endif>
                        {{ $item->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="tbl_subcategories" class="tw-flex tw-justify-center tw-py-10">
            <div class="tw-flex tw-items-center">
                <svg aria-hidden="true" class="tw-w-8 tw-h-8 tw-mr-2 tw-text-gray-200 tw-animate-spin tw-fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                          fill="currentColor"
                    />
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                          fill="currentFill"
                    />
                </svg>
                <span>Loading...</span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create new design category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="create_modal_form" method="post" enctype="multipart/form-data" action="{{route('admin.graphics.category.store')}}">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="file" name="thumbnail" id="slimInput" />
                                </div>
                                <div class="col-lg-6">
                                    <input type="hidden" name="category_id" id="category_id" />
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">
                                            Name:
                                        </label>
                                        <input type="text" class="form-control m-input--square" name="name" id="name">
                                        <div class="form-control-feedback error-name"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-control-label">
                                            Description:
                                        </label>
                                        <textarea class="form-control m-input--square minh-100" rows="6" name="description" id="description"></textarea>
                                        <div class="form-control-feedback error-description"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let graphic = {!! $graphic !!}
    </script>
    <script src="{{s3_asset('vendors/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/graphic-design/designCategories.js')}}"></script>
@endsection
