@extends('layouts.master')

@section('title', 'Blog Post Detail')
@section('style')

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
                    <span class="m-nav__link-text">Post Detail</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="ml-auto">
        <a href="{{route('user.blog.index')}}" class="btn m-btn--square btn-outline-primary">Back</a>
        <a href="{{route('user.blog.edit', $post->id)}}" class="btn m-btn--square btn-outline-info">Edit</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            @if($post->status!='approved'&&$post->denied_reason!=null)
                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="m-alert__icon">
                        <i class="flaticon-exclamation-1"></i>
                        <span></span>
                    </div>
                    <div class="m-alert__text">
                        <strong>Denied Reason! </strong> {{$post->denied_reason}}
                    </div>
                    <div class="m-alert__close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                </div>
            @endif
            <div class="m-portlet m-portlet--mobile" m-portlet="true">
                <div class="m-portlet__head bg-333">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">

                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                    <i class="la la-expand  text-white"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body m_blog_post_area">
                    <div class="container">
                        <h3 class="br-padding-15">{{ $post->title }}</h3>
                        <hr>
                        <p>Posted By <b> <a href="javascript:;">{{ $post->user->name }}</a></b> on {{ $post->created_at }}</p>

                        <a href="{{$post->getFirstMediaUrl('image')}}" class="w-100 progressive replace m-auto">
                            <img src="{{$post->getFirstMediaUrl('image', 'thumb')}}"
                                 alt="{{$post->title}}"
                                 class="w-100 preview"
                            >
                        </a>

                        <div class="mt-3 br-padding-15">{!!html_entity_decode($post->body)!!}</div>
                    </div>
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
                                    <a href="#" m-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
                                        <i class="la la-angle-down  text-white"></i>
                                    </a>
                                </li>
                                <li class="m-portlet__nav-item">
                                    <a href="#" m-portlet-tool="fullscreen" class="m-portlet__nav-link m-portlet__nav-link--icon">
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
                                                <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                                                <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                            </td>
                                            <td class="text-center">
                                                <figure data-href="{{$image->getUrl()}}" class="width-150 progressive replace m-auto">
                                                    <img class='width-150 preview' src="{{$image->getUrl('thumb')}}"/>
                                                </figure>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="link_area">
                                    @foreach($post->getLinks() as $key1=>$link)
                                        <tr>
                                            <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}" readonly></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="video_area">
                                @foreach($post->getMedia('video') as $key2=>$video)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square" value="{{$video->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="example_input_full_name">Choose Gallery Order:</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="1" @if($post->gallery_order==1) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Blog Post

                                                    <hr/>

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
                                                <input type="radio" name="order" value="0" @if($post->gallery_order==0) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                     Gallery Area

                                                    <hr/>

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
        <div class="col-lg-4 col-md-12">
            <div class="m-portlet m-portlet--brand m-portlet--head-solid-bg">
                <div class="m-portlet__head" style="height:3.1rem">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Post Status
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:1rem;">
                    @if($post->status=='approved')
                        <a href="javascript:void(0);" class="btn m-btn--square btn-outline-success">Approved</a>
                    @elseif($post->status=='denied')
                        <a href="javascript:void(0);" class="btn m-btn--square btn-outline-danger">Denied</a>
                    @elseif($post->status=='pending')
                        <a href="javascript:void(0);" class="btn m-btn--square btn-outline-info">Pending Approval</a>
                    @else
                        <a href="javascript:void(0);" class="btn m-btn--square btn-outline-primary">{{$post->status}}</a>
                    @endif
                </div>
            </div>
            <div class="m-portlet m-portlet--primary m-portlet--head-solid-bg">
                <div class="m-portlet__head" style="height:3.1rem">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Blogging Type
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:1rem;">
                    @if($post->is_free==true)
                        <button type="button" class="btn m-btn--square btn-outline-primary p-2">Free Blogging</button>
                    @else
                        <button type="button" class="btn m-btn--square btn-outline-info p-2">Paid Blogging
                            @if($post->package)
                                : (<a href="{{route('user.purchase.blog.detail', $post->package->id?? 0)}}">{{$post->package->getName()}}</a>)
                            @endif
                        </button>
                    @endif
                </div>
            </div>
            <div class="m-portlet m-portlet--success m-portlet--head-solid-bg">
                <div class="m-portlet__head" style="height:3.1rem">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Category
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:1rem;">
                    <span class="btn m-btn--square btn-outline-primary p-2 ">
                        {{$post->category->name?? 'Deleted Category'}}
                    </span>
                </div>
            </div>
            <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head" style="height:3.1rem">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Tags
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:1rem;">
                    @foreach($post->tags as $tag)
                        <span class="btn m-btn--square btn-outline-info p-2">
                            @if(!empty($tag->name)){{$tag->name}}@endif
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="denied_reason_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Deny Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="denied_reason_form" method="post" >
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="denied_reason" class="form-control-label">Reason:</label>
                            <textarea class="form-control" name="denied_reason" id="denied_reason" style="height:100px;"></textarea>
                            <div class="form-control-feedback error-denied_reason"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn m-btn--square  btn-outline-primary" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn m-btn--square  btn-outline-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
