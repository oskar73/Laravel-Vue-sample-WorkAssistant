@extends('layouts.master')

@section('title', 'Newsletter Advertisement Listing Detail')
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
                    <span class="m-nav__link-text">Newsletter Ads Detail</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.newsletterAds.index')}}"
           class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
        @if($listing->status!='expired')
            <a href="{{route('user.newsletterAds.edit', $listing->id)}}"
               class="btn m-btn--square m-btn m-btn--custom btn-outline-success">Edit</a>
        @endif
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="javascript:void(0);">Listing
                    Detail</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
        <div class="m-portlet__body px-3 px-md-5">
            @if($listing->status=='denied'&&$listing->reason!=null)
                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show"
                     role="alert">
                    <div class="m-alert__icon">
                        <i class="flaticon-exclamation-1"></i>
                        <span></span>
                    </div>
                    <div class="m-alert__text">
                        <strong>Denied Reason! </strong> {{$listing->reason}}
                    </div>
                    <div class="m-alert__close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                </div>
            @endif
            <div class="mt-3 border border-success p-2 pt-4">
                <div class="row">
                    <div class="col-md-7 mb-2">
                        <div class="form-group">
                            <label for="image" class="form-control-label">Image ({{$listing->position->type->width}}
                                x{{$listing->position->type->height}}
                                px)</label>
                            <div class="form-control-feedback error-image"></div>
                            @if($listing->getFirstMediaUrl("image"))
                                <img id="image_preview" class="border border-success mt-2"
                                     style="max-width:100%;width:{{$listing->position->type->width}}px;height:{{$listing->position->type->height}}px"
                                     src="{{$listing->getFirstMediaUrl("image")}}" />
                            @else
                                <span class="c-badge c-badge-warning">No image added</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="url" class="form-control-label">URL (start with http:// or https://):</label>
                            <input type="url" class="form-control" name="url" id="url" value="{{$listing->url}}"
                                   readonly>
                            <div class="form-control-feedback error-url"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="">
                            Status <br> <span
                                    class="c-badge {{$listing->status=='approved'?'c-badge-success':'c-badge-info'}}">{{$listing->status}}</span>
                        </div>
                        @if($listing->price->type=='impression')
                            <div class="mt-5">
                                <a href="{{route('user.newsletterAds.tracking', $listing->id)}}" class="underline">Total
                                    Clicks: {{$listing->current_number}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
