@extends('layouts.master')

@section('title', 'Newsletter Advertisement Listing Edit')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/calendar/fullcalendar.css')}}">
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
                    <span class="m-nav__link-text">Newsletter Advertisement</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{route('admin.newsletterAds.listing.index')}}" class="m-nav__link">
                    <span class="m-nav__link-text">Listing</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Edit</span>
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
    <form action="{{route('admin.newsletterAds.listing.update', $listing->id)}}" id="submitForm">
        @csrf
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
            <div class="m-portlet__body  px-3 px-md-5">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="lightgallery">
                            <a href="{{$listing->position->getFirstMediaUrl("thumbnail")}}"
                               class="progressive replace h-cursor"
                            >
                                <img src="{{$listing->position->getFirstMediaUrl("thumbnail")}}" alt=""
                                     class="preview w-100">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        {{$listing->position->name}} <br><br>
                        {{$listing->position->description}}
                    </div>
                </div>
                <div class="font-size20">Listing Detail</div>
                <div class="mt-3 border border-success p-2 pt-4">
                    <div class="row">
                        <div class="col-md-7 mb-2">
                            <div class="form-group">
                                <label for="image" class="form-control-label">Image ({{$listing->position->type->width}}
                                    x{{$listing->position->type->height}}
                                    px)</label>
                                <input type="file" accept="image/*" class="form-control m-input--square" id="image"
                                       name="image" data-target="image_preview">
                                <div class="form-control-feedback error-image"></div>
                                <img id="image_preview" class="border border-success mt-2"
                                     style="max-width:100%;width:{{$listing->position->type->width}}px;height:{{$listing->position->type->height}}px"
                                     src="{{$listing->getFirstMediaUrl("image")}}" />
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-control-label">URL (start with http:// or
                                    https://):</label>
                                <input type="url" class="form-control" name="url" id="url" value="{{$listing->url}}">
                                <div class="form-control-feedback error-url"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mt-3 form-group">
                                <label>Price Option</label>
                                <div class="m-radio-list">
                                    <label class="m-radio m-radio--state-primary">
                                        <input type="radio" name="price" value="{{$listing->price->id}}" checked>
                                        <p class="slashed_price_text d-inline-block mb-0 ml-0">
                                            ${{$listing->price->slashed_price}}</p> ${{$listing->price->price}}
                                        / {{$listing->price->type=='period'? numToDay($listing->price->period) : $listing->price->impression . " imps"}}
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-3 form-group">
                                        <label for="status" class="form-control-label">Status:</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="approved" @if($listing->status=='approved') selected @endif>
                                                Approved
                                            </option>
                                            <option value="pending" @if($listing->status=='pending') selected @endif>
                                                Pending
                                            </option>
                                            <option value="denied" @if($listing->status=='denied') selected @endif>
                                                Denied
                                            </option>
                                            <option value="expired" @if($listing->status=='expired') selected @endif>
                                                Expired
                                            </option>
                                            <option value="paid" @if($listing->status=='paid') selected @endif>Newly
                                                Paid
                                            </option>
                                        </select>
                                        <div class="form-control-feedback error-status"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-3 form-group">
                                        <label for="notify_status" class="form-control-label">Send Notification</label>
                                        <select name="notify_status" id="notify_status" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes (Default Notification)</option>
                                            <option value="2">Yes (Custom Notification)</option>
                                        </select>
                                        <div class="form-control-feedback error-notify_status"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 form-group">
                                <label for="customer" class="form-control-label">Choose Customer</label>
                                <select name="customer" id="customer" class="select2 m-select2">
                                    <option value="{{$listing->user->id}}" selected>{{$listing->user->name}}
                                        ({{$listing->user->email}})
                                    </option>
                                </select>
                                <div class="form-control-feedback error-customer"></div>
                            </div>
                            @if($listing->price->type=='impression')
                                <div class="impression_area row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="impression_number" class="form-control-label">Total
                                                Impression:</label>
                                            <input type="text" class="form-control" name="impression_number"
                                                   id="impression_number" value="{{$listing->impression_number}}">
                                            <div class="form-control-feedback error-impression_number"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="current_number" class="form-control-label">Current
                                                Impression:</label>
                                            <input type="text" class="form-control" name="current_number"
                                                   id="current_number" value="{{$listing->current_number}}">
                                            <div class="form-control-feedback error-current_number"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-5 text-right">
                                <a href="{{route('admin.newsletterAds.listing.index')}}"
                                   class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
                                <button type="submit"
                                        class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    @if($listing->price->type=='period')
                        <div class="calendar_area">
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="calendar"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="m-alert m-alert--outline m-alert--square alert alert-info alert-dismissible fade show"
                                         role="alert">
                                        Select Period
                                    </div>
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table ajaxTable datatable">
                                            <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Price ($)</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="dynamic_date_field">
                                            @foreach($listing->events as $key=>$event)
                                                <tr id="rowe{{$event->id}}" class="picked_row">
                                                    <td><input type="text" class="jgjformshow" name="start[]"
                                                               id="starte{{$event->id}}" value="{{$event->start_date}}"
                                                               readonly /></td>
                                                    <td><input type="text" name="end[]" class="jgjformshow"
                                                               id="ende{{$event->id}}" value="{{$event->end_date}}"
                                                               readonly /></td>
                                                    <td><input type="text" class="jgjformshow" name="priceval"
                                                               id="pricee{{$event->id}}"
                                                               value="{{ formatNumber($listing->price->price)}}"
                                                               readonly /></td>
                                                    <td>
                                                        <button type="button" data-id="e{{$event->id}}"
                                                                data-price="{{$listing->price->price}}"
                                                                class="btn-danger btn btn-sm btn_remove">X
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @php
                                            $t_price = (($key?? 0)+1)*$listing->price->price;
                                        @endphp
                                        <div class="form-group text-right">
                                            <div class="font-size20">Total Price : $<span
                                                        class="total_price">{{formatNumber($t_price)}}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal fade" id="notification_modal" tabindex="-1" role="dialog" data-backdrop="static"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Custom Notification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="notification" id="notification">
                                <h3>Hello {username}!</h3>
                                <p>Administrator edited your blog advertisement listing. <br> Please check in detail by clicking below link.</p>
                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn m-btn--square btn-outline-primary" data-dismiss="modal">Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/calendar/fullcalendar.js') }}"></script>
    <script>var price_obj = @JSON($listing->price), type =@JSON($listing->position->type),
        t_price = "{{$t_price?? ''}}",
        listing_id = "{{$listing->id}}"</script>
    <script src="{{asset('assets/js/admin/newsletterAds/listingEdit.js')}}"></script>
    <script src="{{asset('assets/js/dev2/calendar-hover.js')}}"></script>
@endsection
