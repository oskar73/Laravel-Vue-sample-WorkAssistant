@extends('layouts.master')

@section('title', 'Notifications')
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
                <a href="{{ route('notification', ['role' => 'account']) }}" class="m-nav__link">
                    <span class="m-nav__link-text">Notifications</span>
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
        <a href="/{{user()->hasRole("admin")?'admin':'account'}}/notifications" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Back</a>
    </div>
@endsection

@section('content')
    {!! $notification->data['body'] !!}
@endsection
@section('script')
    <script>$('*[contenteditable]').removeAttr('contenteditable').blur();</script>
@endsection
