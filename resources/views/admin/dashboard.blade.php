@extends('layouts.master')

@section('title', 'Admin Dashboard')
@section('style')

@endsection
@section('breadcrumb')
    <div class="mr-auto">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
                    <i class="m-nav__link-icon la la-home"></i>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Dashboard</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#reports" href="#/reports"> Reports</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#analytics" href="#/analytics"> Google Analytics</a>
            </li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active" id="reports_area">
        <div class="m-portlet__body">
            <div class="border border-success p-2 py-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="modules" class="form-control-label">
                                Choose Website:
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[1]}}'
                                   data-page="{{$view_name}}"
                                   data-id="1"
                                ></i>
                            </label>
                            <select name="website" id="website" class="website" data-width="100%"
                                    data-live-search="true">
                                <option value="0">Bizinabox Website</option>
                                @foreach($websites as $website)
                                    <option value="{{$website->id}}">{{$website->name}} ({{$website->domain}})</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-website"></div>
                        </div>
                    </div>
                </div>
                <div class="dashboard_card_area">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head bg-333">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text text-white">
                                        InBasket
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body p-2 p-md-3">
                            <div class="m-scrollable m-scroller" data-scrollable="true"
                                 style="max-height: 400px; overflow: auto;">
                                <a href="{{route('admin.blog.post.index')}}"
                                   class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                    Blog Posts @if($pendingPosts)
                                        <div class="float-right"><span
                                                    class="m-nav__link-badge m-badge m-badge--danger">{{$pendingPosts}}</span>
                                        </div>
                                    @endif
                                </a>
                                <a href="{{route('admin.blog.comment.index')}}"
                                   class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                    Blog Comments @if($pendingComments)
                                        <div class="float-right"><span
                                                    class="m-nav__link-badge m-badge m-badge--danger">{{$pendingComments}}</span>
                                        </div>
                                    @endif
                                </a>
                                <a href="{{route('admin.blogAds.listing.index')}}"
                                   class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                    Blog Ads Listing @if($pendingListings)
                                        <div class="float-right"><span
                                                    class="m-nav__link-badge m-badge m-badge--danger">{{$pendingListings}}</span>
                                        </div>
                                    @endif
                                </a>
                                <a href="{{route('admin.newsletterAds.listing.index')}}"
                                   class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                    Newsletter Ads Listing @if($pendingNewsletterAdListings)
                                        <div class="float-right"><span
                                                    class="m-nav__link-badge m-badge m-badge--danger">{{$pendingNewsletterAdListings}}</span>
                                        </div>
                                    @endif
                                </a>
                                <a href="{{route('admin.purchase.form.index')}}"
                                   class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                    Purchase Followup Forms @if($pendingForms)
                                        <div class="float-right"><span
                                                    class="m-nav__link-badge m-badge m-badge--danger">{{$pendingForms}}</span>
                                        </div>
                                    @endif
                                </a>
                                <a href="{{route('admin.appointment.listing.index')}}"
                                   class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                    Appointments @if($pendingAppointments)
                                        <div class="float-right"><span
                                                    class="m-nav__link-badge m-badge m-badge--danger">{{$pendingAppointments}}</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head bg-333">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text text-white">
                                        Coming Approved Appointments ({{$comingAppointments->count()}})
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-caption">
                                <a href="{{route('admin.appointment.listing.index')}}" class="text-white underline">View
                                    All</a>
                            </div>
                        </div>
                        <div class="m-portlet__body p-2 p-md-3">
                            <div class="m-scrollable m-scroller" data-scrollable="true"
                                 style="max-height: 400px; overflow: auto;">
                                @forelse($comingAppointments as $appointment)
                                    <a href="{{route('admin.appointment.listing.detail', $appointment->id)}}"
                                       class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                        <span class="c-badge c-badge-success white-space-nowrap">{{$appointment->category->name}}</span>
                                        <span class="c-badge c-badge-info white-space-nowrap"> {{$appointment->date}}</span>
                                        <span class="c-badge c-badge-warning white-space-nowrap">{{$appointment->start_time}} - {{$appointment->end_time}}</span>
                                        <span class="c-badge c-badge-info white-space-nowrap">({{$appointment->user->name}})</span>
                                    </a>
                                @empty
                                    <div class="text-center">No coming appointment</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head bg-333">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text text-white">
                                        Opened Tickets ({{$openedTickets->count()}})
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-caption">
                                <a href="{{route('admin.ticket.item.index')}}" class="text-white underline">View All</a>
                            </div>
                        </div>
                        <div class="m-portlet__body p-2 p-md-3">
                            <div class="m-scrollable m-scroller" data-scrollable="true"
                                 style="max-height: 400px; overflow: auto;">
                                @forelse($openedTickets as $openedTicket)
                                    <a href="{{route('admin.ticket.item.edit', $openedTicket->id)}}"
                                       class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                        <div class="float-left"><span
                                                    class="c-badge c-badge-success">#{{$openedTicket->id}}</span></div>
                                        {{Str::limit($openedTicket->text, 40)}}
                                        <div class="float-right"><span
                                                    class="c-badge {{$openedTicket->status=='opened'? 'c-badge-danger':'c-badge-success'}}">{{ucfirst($openedTicket->status)}}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center">No opened ticket</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head bg-333">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text text-white">
                                        Unread Notifications ({{$notifications->count()}})
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-caption">
                                <a href="/admin/notifications" class="text-white underline">View All</a>
                            </div>
                        </div>
                        <div class="m-portlet__body p-2 p-md-3">
                            <div class="m-scrollable m-scroller" data-scrollable="true"
                                 style="max-height: 400px; overflow: auto;">
                                <div class="m-list-timeline m-list-timeline--skin-light">
                                    <div class="m-list-timeline__items">
                                        @forelse($notifications as $unread)
                                            <div class="m-list-timeline__item">
                                                <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                                <span class="m-list-timeline__text">
                                                    <a href="{{route('notification.detail', ['id'=>$unread->id, 'role'=>'admin'])}}"
                                                       class="text-dark">{{$unread->data['subject']}}</a>
                                                    <a href="{{route('notification.detail', ['id'=>$unread->id, 'role'=>'admin'])}}"
                                                       class="btn m-btn--square m-btn btn-sm m-btn--custom btn-outline-black p-1">
                                                        View
                                                    </a>
                                                </span>
                                                <span class="m-list-timeline__time"
                                                      style="width:100px;">{{$unread->created_at->diffForHumans()}}</span>
                                            </div>
                                        @empty
                                            <div class="text-center">No Notification</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area" id="analytics_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.dashboard.analytics.submit')}}" id="analytics_form" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="modules" class="form-control-label">
                                Choose Website:

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[2]}}'
                                   data-page="{{$view_name}}"
                                   data-id="2"
                                ></i>
                            </label>
                            <select name="website" id="website_analytics" class="website" data-width="100%"
                                    data-live-search="true">
                                <option value="0">Bizinabox Website</option>
                                @foreach($websites as $website)
                                    <option value="{{$website->id}}">{{$website->name}} ({{$website->domain}})</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback error-website"></div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">
                                Upload Credentials Json

                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[3]}}'
                                   data-page="{{$view_name}}"
                                   data-id="3"
                                ></i>
                                <a href="https://bizinabox.com/account/tutorial/#/basic/how-to-hook-up-google-analytics"
                                   target="_blank" class="c-badge c-badge-success hover-none">Tutorial </a>
                            </label>
                            <div></div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="json_file" name="json_file">
                                <label class="custom-file-label" for="json_file">@if($credential_json_exists)
                                        Already exist
                                    @else
                                        Choose file
                                    @endif</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="property_id">
                                PROPERTY ID
                                <i class="la la-info-circle tooltip_icon"
                                   title='{{$tooltip[4]}}'
                                   data-page="{{$view_name}}"
                                   data-id="4"
                                ></i>
                            </label>
                            <input type="text" class="form-control m-input m-input--square" id="property_id"
                                   name="property_id" value="{{option("analytics_property_id", "")}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <br>
                            <input type="submit" class="btn btn-success analytics_smt_btn m-1" value="Submit">
                            @if($credential_json_exists)
                                <a href="#" class="btn btn-danger revoke_btn m-1">Revoke Json File</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
            @if($credential_json_exists)
                <x-admin.analytics></x-admin.analytics>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/chart/chart.min.js')}}"></script>
    <script src="{{asset('assets/js/admin/dashboard.js')}}"></script>
    @if($credential_json_exists && config('app.env') !== 'local')
        <script src="{{asset('assets/js/admin/analytics.js')}}"></script>
    @endif
@endsection
