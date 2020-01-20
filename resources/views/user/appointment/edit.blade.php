@extends('layouts.master')

@section('title', 'Appointments')
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
                <a href="{{ route('user.appointment.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Appointments</span>
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
        <div class="clearfix"></div>
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" href="#"> Appointments </a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="category_area">
        <div class="m-portlet__body">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        Product: {{$list->meeting->model?->getName()?? ''}} <br>
                        Category: {{$list->category->name?? ''}} <br>
                        Date: {{$list->date?? ''}} <br>
                        Time: {{$list->start_time}} ~ {{$list->end_time}} <br>
                        Status: {{ucfirst($list->status)}} <br>
                    </div>
                    <div class="col-md-4">
                        @if($list->reason!=null)
                            Reason: {{$list->reason}} <br>
                        @endif
                        @if($list->description!=null)
                            Description: {{$list->description}} <br>
                        @endif
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{route('user.appointment.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Back</a>
                        @if($list->date>=\Carbon\Carbon::now()->toDateString())
                            @if($list->status!='canceled')
                                <span class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger mb-2 cancelBtn">Cancel</span>
                            @endif
                            <span class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2" id="smtBtn">Submit</span>
                        @endif
                    </div>
                </div>
                @if($list->date>=\Carbon\Carbon::now()->toDateString())
                    <br>
                    <h3 class="tw-text-xl tw-font-bold">Reschedule</h3>
                    <hr>
                    <div class="mt-3">
                        <p class="fs-20">Choose available product.</p>
                        @foreach($meetings as $meeting)
                            <span class="btn m-btn--square  btn-outline-accent m-btn m-btn--custom m-1 text-dark product_item {{$meeting->available_number<=0?'unavailable':'available'}}" data-id="{{$meeting->id}}">({{$meeting->userModelToName($meeting->model_type)}}) {{$meeting->model->getName()}} ({{$meeting->available_number}})</span>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <p class="fs-20">Choose category</p>
                        @foreach($categories as $category)
                            <span class="btn m-btn--square  btn-outline-info m-btn m-btn--custom m-1 category_item" data-id="{{$category->id}}">{{$category->name}}</span>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <p class="fs-20">Choose date</p>
                        <div class="form-group maxw-300">
                            <div class='input-group'>
                                <input type="text" class="form-control m-input--square" id='choose_date' name="choose_date" readonly/>
                            </div>
                            <div class="form-control-feedback error-choose_date"></div>
                        </div>
                        <div class="choose_date_area"></div>
                        <hr>
                        <div class="period_area">

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancel_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="cancel_modal_form" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="reason" class="form-control-label">Cancel Reason:</label>
                            <textarea class="form-control m-input--square minh-100" name="reason" id="reason"></textarea>
                            <div class="form-control-feedback error-reason"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>var id = "{{$list->id}}"</script>
    <script src="{{asset('assets/js/user/appointment/edit.js')}}"></script>
@endsection
