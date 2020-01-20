@extends('layouts.master')

@section('title', 'Subscribed Status')
@section('style')
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
                    <span class="m-nav__link-text">Account</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Subscribed Status</span>
                </a>
            </li>
        </ul>
    </div>
@endsection
@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#subscribed" href="javascript:void(0);">Subscribed Status</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="subscribed_area">
        <div class="m-portlet__body">
            <div class="container-fluid">
                <form action="{{route('subscribed.update', "account")}}" method="POST" id="submit_form">
                    @csrf
                    @honeypot

                   <p class="font-size20">Newsletter Subscribed Status!</p> <hr> <br>
                    <div class="d-flex justify-content-between" style="width:250px;">
                        <span class="m-switch m-switch--outline m-switch--success">
                            <label>
                                <input type="checkbox" @if($subscriber!=null&&$subscriber->status==1) checked @endif id="status" name="status">
                                <span></span>
                            </label>
                        </span>
                        <div>
                            <button type="submit" class="btn m-btn--square  btn-outline-success m-btn m-btn--custom m-btn--sm smtBtn">Update</button>
                        </div>
                    </div>
                    <div class="m-checkbox-list pl-5 sub_category @if($subscriber==null||$subscriber->status==0) d-none @endif"  style="width:250px;">
                        @foreach($categories as $category)
                            <input type="hidden" name="categories[]" value="{{$category->id}}" @if(!in_array($category->id, $unsubscribeds)) disabled="disabled" @endif>
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" value="{{$category->id}}" @if(!in_array($category->id, $unsubscribeds)) checked @endif class="category_item"> {{$category->name}}
                                <span></span>
                            </label>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mt-5">
                        <p class="font-size20 border-bottom d-inline-block">Notifications</p>
                    </div>
                    <div class="m-checkbox-list pl-5">
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="order_confirm_user" name="notifications[]" checked> Confirm Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="invoice_user" name="notifications[]" checked> Invoice Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_post_approved" name="notifications[]" checked> Blog Post Approved Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_post_denied" name="notifications[]" checked> Blog Post Denied Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_ads_approved" name="notifications[]" checked> Blog Ads Approved Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_ads_denied" name="notifications[]" checked> Blog Ads Denied Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_ads_expire_remind" name="notifications[]" checked> Blog Ads Expire Remind Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_ads_expired" name="notifications[]" checked> Blog Ads Expired Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="blog_ads_paid_remind" name="notifications[]" checked> Blog Ads Paid Remind Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="appointment_approved" name="notifications[]" checked> Appointment Approved Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="appointment_canceled" name="notifications[]" checked> Appointment Canceled Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="ticket_replied" name="notifications[]" checked> Ticket Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="purchase_followup_need_revision" name="notifications[]" checked> Purchase Form Need to fill Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="purchase_followup_completed" name="notifications[]" checked> Purchase Form Completed Notifications
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success">
                            <input type="checkbox" value="domain_expired_soon" name="notifications[]" checked> Domain Expire Notifications
                            <span></span>
                        </label>
                    </div>
                    <div class="text-right">
                        <a href="" class="btn m-btn--square  btn-outline-success m-btn m-btn--custom m-btn--sm">Update</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="result_area">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{asset('assets/js/account/subscribed.js')}}"></script>
@endsection
