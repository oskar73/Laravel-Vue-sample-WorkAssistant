
@extends('layouts.master')

@section('title', 'Blog Posts')

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
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Portfolio</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Create Portfolio</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('user.portfolio.store')}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf

                @if($portfolio)
                    <input name="id" hidden value="{{$portfolio->id}}" />
                @endif

                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="footer" class="form-control-label">Choose Category</label>
                            <select name="category" id="category" class="category selectpicker" data-live-search="true" data-width="100%">
                                <option value="" disabled selected>Choose Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{$category->id === ($portfolio->category->id??'') ? 'selected':''}}>{{$category->name}}</option>
                                    @foreach($category->approvedSubCategories as $subcat)
                                        <option  value="{{$subcat->id}}"  {{$subcat->id === ($portfolio->category->id??'') ? 'selected':''}}>{{$category->name}} --> {{$subcat->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-category"></div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="form-control-label">Title</label>
                            <input type="text" class="form-control" name="title" value="{{$portfolio->title??''}}" id="title" >
                            <div class="form-control-feedback error-title"></div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control m-input--square minh-100" name="description" id="description">{{$portfolio->description??''}}</textarea>
                            <div class="form-control-feedback error-description"></div>
                        </div>
                        <x-form.addImage :images="$portfolio?$portfolio->images():[]" ></x-form.addImage>

                        <x-form.addLink :links="$portfolio?$portfolio->links():[]"></x-form.addLink>

                        <x-form.uploadVideo :videos="$portfolio?$portfolio->videos():[]"></x-form.uploadVideo>

                        <x-form.galleryOrder :order="$portfolio->order??1"></x-form.galleryOrder>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="thumbnail" class="form-control-label">Upload Image</label>
                            <div class="slimdiv">
                                <input type="file" name="image" @if($portfolio) value="{{$portfolio->getFirstMediaUrl('thumbnail')}}" @endif id="slimInput"/>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-4">
                                <label for="featured" class="form-control-label">Featured</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" {{($portfolio->status??'1')==1?'featured':''}} name="featured" id="featured">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="new" class="form-control-label">New</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" {{($portfolio->new??'1')==1?'checked':''}} name="new" id="new">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="status" class="form-control-label">Approve</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" {{($portfolio->status??'1')==1?'checked':''}} name="status" id="status">
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
        @if($portfolio)
            window.thumbNailUrl = '{{$portfolio->getFirstMediaUrl('thumbnail')}}';
        @endif
    </script>
    <script src="{{asset('assets/js/user/portfolio/edit.js')}}"></script>
@endsection
