@extends('layouts.master')

@section('title', 'Welcome Video')
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
    <div class="m-portlet m-portlet--mobile md-pt-50" >
        <div class="m-portlet__body">
            <form method="post" action="{{route('admin.welcome-video.update')}}" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Video</label>
                                <div class="input-group">
                                   <div class="input-group-append">
                                       <input type="file" accept="video/*" name="video" id="welcome-video-upload" hidden onchange="handleChange()" />
                                       <label class="input-group-text" for="welcome-video-upload"><b>Upload</b></label>
                                       <div class="tw-flex tw-items-center" id='name-of-file'></div>
                                   </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Current Video</label>
                                <div class="input-group">
                                    <div class="tw-flex tw-justify-center tw-flex-col tw-h-full tw-w-full">
                                        <video controls style="height:300px !important; width: auto !important; background: black; color: white">
                                            <source src="{{option('welcome.video.url','')}}">
                                            Your browser does not support HTML5 video.
                                        </video>
                                    </div>
                                </div>
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
<script>
function handleChange(){
    let fakePath = $('#welcome-video-upload').val();
    let nameArr = fakePath.split('\\')
    let name = nameArr[nameArr.length -1]
    $('#name-of-file').html(name)
}
</script>
@endsection
