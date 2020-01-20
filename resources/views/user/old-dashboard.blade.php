@extends('layouts.master')

@section('title', 'Admin Dashboard')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/5.1.0/introjs.min.css" />
@endsection
@section('breadcrumb')
    <div class="mr-auto">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="{{ route('user.dashboard') }}" class="m-nav__link m-nav__link--icon">
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

@php
    $quickTourItems = App\Models\QuickTour::where('status', 1)
        ->orderBy('order')
        ->get();
    $quickTourItemsFormatted = [];

    foreach ($quickTourItems as $index => $item) {
        $quickTourItemsFormatted[$item->targetID] = $item;
    }
    $quickTourItemsFormatted = optional($quickTourItemsFormatted);
@endphp

@section('content')
    <div class="tw-w-full">
        @if(count($quickTourItems))
            <div class="box-quick-tour-wrap tw-mb-4">
                <div class="box-quick-tour-button-wrap">
                    <p><a href="#" class="box-quick-tour-button">Quick Tour</a></p>
                </div>
            </div>
        @endif
        <div @if($quickTourItemsFormatted['announcement']) data-title={{ $quickTourItemsFormatted['announcement']['title'] }} data-intro={{ $quickTourItemsFormatted['announcement']['description'] }} @endif>
            @foreach($announcements as $announcement)
                <div class="custom_alert">
                    @if($announcement->user_id==user()->id)
                        <span class="remove position-absolute h-cursor" style="right:10px;top:0;font-size:20px;">
                            ×
                        </span>
                    @endif
                    <div class="custom_alert_bell">
                        <i class="flaticon-bell-1"></i>
                    </div>
                    <div class="title border-bottom">
                        {{$announcement->title}}
                    </div>
                    <div class="content pt-2">
                        {!! $announcement->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head  bg-333" data-toggle="collapse" data-target="#pfuf">
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
                    <span class="mdi mdi-chevron-up tw-hidden tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                    <span class="mdi mdi-chevron-down tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                </div>
                <div id="pfuf" class="m-portlet__body collapse">
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        @forelse($pendingForms as $form)
                            <a href="{{route('user.purchase.form.edit', $form->id)}}" class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line">
                                {{$form->title}}
                                <div class="float-right"><span class="c-badge c-badge-danger">{{$form->status}}</span></div>
                            </a>
                        @empty
                            <div class="text-center">No purchased forms</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head  bg-333" data-toggle="collapse" data-target="#coming-apps">
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
                    <span class="mdi mdi-chevron-up tw-hidden tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                    <span class="mdi mdi-chevron-down tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                </div>
                <div id="coming-apps" class="m-portlet__body collapse">
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        @forelse($comingAppointments as $appointment)
                            <a href="{{route('user.appointment.detail', $appointment->id)}}"
                               class="btn m-btn--square  btn-outline-dark m-btn m-btn--custom btn-block white-space-pre-line"
                            >
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
                <div class="m-portlet__head  bg-333" data-toggle="collapse" data-target="#opened-ticket">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text text-white">
                                Opened Tickets ({{$openedTickets->count()}})
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools tw-flex tw-justify-between tw-items-center">
                        <a href="{{route('user.ticket.index')}}" class="text-white underline">View All</a>
                    </div>
                    <span class="mdi mdi-chevron-up tw-hidden tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                    <span class="mdi mdi-chevron-down tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                </div>
                <div id="opened-ticket" class="collapse m-portlet__body">
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
                <div class="m-portlet__head  bg-333" data-toggle="collapse" data-target="#unread-notification">
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
                    <span class="mdi mdi-chevron-up tw-hidden tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                    <span class="mdi mdi-chevron-down tw-absolute tw-right-2 tw-top-3 tw-text-white tw-text-lg collapse-id"></span>
                </div>
                <div id="unread-notification" class="m-portlet__body collapse">
                    <div class="m-scrollable m-scroller" data-scrollable="true" style="max-height: 400px; overflow: auto;">
                        <div class="m-list-timeline m-list-timeline--skin-light">
                            <div class="m-list-timeline__items">
                                @forelse($notifications as $unread)
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                        <span class="m-list-timeline__text">
                                            <a href="{{route('notification.detail', ['id'=>$unread->id, 'role'=>'account'])}}" class="text-dark">{{$unread->data['subject']}}</a>
                                            <a href="{{route('notification.detail', ['id'=>$unread->id, 'role'=>'account'])}}"
                                               class="btn m-btn--square m-btn btn-sm m-btn--custom btn-outline-black p-1"
                                            >
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/5.1.0/intro.min.js"></script>

    <script>
      function getClosestScrollableParent (element) {
        let parent = element.parentNode

        while (parent) {
          if (parent.scrollHeight > parent.clientHeight) {
            return parent
          }
          parent = parent.parentNode
        }

        // If no scrollable parent is found, return null or document.documentElement as the default scrollable element
        // Change this according to your needs
        return null
      }

      $(document).ready(function () {
        $(document).on('click', '.box-quick-tour-button', function (e) {
          e.preventDefault()

          $('#m_ver_menu').scrollTop(0) //scroll top of menu div
          $('.m-menu__item.m-menu__item--submenu').removeClass('m-menu__item--open') //Close all dropdown menus

          introJs()
            .start()
            .onchange((targetElement) => {
              const scrollElm = getClosestScrollableParent(targetElement)
              if (scrollElm) {
                const { scrollTop } = scrollElm
                if (targetElement.getBoundingClientRect().bottom > window.innerHeight - 200) {
                  scrollElm.scrollTo(0, scrollTop + 200)
                }
              }
            })
        })

        $(document).on('click', '.introjs-skipbutton', function () {
          introJs().exit()
        })

        $('[data-toggle="collapse"]').on('click', function (e) {
          $(e.currentTarget).children('.collapse-id').toggleClass('tw-hidden')
        })
      })
    </script>
@endsection
