@extends('layouts.master')

@section('title', 'Getting Started Video')

@section('style')
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/extra.css')}}">
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
                    <span class="m-nav__link-text">Welcome Video</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div class="m-portlet__body">
            <form method="post" action="{{route('admin.getting-started.update')}}" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input name="title" class="form-control" rows="10"
                                       value="{{option('getting-started.video.title','')}}">
                            </div>
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Video Upload</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <input type="file" accept="video/*" name="video"
                                               id="getting-started-video-upload" hidden onchange="handleChange()" />
                                        <label class="input-group-text" for="getting-started-video-upload"><b>Upload</b></label>
                                        <div class="tw-flex tw-items-center" id='name-of-file'></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">--OR--</div>
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Video URL</label>
                                <div class="input-group">
                                    <input
                                            type="text"
                                            accept="video/*"
                                            name="videoUrl"
                                            id="getting-started-video-url"
                                            class="form-control m-input"
                                            placeholder="Video URL"
                                            value="{{ option('getting-started.video.isYouTube','') ? "https://www.youtube.com/embed/" . option('getting-started.video.url','') : '' }}"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control"
                                          rows="10">{{option('getting-started.video.description','')}}</textarea>
                            </div>
                            <div class="form-group m-form__group">
                                @if (option('getting-started.video.url',''))
                                    <label for="exampleInputEmail1">Current Video</label>
                                    <div class="input-group">
                                        @if (option('getting-started.video.isYouTube',''))
                                            <iframe width="560" height="315"
                                                    src={{ "https://www.youtube.com/embed/" . option('getting-started.video.url','') }} frameborder="0"
                                                    allowfullscreen></iframe>
                                        @else
                                            <video controls
                                                   style="height:315px !important; width: 560px !important; background: black; color: white; margin:auto">
                                                <source src="{{option('getting-started.video.url','')}}">
                                                Your browser does not support HTML5 video.
                                            </video>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-outline-success">Submit</button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Complete Title</label>
                                <input type="text" name="completeTitle" class="form-control"
                                       value="{{option('getting-started.complete.title','')}}">
                            </div>
                            <div class="form-group">
                                <label>Complete Content</label>
                                <textarea class="form-control m-input--square minh-100" name="completeContent"
                                          id="complete_content">{{option('getting-started.complete.content','')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
      $(document).ready(function() {
        tinymceInit('#complete_content')
      })

      function handleChange() {
        let fakePath = $('#getting-started-video-upload').val()
        let nameArr = fakePath.split('\\')
        let name = nameArr[nameArr.length - 1]
        $('#name-of-file').html(name)
      }
    </script>
@endsection
