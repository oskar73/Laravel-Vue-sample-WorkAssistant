@extends('layouts.master')

@section('title', 'Service')
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
                    <span class="m-nav__link-text">Service</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Edit Item</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.service.item.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Service Detail</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#meeting" href="#/meeting">Meeting and Attach Form</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.service.item.update', $item->id)}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="footer" class="form-control-label">Choose Category:</label>
                            <select name="category" id="category" class="category selectpicker" data-live-search="true" data-width="100%">
                                <option value="" disabled selected>Choose Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @selected($category->id == $item->category_id)>{{$category->name}}</option>
                                    @foreach($category->approvedSubCategories as $subcat)
                                        <option value="{{$subcat->id}}" @selected($category->id == $item->category_id)>{{$category->name}} --> {{$subcat->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-category"></div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$item->name}}">
                            <div class="form-control-feedback error-name"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Price:</label>
                                    <input type="text" class="form-control price" name="price" id="price" value="{{formatNumber($item->price)}}">
                                    <div class="form-control-feedback error-price"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slashed_price" class="form-control-label">Slashed Price: <i class="fa fa-info-circle tooltip_1" title="Slashed Price"></i></label>
                                    <input type="text" class="form-control price" name="slashed_price" id="slashed_price" value="{{$item->slashed_price}}">
                                    <div class="form-control-feedback error-slashed_price"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description:</label>
                            <textarea class="form-control m-input--square minh-100 white-space-pre-line" name="description" id="description">{{$item->description}}</textarea>
                            <div class="form-control-feedback error-description"></div>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="image_area">
                                @foreach($item->getMedia('image') as $key=>$image)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                        </td>
                                        <td class="text-center">
                                            <figure data-href="{{$image->getUrl()}}" class="width-150 progressive replace m-auto">
                                                <img class='width-150 preview' src="{{$image->getUrl('thumb')}}"/>
                                            </figure>
                                        </td>
                                        <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addImage">+ Add Image</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="link_area">
                                @foreach($item->getLinks() as $key1=>$link)
                                    <tr>
                                        <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}"></td>
                                        <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addLink">+ Add External Video Link</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="video_area">
                                @foreach($item->getMedia('video') as $key2=>$video)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square" value="{{$video->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                                        </td>
                                        <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addVideo">+ Upload Video</a>
                            </table>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="example_input_full_name">Choose Gallery Order:</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="1" @if($item->order==1) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Image Gallery

                                                    <hr/>

                                                    Video Gallery
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="0" @if($item->order==0) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                     Video Gallery

                                                    <hr/>

                                                     Image Gallery

                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- <div class="form-group">
                            <label for="thumbnail" class="form-control-label">Thumbnail</label>
                            <input type="file" accept="image/*" class="form-control m-input--square" id="thumbnail" >
                            <div class="form-control-feedback error-thumbnail"></div>
                            <img id="thumbnail_image" class="maxw-100" src="{{$item->getFirstMediaUrl("thumbnail")}}"/>
                        </div> --}}
                        <div class="form-group slimdiv mb-5" style="width: 300px; margin: auto">
                            <label for="thumbnail" class="form-control-label">Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" />
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="featured" class="form-control-label">Featured?</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="featured" id="featured" @if($item->featured) checked @endif>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="new" class="form-control-label">New?</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="new" id="new" @if($item->new) checked @endif>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="status" class="form-control-label">Approve?</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="status" id="status" @if($item->status) checked @endif>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @include("components.admin.common.meetingForm", ['action'=>route('admin.service.item.updateMeetingForm', $item->id)])
@endsection
@section('script')
    {{-- <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script> --}}
    <script>
        var category = "{{$item->category_id}}",
            item_id = "{{$item->id}}",
            maxImageSize = "{{config('custom.variable.max_image_size')}}";

        @if($item->getFirstMediaUrl("thumbnail"))
            window.thumbNailUrl = '{{$item->getFirstMediaUrl("thumbnail")}}';
        @endif
    </script>
    <script src="{{asset('assets/js/admin/service/itemEdit.js')}}"></script>
    <script src="{{asset('assets/js/admin/common/meetingForm.js')}}"></script>
@endsection
