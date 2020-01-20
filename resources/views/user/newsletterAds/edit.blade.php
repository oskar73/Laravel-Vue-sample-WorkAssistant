@extends('layouts.master')

@section('title', 'Newsletter Advertisement Listing Edit')
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
                <a href="{{ route('user.newsletterAds.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Newsletter Ads</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Newsletter Ads Edit</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="javascript:void(0);">Listing
                    Detail</a></li>
        </ul>
    </div>
    <form action="{{route('user.newsletterAds.update', $listing->id)}}" id="submitForm">
        @csrf
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
            <div class="m-portlet__body  px-3 px-md-5">
                <div class="mt-3 border border-success p-2 pt-4">
                    <div class="row">
                        <div class="col-md-7 mb-2">
                            <div class="form-group slimdiv">
                                <label for="thumbnail" class="form-control-label">Upload Image</label>
                                <input type="file" accept="image/*" name="image" id="thumbnail"
                                       data-target="image_preview" data-url="{{$listing->getFirstMediaUrl("image")}}" />
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-control-label">URL (start with http:// or
                                    https://):</label>
                                <input type="url" class="form-control" name="url" id="url" value="{{$listing->url}}">
                                <div class="form-control-feedback error-url"></div>
                            </div>
                        </div>
                        <div class="col-md-5">

                        </div>
                    </div>
                </div>
                <div class="mt-5 text-right">
                    <a href="{{route('user.newsletterAds.index')}}"
                       class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script>
      let type = @JSON($listing->position->type);
    </script>
    <script src="{{asset('assets/js/user/newsletterAds/edit.js')}}"></script>
@endsection
