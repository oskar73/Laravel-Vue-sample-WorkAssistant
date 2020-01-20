@extends('layouts.master')

@section('title', 'Newsletter Advertisement Listing Create')
@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/calendar/fullcalendar.css')}}">
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Newsletter Advertisement', 'Listings', 'Create']"
                             :menuLinks="['', route('admin.newsletterAds.listing.index'), '']" />
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link" href="{{route('admin.newsletterAds.listing.select')}}">Select
                    Position</a></li>
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="javascript:void(0);">Listing
                    Detail</a></li>
        </ul>
    </div>
    <form action="{{route('admin.newsletterAds.listing.store', $position->slug)}}" id="submitForm">
        @csrf
        <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
            <div class="m-portlet__body  px-3 px-md-5">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="lightgallery">
                            <a href="{{$position->getFirstMediaUrl("thumbnail")}}"
                               class="progressive replace h-cursor box-shadow"
                            >
                                <img src="{{$position->getFirstMediaUrl("thumbnail")}}" alt="" class="preview w-100">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        {{$position->name}} <br><br>
                        {{$position->description}}
                    </div>
                </div>
                <div class="font-size20">Listing Detail</div>
                <div class="mt-3 border border-success p-2 pt-4">
                    <div class="row">
                        <div class="col-md-7 mb-2">
                            <div class="form-group">
                                <label for="image" class="form-control-label">Image ({{$position->type->width}}
                                    x{{$position->type->height}}
                                    px)
                                </label>
                                <div class="slim slimdiv"
                                     style="max-width:100%;width:{{$position->type->width}}px;height:{{$position->type->height}}px"
                                     data-download="true"
                                     data-label="Drop or choose image"
                                     data-max-file-size="50"
                                     data-instant-edit="true"
                                     data-button-remove-title="Upload"
                                     data-min-size="{{$position->type->width}},{{$position->type->height}}"
                                     data-force-size="{{$position->type->width}},{{$position->type->height}}"
                                     data-size="{{$position->type->width}},{{$position->type->height}}"
                                     data-ratio="{{$position->type->width}}:{{$position->type->height}}">
                                    <input type="file" name="image" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url" class="form-control-label">URL (start with http:// or https://):
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[4]}}'
                                       data-page="{{$view_name}}"
                                       data-id="4"
                                    ></i>
                                </label>
                                <input type="url" class="form-control" name="url" id="url">
                                <div class="form-control-feedback error-url"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Price Option
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[8]}}'
                                       data-page="{{$view_name}}"
                                       data-id="8"
                                    ></i>
                                </label>
                                <div class="m-radio-list">
                                    @foreach($position->prices as $price)
                                        <label class="m-radio m-radio--state-primary">
                                            <input type="radio" name="price" value="{{$price->id}}"
                                                   @if($price->standard) checked @endif data-price="{{$price}}">
                                            <p class="slashed_price_text d-inline-block mb-0 ml-0">
                                                ${{$price->slashed_price}}</p> ${{$price->price}}/ {{$price->getUnit()}}
                                            <span></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-3 form-group">
                                        <label for="status" class="form-control-label">Status:
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[9]}}'
                                               data-page="{{$view_name}}"
                                               data-id="9"
                                            ></i>
                                        </label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="approved">Approved</option>
                                            <option value="pending">Pending</option>
                                            <option value="denied">Denied</option>
                                            <option value="expired">Expired</option>
                                            <option value="paid">Newly Paid</option>
                                        </select>
                                        <div class="form-control-feedback error-status"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-3 form-group">
                                        <label for="notify_status" class="form-control-label">Send Notification
                                            <i class="la la-info-circle tooltip_icon"
                                               title='{{$tooltip[10]}}'
                                               data-page="{{$view_name}}"
                                               data-id="10"
                                            ></i>
                                        </label>
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
                                <label for="customer" class="form-control-label">Choose Customer
                                    <i class="la la-info-circle tooltip_icon"
                                       title='{{$tooltip[11]}}'
                                       data-page="{{$view_name}}"
                                       data-id="11"
                                    ></i>
                                </label>
                                <select name="customer" id="customer" class="select2 m-select2">
                                    <option value="{{user()->id}}" selected>{{user()->name}} ({{user()->email}})
                                    </option>
                                </select>
                                <div class="form-control-feedback error-customer"></div>
                            </div>
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
                <div class="calendar_area mt-3">
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
                                    </tbody>
                                </table>
                                <div class="form-group text-right">
                                    <div class="font-size20">Total Price : $<span class="total_price">0.00</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="notification_modal" tabindex="-1" role="dialog" data-backdrop="static"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Custom Notification
                            <i class="la la-info-circle tooltip_icon"
                               title='{{$tooltip[12]}}'
                               data-page="{{$view_name}}"
                               data-id="12"
                            ></i>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="notification" id="notification">
                                <h3>Hello {username}!</h3>
                                <p>Administrator created newsletter advertisement listing for you. <br> Please check in detail by clicking below link.</p>
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
    <script>
      var price_obj = @JSON($position->standardPrice), type =@JSON($position->type),
        slug = "{{$position->slug}}"
    </script>
    <script src="{{asset('assets/js/admin/newsletterAds/listingCreate.js')}}"></script>
    <script src="{{asset('assets/js/dev2/calendar-hover.js')}}"></script>
@endsection
