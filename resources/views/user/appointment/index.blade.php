@extends('layouts.master')

@section('title', 'Appointments')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendors/calendar/fullcalendar.css')}}">
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
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Appointments</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.appointment.create')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2" id="create_modal_btn">New Appointment</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" href="#"> Appointments </a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="category_area">
        <div class="m-portlet__body">
            <div id="calendar" class="calendar_area appointment_calendar">

            </div>
            <div class="list_view mt-5">
                <div class="table-responsive">
                    <table class="table table-hover ajaxTable datatable block-datatable">
                        <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            <th >
                                Time
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Product
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody class="table_body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('assets/vendors/calendar/fullcalendar.js') }}"></script>
    <script src="{{asset('assets/js/user/appointment/index.js')}}"></script>
@endsection
