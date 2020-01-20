@extends('layouts.master')

@section('title', 'Blog Advertisement Listing Detail')
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
                <a href="{{ route('user.blogAds.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Blog Ads</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Blog Ads Detail</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.blogAds.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
        @if($listing->status!='expired')
            <a href="{{route('user.blogAds.edit', $listing->id)}}" class="btn m-btn--square m-btn m-btn--custom btn-outline-success">Edit</a>
        @endif
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="javascript:void(0);">Listing Detail</a></li>
        </ul>
    </div>
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
            <div class="m-portlet__body  px-3 px-md-5">
                @if($listing->status=='denied'&&$listing->reason!=null)
                    <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
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
                            @php
                                $type = json_decode($listing->type);
                            @endphp
                            <div class="form-group">
                                <label for="image" class="form-control-label">Image ({{$type->width}}x{{$type->height}}px)</label>
                                <div class="form-control-feedback error-image"></div>
                                <img id="image_preview" class="border border-success mt-2" style="max-width:100%;width:{{$type->width}}px;height:{{$type->height}}px" src="{{$listing->getFirstMediaUrl("image")}}"/>
                            </div>
                            @if($type->title_char!==0)
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Title ({{$type->title_char}} char):</label>
                                    <input type="text" class="form-control" name="title" id="title" maxlength="{{$type->title_char}}" value="{{$listing->title}}">
                                    <div class="form-control-feedback error-title"></div>
                                </div>
                            @endif
                            @if($type->text_char!==0)
                                <div class="form-group">
                                    <label for="text" class="form-control-label">Text ({{$type->text_char}} char):</label>
                                    <textarea class="form-control m-input--square minh-100 white-space-pre-line" name="text" id="text" maxlength="{{$type->text_char}}">{{$listing->text}}</textarea>
                                    <div class="form-control-feedback error-text"></div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="url" class="form-control-label">URL (start with http:// or https://):</label>
                                <input type="url" class="form-control" name="url" id="url" value="{{$listing->url}}" readonly>
                                <div class="form-control-feedback error-url"></div>
                            </div>
                        </div>
                        <div class="col-md-5" x-data="{cta:{{$listing->cta_action? 'true': 'false'}}}">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" id="cta_check" name="cta_check" x-on:click="cta=!cta" @if($listing->cta_action) checked @endif>
                                Enable CTA LINK
                                <span></span>
                            </label>
                            <br>
                            <br>
                            <div class="cta_area" x-show="cta">
                                <div class="form-group">
                                    <label for="cta_url" class="form-control-label">CTA URL:</label>
                                    <input type="url" class="form-control" name="cta_url" id="cta_url" value="{{$listing->cta_url}}" readonly>
                                    <div class="form-control-feedback error-cta_url"></div>
                                </div>
                                <div class="form-group">
                                    <label for="cta_type" class="form-control-label">Select CTA Type:</label>
                                    <select name="cta_type" id="cta_type" class="form-control">
                                        <option value="buy_now" @if($listing->cta_type=='buy_now') selected @endif>Buy Now</option>
                                        <option value="shop_now" @if($listing->cta_type=='shop_now') selected @endif>Shop Now</option>
                                        <option value="visit_now" @if($listing->cta_type=='visit_now') selected @endif>Visit Now</option>
                                        <option value="learn_more" @if($listing->cta_type=='learn_more') selected @endif>Learn More</option>
                                        <option value="join_now" @if($listing->cta_type=='join_now') selected @endif>Join Now</option>
                                    </select>
                                    <div class="form-control-feedback error-cta_type"></div>
                                </div>
                            </div>
                            <div class="mt-5">
                                Status <br> <span class="c-badge {{$listing->status=='approved'?'c-badge-success':'c-badge-info'}}">{{$listing->status}}</span>
                            </div>
                            @if(json_decode($listing->price)->type=='impression')
                            <div class="mt-5">
                                <a href="{{route('user.blogAds.tracking', $listing->id)}}" class="underline">Total Clicks: {{$listing->current_number}}</a>
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
