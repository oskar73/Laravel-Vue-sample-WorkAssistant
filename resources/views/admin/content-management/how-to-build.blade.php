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
            <form method="post" action="{{route('admin.home.update-how-to-build')}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Title</label>
                                <div class="input-group">
                                    <input type="text" value="{{$data['title']??''}}" class="form-control m-input" name="title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="10">{{$data['description']??''}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">Item1 Title</label>
                                <div class="input-group">
                                    <input type="text" value="{{$data['items'][0]['title']??''}}" class="form-control m-input" name="items[0][title]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Item1 Description</label>
                                <textarea name="items[0][description]" class="form-control" rows="3">{{$data['items'][0]['description']??''}}</textarea>
                            </div>

                            <div class="form-group m-form__group mt-5">
                                <label for="exampleInputEmail1">Item2 Title</label>
                                <div class="input-group">
                                    <input type="text" value="{{$data['items'][1]['title']??''}}" class="form-control m-input" name="items[1][title]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Item2 Description</label>
                                <textarea name="items[1][description]" class="form-control" rows="3">{{$data['items'][1]['description']??''}}</textarea>
                            </div>

                            <div class="form-group m-form__group mt-5">
                                <label for="exampleInputEmail1">Item3 Title</label>
                                <div class="input-group">
                                    <input type="text" value="{{$data['items'][2]['title']??''}}" class="form-control m-input" name="items[2][title]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Item3 Description</label>
                                <textarea name="items[2][description]" class="form-control" rows="3">{{$data['items'][2]['description']??''}}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
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
