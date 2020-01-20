@extends('layouts.master')

@section('title', 'Home Video')
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
                    <span class="m-nav__link-text">Home Video</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50" >
        <div class="m-portlet__body">
            <form method="post" action="{{route('admin.video.update')}}" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Video</label>
                                <div class="input-group">
                                    <input type="text" value="{{option('home.video.url','')}}" class="form-control m-input" name="videoUrl" placeholder="Video URL">
{{--                                    <div class="input-group-append">--}}
{{--                                        <input type="file" accept="video/*" id="home-video-upload" hidden/>--}}
{{--                                        <label class="input-group-text" for="home-video-upload"><b>Upload</b></label>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="10">{{option('home.video.description','')}}</textarea>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-outline-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
@endsection
