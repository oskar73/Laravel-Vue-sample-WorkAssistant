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
                    <span class="m-nav__link-text">User Dashboard</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="m-portlet m-portlet--mobile" >
                <div class="m-portlet__head  bg-333">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text text-white">
                                Purchase Followup Forms ({{$pendingForms->count()}})
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('user.purchase.form.index')}}" class="text-white underline">View All</a>
                    </div>
                </div>
                <div class="m-portlet__body" >
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        @foreach($pendingForms as $form)
                            <a href="{{route('user.purchase.form.edit', $form->id)}}" class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                {{$form->title}} <div class="float-right"><span class="c-badge c-badge-danger">{{$form->status}}</span></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head  bg-333">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text text-white">
                                Coming Appointments ({{$comingAppointments->count()}})
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('user.appointment.index')}}" class="text-white underline">View All</a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        @forelse($comingAppointments as $appointment)
                            <a href="{{route('user.appointment.detail', $appointment->id)}}"
                               class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                <span class="c-badge c-badge-success white-space-nowrap">{{$appointment->category->name}}</span>
                                <span class="c-badge c-badge-info white-space-nowrap"> {{$appointment->date}}</span>
                                <span class="c-badge c-badge-warning white-space-nowrap">{{$appointment->start_time}} - {{$appointment->end_time}}</span>
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
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head  bg-333">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text text-white">
                                Opened Tickets ({{$openedTickets->count()}})
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('user.ticket.index')}}" class="text-white underline">View All</a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        @forelse($openedTickets as $openedTicket)
                            <a href="{{route('user.ticket.edit', $openedTicket->id)}}" class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                <div class="float-left"><span class="c-badge c-badge-success">#{{$openedTicket->id}}</span></div>
                                {{Str::limit($openedTicket->text, 40)}}
                                <div class="float-right"><span class="c-badge {{$openedTicket->status=='opened'? 'c-badge-danger':'c-badge-success'}}">{{ucfirst($openedTicket->status)}}</span></div>
                            </a>
                        @empty
                            <div class="text-center">No opened ticket</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head  bg-333">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text text-white">
                                Unread Notifications ({{$notifications->count()}})
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="/account/notifications" class="text-white underline">View All</a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        <div class="m-list-timeline m-list-timeline--skin-light">
                            <div class="m-list-timeline__items">
                                @forelse($notifications as $unread)
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                        <span class="m-list-timeline__text">
                                            <a href="{{route('notification.detail', ['id'=>$unread->id, 'role'=>'account'])}}" class="text-dark">{{$unread->data['subject']}}</a>
                                            <a href="{{route('notification.detail', ['id'=>$unread->id, 'role'=>'account'])}}" class="btn m-btn--square m-btn btn-sm m-btn--custom btn-outline-black p-1">
                                                View
                                            </a>
                                        </span>
                                        <span class="m-list-timeline__time" style="width:100px;">{{$unread->created_at->diffForHumans()}}</span>
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
@endsection
@section('script')
@endsection
