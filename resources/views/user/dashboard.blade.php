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

        <div class="row" style="font-family: Segoe UI">
            @foreach ($packages as $package)
                <div class="col-xl-2 col-md-3 col-sm-6 col-12 mb-4">
                    <a data-id="{{ $package->id }}"
                       href="{{ route('user.website.getting.resume', ['id' => $package->id]) }}"
                       class="new-click-btn tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                       style="text-decoration: none">
                        @if ($package->progresses->count())
                            <div class="tw-absolute tw-rounded-full tw-top-2 tw-left-2 tw-border-[#0574bf] tw-bg-[#0574bf11] tw-border tw-text-base tw-px-4 tw-py-1 tw-text-[#0574bf] tw-flex tw-items-center">
                                In Progress
                            </div>
                        @else
                            <div data-id="{{ $package->id }}"
                                 class="new-click-badge tw-hidden tw-absolute tw-rounded-full tw-top-2 tw-left-2 tw-border-[#86bc42] tw-bg-[#86bc4211] tw-border tw-text-base tw-px-4 tw-py-1 tw-text-[#86bc42] tw-flex tw-items-center">
                                New
                            </div>
                        @endif

                        <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                            <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                {{ $package->progresses->first()->data['name'] ?? "Set up New Website"}}
                            </h5>
                        </div>
                    </a>
                </div>
                @foreach($package->websites as $website)
                    <div class="col-xl-2 col-md-3 col-sm-6 col-12 mb-4">
                        <div class="tw-w-full tw-shadow tw-bg-gray-200">
                            <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-between tw-items-center">
                                    {{ $website->name }}
                                    <i data-id="{{ $website->id }}"
                                       class='fa fa-ellipsis-h fa-sm tw-text-gray-600 tw-cursor-pointer'></i>
                                </h5>
                            </div>
                            <div class="tw-bg-white tw-px-4 sm:tw-px-6">
                                <ul class="tw-mb-4 tw-space-y-3 sortable tw-mt-2 tw-py-3">
                                    <li>
                                        <a href="{{ route('user.website.getting.resume', ['id' => $package->id]) }}"
                                           class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                           style="text-decoration: none">
                                            <span class="tw-flex-1">Website Setup Details</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('user.website.editContent', $website->id)}}"
                                           class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                           style="text-decoration: none">
                                            <span class="tw-flex-1">Website Design</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('graphics.category', ['slug' => 'logo'])}}"
                                           class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                           style="text-decoration: none">
                                            <span class="tw-flex-1">Logo Design</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('graphics.category', ['slug' => 'favicon'])}}"
                                           class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                           style="text-decoration: none">
                                            <span class="tw-flex-1">Favicon Design</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

            @foreach ($widgets as $widget)
                <div class="col-xl-2 col-md-3 col-sm-6 col-12 mb-4">
                    <div class="tw-w-full tw-shadow tw-bg-gray-200">
                        <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                            <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-between tw-items-center">
                                @if ($widget->link)
                                    <a href="{{ $widget->link }}" class="hover:tw-text-gray-900"
                                       style="text-decoration: none">{{ $widget->title }}</a>
                                @else
                                    {{ $widget->title }}
                                @endif
                                <i data-id="{{ $widget->id }}"
                                   class='fa fa-ellipsis-h fa-sm tw-text-gray-600 tw-cursor-pointer widget-toggle-btn'></i>
                            </h5>
                            {{-- <p class="tw-text-sm tw-font-normal tw-text-gray-500">{{ $widget->description }}</p> --}}
                        </div>

                        @if (count($widget->items))
                            <div class="widget-list-{{ $widget->id }} tw-bg-white tw-px-4 sm:tw-px-6">
                                <ul class="tw-mb-4 tw-space-y-3 sortable tw-mt-2 tw-py-3">
                                    @foreach ($widget->items as $item)
                                        <li>
                                            <a href="{{ $item->url }}"
                                               class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                               style="text-decoration: none">
                                                <span class="tw-flex-1">{{ $item->title }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            @foreach ($newsletterAds as $newsletterAd)
                @if($newsletterAd->status !== 'approved')
                    <div class="col-xl-2 col-md-3 col-sm-6 col-12 mb-4">
                        <a data-id="{{ $newsletterAd->id }}"
                           href="{{ route('user.newsletterAds.edit', ['id' => $newsletterAd->id]) }}"
                           class="new-click-btn tw-block tw-w-full tw-shadow tw-bg-white tw-cursor-pointer tw-relative tw-text-center tw-group"
                           style="text-decoration: none">
                            @if ($newsletterAd->url)
                                @if($newsletterAd->status === 'pending')
                                    <div class="tw-absolute tw-rounded-full tw-top-2 tw-left-2 tw-border-[#0574bf] tw-bg-[#0574bf11] tw-border tw-text-base tw-px-4 tw-py-1 tw-text-[#0574bf] tw-flex tw-items-center">
                                        Pending Admin Approval
                                    </div>
                                @else
                                    <div class="tw-absolute tw-rounded-full tw-top-2 tw-left-2 tw-border-[#0574bf] tw-bg-[#0574bf11] tw-border tw-text-base tw-px-4 tw-py-1 tw-text-[#0574bf] tw-flex tw-items-center">
                                        In Progress
                                    </div>
                                @endif
                            @else
                                <div class="new-click-badge tw-hidden tw-absolute tw-rounded-full tw-top-2 tw-left-2 tw-border-[#86bc42] tw-bg-[#86bc4211] tw-border tw-text-base tw-px-4 tw-py-1 tw-text-[#86bc42] tw-flex tw-items-center">
                                    New
                                </div>
                            @endif
                            <div class="tw-px-4 sm:tw-px-6 tw-py-8 sm:tw-py-12">
                                <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-flex tw-justify-center tw-items-center tw-text-[#0574bf] group-hover:tw-text-[#0574bfcc]">
                                    {{ $newsletterAd->position->name }} Purchased
                                </h5>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
            @if(count($newsletterAds->where('status', 'approved')))
                <div class="col-xl-2 col-md-3 col-sm-6 col-12 mb-4">
                    <div class="tw-w-full tw-shadow tw-bg-gray-200">
                        <div class="tw-px-4 sm:tw-px-6 tw-py-2 sm:tw-py-3">
                            <h5 class="tw-text-base md:tw-text-xl tw-font-bold tw-text-gray-900 tw-flex tw-justify-between tw-items-center">
                                Bizinabox Newsletter Advertise
                                <i class='fa fa-ellipsis-h fa-sm tw-text-gray-600 tw-cursor-pointer'></i>
                            </h5>
                        </div>
                        <div class="tw-bg-white tw-px-4 sm:tw-px-6">
                            <ul class="tw-mb-4 tw-space-y-3 sortable tw-mt-2 tw-py-3">
                                <li>
                                    <a href="{{ route('newsletterAds.index') }}"
                                       class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                       style="text-decoration: none">
                                        <span class="tw-flex-1">New Advertise</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('user.newsletterAds.index')}}"
                                       class="tw-flex tw-items-center tw-text-base md:tw-text-xl tw-font-bold tw-rounded-lg tw-group tw-text-[#0574bf] hover:tw-text-[#0574bfcc]"
                                       style="text-decoration: none">
                                        <span class="tw-flex-1">My Newsletter Advertise</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/account/widget.js') }}"></script>
@endsection
