@extends('layouts.master')

@section('title', 'Edit Blog Post')

@section('style')
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/extra.css')}}">
@endsection

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{ route('user.blog.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Blog</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Edit Post</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <form id="submit_form" action="{{route('user.blog.update', $post->id)}}">
        @csrf
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head bg-333">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text text-white">
                                    Edit Post
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label for="title">Post Title</label>
                            <textarea type="text" class="form-control m-input m-input--square" name="title" id="title"
                                      placeholder="Title" autofocus>
                                {{$post->title}}
                            </textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="category">Choose Category</label>
                            <select class="form-control" id="category" name="category">
                                <option></option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if($post->category_id==$category->id) selected
                                            @endif data-tags="{{$category->approvedTags->pluck('id')}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-category"></div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="tag">Select Tags</label><br>
                            <select class="form-control" id="tag" name="tags[]" multiple>
                                <option></option>
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}"
                                            @if(in_array($tag->id, $post->tags->pluck("id")->toArray())) selected @endif>{{$tag->name}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-tags"></div>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="video">Video Link</label>
                            <input type="text" class="form-control m-input m-input--square" name="video" id="video"
                                   placeholder="youtube video" value="{{$post->video}}" />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="m-radio-inline p-4">
                                    <label class="m-radio">
                                        <input type="radio" name="publish" value="1"
                                               @if($post->is_published==1) checked @endif> Ready to publish to Blog
                                        <span></span>
                                    </label>
                                    <label class="m-radio">
                                        <input type="radio" name="publish" value="0"
                                               @if($post->is_published==0) checked @endif> Draft
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head bg-333">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text text-white">
                                    Featured Image
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body ">
                        <div class="form-group position-relative">
                            <label class="form-label">Upload Image</label>
                            <label for="thumbnail"
                                   class="btn btn-outline-info m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air choose_btn_container">
                                <i class="la la-edit"></i>
                            </label>
                            <input type="file" accept="image/*" class="d-none" id="thumbnail">
                            <div class="form-control-feedback error-thumbnail"></div>
                            <img id="thumbnail_image" class="w-100" src="{{$post->getFirstMediaUrl("image")}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="m-portlet m-portlet--mobile" m-portlet="true">
                    <div class="m-portlet__head bg-333">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text text-white">
                                    Description
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <a href="#" m-portlet-tool="fullscreen"
                                       class="m-portlet__nav-link m-portlet__nav-link--icon">
                                        <i class="la la-expand  text-white"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body p-1">
                        {{-- <div class="tinymce_area" id="description" style="border:1px solid #eee;">{!!html_entity_decode($post->body)!!}</div> --}}
                        <textarea id="description" name="description" hidden>{{$post->body}}</textarea>
                    </div>
                    <div class="m-portlet__foot text-right">
                        <a href="{{route('user.blog.index')}}" class="btn m-btn--square btn-outline-primary"
                           data-dismiss="modal">Back</a>
                        <button type="submit" id="submit_btn" class="btn m-btn--square btn-outline-info smtBtn">Submit
                        </button>
                    </div>
                </div>


                <div class="m-portlet m-portlet--mobile" m-portlet="true">
                    <div class="m-portlet__head bg-333">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text  text-white">
                                    Gallery
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <a href="#" m-portlet-tool="toggle"
                                       class="m-portlet__nav-link m-portlet__nav-link--icon">
                                        <i class="la la-angle-down  text-white"></i>
                                    </a>
                                </li>
                                <li class="m-portlet__nav-item">
                                    <a href="#" m-portlet-tool="fullscreen"
                                       class="m-portlet__nav-link m-portlet__nav-link--icon">
                                        <i class="la la-expand  text-white"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body p-3">

                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="image_area">
                                @foreach($post->getMedia('images') as $key=>$image)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square"
                                                   value="{{$image->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                        </td>
                                        <td class="text-center">
                                            <figure data-href="{{$image->getUrl()}}"
                                                    class="width-150 progressive replace m-auto">
                                                <img class='width-150 preview' src="{{$image->getUrl('thumb')}}" />
                                            </figure>
                                        </td>
                                        <td>
                                            <button class='btn btn-danger btn-sm delBtn'>X</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);"
                                   class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addImage">+
                                    Add Image</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="link_area">
                                @foreach($post->getLinks() as $key1=>$link)
                                    <tr>
                                        <td><input type="url" name='links[]' class="form-control m-input--square"
                                                   value="{{$link}}"></td>
                                        <td>
                                            <button class='btn btn-danger btn-sm delBtn'>X</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);"
                                   class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addLink">+ Add
                                    External Video Link</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="video_area">
                                @foreach($post->getMedia('video') as $key2=>$video)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square"
                                                   value="{{$video->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                                        </td>
                                        <td>
                                            <button class='btn btn-danger btn-sm delBtn'>X</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);"
                                   class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addVideo">+
                                    Upload Video</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="example_input_full_name">Choose Gallery Order:</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="1"
                                                       @if($post->gallery_order==1) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Blog Post

                                                    <hr />

                                                    Gallery Area
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="0"
                                                       @if($post->gallery_order==0) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                     Gallery Area

                                                    <hr />

                                                     Blog Post
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script src="https://unpkg.com/react@17.0.2/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17.0.2/umd/react-dom.production.min.js"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script src="{{asset('assets/js/user/blog/edit.js')}}"></script>
@endsection
