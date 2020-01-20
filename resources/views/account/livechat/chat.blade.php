@extends('layouts.master')

@section('title', 'LiveChat Chat Box')
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
                    <span class="m-nav__link-text">LiveChat</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Chat Box</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="javascript:void(0);" class="text-dark open_chat"><i class="fa fa-external-link-alt"></i> Open Chat Window</a>
    </div>
@endsection

@section('content')
    <iframe src="@if(Request::is('client/chat*')){{route('client.chatbox.index')}}@elseif(Request::is('employee/chat*')){{route('employee.chatbox.index')}} @endif" frameborder="0" class="chat_frame"></iframe>
@endsection
@section('script')
    <script>
        $(".open_chat").on("click", function() {
            window.open('@if(Request::is('client/chat*')){{route('client.chatbox.index')}}@elseif(Request::is('employee/chat*')){{route('employee.chatbox.index')}} @endif', "Chat Box", "width=800, height=600");
        })
    </script>
@endsection
