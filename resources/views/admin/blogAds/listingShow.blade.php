@extends('layouts.master')

@section('title', 'Blog Advertisement Listing Detail')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
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
                    <span class="m-nav__link-text">Blog Advertisement</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{route('admin.blogAds.listing.index')}}" class="m-nav__link">
                    <span class="m-nav__link-text">Listing</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Detail</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.blogAds.listing.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
        <a href="{{route('admin.blogAds.listing.edit', $listing->id)}}" class="btn m-btn--square m-btn m-btn--custom btn-outline-success">Edit</a>
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="form-control-label">Status:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[1]}}'
                                   data-page="{{$view_name}}"
                                   data-id="1"
                                ></i>
                            </label>
                            <select name="status" id="status" class="form-control">
                                <option value="approve" @if($listing->status=='approved') selected @endif>Approved</option>
                                <option value="pending" @if($listing->status=='pending') selected @endif>Pending</option>
                                <option value="deny" @if($listing->status=='denied') selected @endif>Denied</option>
                                <option value="expired" @if($listing->status=='expired') selected @endif>Expired</option>
                                <option value="paid" @if($listing->status=='paid') selected @endif>Newly Paid</option>
                            </select>
                            <div class="form-control-feedback error-status"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <button type="button" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn mt-2" data-id="{{$listing->id}}">Update Status</button>
                    </div>
                </div>
                <hr>
                <div class="modal fade" id="reason_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="reason">Deny Reason
                                        <i class="la la-info-circle tooltip_icon"
                                           title='{{$tooltip[2]}}'
                                           data-page="{{$view_name}}"
                                           data-id="2"
                                        ></i>
                                    </label>
                                    <textarea name="reason" id="reason" class="form-control" rows="5">{{$listing->reason}}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn m-btn--square btn-outline-primary" data-dismiss="modal">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
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
            <div class="font-size20">Listing Detail</div>
            <div class="mt-3 border border-success p-2 pt-4">
                <div class="row">
                    <div class="col-md-7 mb-2">
                        @php
                            $type = json_decode($listing->type);
                        @endphp
                        <div class="form-group">
                            <label for="image" class="form-control-label">Image ({{$type->width}}x{{$type->height}}px)
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[3]}}'
                                   data-page="{{$view_name}}"
                                   data-id="3"
                                ></i>
                            </label>
                            <div class="form-control-feedback error-image"></div>
                            <img id="image_preview" class="border border-success mt-2" style="max-width:100%;width:{{$type->width}}px;height:{{$type->height}}px" src="{{$listing->getFirstMediaUrl("image")}}"/>
                        </div>
                        @if($type->title_char!==0)
                            <div class="form-group">
                                <label for="title" class="form-control-label">Title ({{$type->title_char}} char):
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[4]}}'
                                       data-page="{{$view_name}}"
                                       data-id="4"
                                    ></i>
                                </label>
                                <input type="text" class="form-control" name="title" id="title" maxlength="{{$type->title_char}}" value="{{$listing->title}}">
                                <div class="form-control-feedback error-title"></div>
                            </div>
                        @endif
                        @if($type->text_char!==0)
                            <div class="form-group">
                                <label for="text" class="form-control-label">Text ({{$type->text_char}} char):
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[5]}}'
                                       data-page="{{$view_name}}"
                                       data-id="5"
                                    ></i>
                                </label>
                                <textarea class="form-control m-input--square minh-100 white-space-pre-line" name="text" id="text" maxlength="{{$type->text_char}}">{{$listing->text}}</textarea>
                                <div class="form-control-feedback error-text"></div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="url" class="form-control-label">URL (start with http:// or https://):
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[6]}}'
                                   data-page="{{$view_name}}"
                                   data-id="6"
                                ></i>
                            </label>
                            <input type="url" class="form-control" name="url" id="url" value="{{$listing->url}}" readonly>
                            <div class="form-control-feedback error-url"></div>
                        </div>
                    </div>
                    <div class="col-md-5" x-data="{cta:{{$listing->cta_action? 'true': 'false'}}}">
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" id="cta_check" name="cta_check" x-on:click="cta=!cta" @if($listing->cta_action) checked @endif >
                            Enable CTA LINK
                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[7]}}'
                               data-page="{{$view_name}}"
                               data-id="7"
                            ></i>

                            <span></span>
                        </label>
                        <br>
                        <br>
                        <div class="cta_area" x-show="cta">
                            <div class="form-group">
                                <label for="cta_url" class="form-control-label">CTA URL:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[8]}}'
                                       data-page="{{$view_name}}"
                                       data-id="8"
                                    ></i>
                                </label>
                                <input type="url" class="form-control" name="cta_url" id="cta_url" value="{{$listing->cta_url}}" readonly>
                                <div class="form-control-feedback error-cta_url"></div>
                            </div>
                            <div class="form-group">
                                <label for="cta_type" class="form-control-label">Select CTA Type:
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[9]}}'
                                       data-page="{{$view_name}}"
                                       data-id="9"
                                    ></i>
                                </label>
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

                        <div class="mt-5 mb-5">
                            <a href="{{route('admin.blogAds.listing.tracking', $listing->id)}}" class="underline">Total Clicks: {{$listing->current_number}}</a>
                        </div>
                        <hr>

                        @php
                          $price = json_decode($listing->price);
                        @endphp
                        <div class="mt-3 form-group">
                            <label>Price Option
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[10]}}'
                                   data-page="{{$view_name}}"
                                   data-id="10"
                                ></i>
                            </label>
                            <div class="m-radio-list">
                                <label class="m-radio m-radio--state-primary">
                                    <input type="radio" name="price" value="{{$price->id}}" checked>
                                    <p class="slashed_price_text d-inline-block mb-0 ml-0">${{$price->slashed_price}}</p> ${{$price->price}} / {{$price->type=='period'? numToDay($price->period) : $price->impression . " imps"}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-3 form-group">
                            <div class="form-group">
                                <label for="customer" class="form-control-label">Customer
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[11]}}'
                                       data-page="{{$view_name}}"
                                       data-id="11"
                                    ></i>
                                </label>
                                <input type="text" class="form-control" name="customer" value="{{$listing->user->name}} ({{$listing->user->email}})" readonly>
                                <div class="form-control-feedback error-customer"></div>
                            </div>

                        </div>
                        @if($price->type=='impression')
                            <div class="impression_area row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="impression_number" class="form-control-label">Total Impression:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[12]}}'
                                               data-page="{{$view_name}}"
                                               data-id="12"
                                            ></i>
                                        </label>
                                        <input type="text" class="form-control" name="impression_number" id="impression_number" value="{{$listing->impression_number}}">
                                        <div class="form-control-feedback error-impression_number"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="current_number" class="form-control-label">Current Impression:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[13]}}'
                                               data-page="{{$view_name}}"
                                               data-id="13"
                                            ></i>
                                        </label>
                                        <input type="text" class="form-control" name="current_number" id="current_number" value="{{$listing->current_number}}">
                                        <div class="form-control-feedback error-current_number"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="font-size20 mt-5">Spot Detail</div>
            <div class="row">
                <div class="col-md-7">

                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="lightgallery">
                                <a href="{{$listing->spot->getFirstMediaUrl("image")}}"
                                   class="progressive replace h-cursor"
                                >
                                    <img src="{{$listing->spot->getFirstMediaUrl("image", 'thumb')}}" alt="" class="preview w-100">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            {{$listing->spot->name}} <br><br>
                            {{$listing->spot->description}}
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table ajaxTable datatable">
                            <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Price ($)</th>
                            </tr>
                            </thead>
                            <tbody id="dynamic_date_field">
                            @foreach($listing->events as $key=>$event)
                                <tr id="rowe{{$event->id}}" class="picked_row">
                                    <td><input type="text" class="jgjformshow" name="start[]" id="starte{{$event->id}}" value="{{$event->start_date}}" readonly/></td>
                                    <td><input type="text" name="end[]" class="jgjformshow" id="ende{{$event->id}}" value="{{$event->end_date}}" readonly/></td>
                                    <td><input type="text" class="jgjformshow" name="priceval" id="pricee{{$event->id}}" value="{{ formatNumber($price->price)}}" readonly/></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @php
                            $t_price = ($key+1)*$price->price;
                        @endphp
                        <div class="form-group text-right">
                            <div class="font-size20">Total Price : $<span class="total_price">{{formatNumber($t_price)}}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/blogAds/listingShow.js')}}"></script>
@endsection
