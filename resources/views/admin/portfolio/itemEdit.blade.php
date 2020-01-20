@extends('layouts.master')

@section('title', 'Portfolio')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Portfolios', 'Edit Item']"/>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Portfolio Detail</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.portfolio.item.update', $item->id)}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="footer" class="form-control-label">Choose Category:

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[1]}}'
                                   data-page="{{$view_name}}"
                                   data-id="1"
                                ></i>
                            </label>
                            <select name="category" id="category" class="category selectpicker" data-live-search="true" data-width="100%">
                                <option value="" disabled selected>Choose Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @foreach($category->approvedSubCategories as $subcat)
                                        <option value="{{$subcat->id}}">{{$category->name}} --> {{$subcat->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-category"></div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="form-control-label">Title:

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[2]}}'
                                   data-page="{{$view_name}}"
                                   data-id="2"
                                ></i>
                            </label>
                            <input type="text" class="form-control" name="title" id="title" value="{{$item->title}}">
                            <div class="form-control-feedback error-title"></div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description:

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[3]}}'
                                   data-page="{{$view_name}}"
                                   data-id="3"
                                ></i>
                            </label>
                            <textarea class="form-control m-input--square minh-100" name="description" id="description">{{$item->description}}</textarea>
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

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[4]}}'
                                   data-page="{{$view_name}}"
                                   data-id="4"
                                ></i>

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

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[5]}}'
                                   data-page="{{$view_name}}"
                                   data-id="5"
                                ></i>

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

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[7]}}'
                                   data-page="{{$view_name}}"
                                   data-id="7"
                                ></i>

                            </table>
                        </div>
                        <x-form.galleryOrder order="{{$item->order}}"/>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="thumbnail" class="form-control-label">Upload Image</label>
                            <input type="file" name="image" id="slimInput" />
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="featured" class="form-control-label">Featured?

                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[9]}}'
                                       data-page="{{$view_name}}"
                                       data-id="9"
                                    ></i>
                                </label>
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
                                <label for="new" class="form-control-label">New?

                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[10]}}'
                                       data-page="{{$view_name}}"
                                       data-id="10"
                                    ></i>
                                </label>
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
                                <label for="status" class="form-control-label">Approve?

                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[11]}}'
                                       data-page="{{$view_name}}"
                                       data-id="11"
                                    ></i>
                                </label>
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
                    <a href="{{route('admin.portfolio.item.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var ratio_width = "{{config("custom.variable.portfolio_image_ratio_width")}}",
            ratio_height="{{config("custom.variable.portfolio_image_ratio_height")}}",
            category = "{{$item->category_id}}",
            item_id = "{{$item->id}}";
        @if($item->getFirstMediaUrl("thumbnail"))
            window.thumbNailUrl = '{{$item->getFirstMediaUrl("thumbnail")}}';
        @endif
    </script>
    <script src="{{asset('assets/js/admin/portfolio/itemEdit.js')}}"></script>
@endsection
