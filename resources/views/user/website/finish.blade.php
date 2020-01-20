@extends('layouts.master')

@section('title', 'Getting Started New Website')
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
                <a href="{{ route('user.dashboard') }}" class="m-nav__link">
                    <span class="m-nav__link-text">User Dashboard</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Setup My New Website</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row tw-py-2 tw-px-6 tw-flex tw-flex-col tw-gap-10">
        <div class="tw-w-full tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-8">
            <div class="tw-w-full tw-h-[240px] tw-bg-white tw-shadow-2xl tw-flex tw-justify-center tw-items-center">
                <a href="{{route('user.website.editContent', $website->id)}}"
                   class="tw-text-2xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">Design
                    Website</a>
            </div>
            <div class="tw-w-full tw-h-[240px] tw-bg-white tw-shadow-2xl tw-flex tw-justify-center tw-items-center">
                <a href="{{route('graphics.category', ['slug' => 'logo'])}}"
                   class="tw-text-2xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">Design
                    Logo</a>
            </div>
            <div class="tw-w-full tw-h-[240px] tw-bg-white tw-shadow-2xl tw-flex tw-justify-center tw-items-center">
                <a href="{{route('graphics.category', ['slug' => 'favicon'])}}"
                   class="tw-text-2xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">Design
                    Favicon</a>
            </div>
            @if(in_array('appointment', json_decode($userPackage->modules)))
                <div class="tw-w-full tw-h-[240px] tw-bg-white tw-shadow-2xl tw-flex tw-justify-center tw-items-center">
                    <a href="{{route('user.appointment.listing.index')}}"
                       class="tw-text-2xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">Set
                        up Appointment</a>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
@endsection
