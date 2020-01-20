@extends('layouts.master')

@section('title', 'Newsletter Advertisement Listing')
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
                    <span class="m-nav__link-text">Newsletter Advertisement</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="{{route('admin.newsletterAds.listing.index')}}" class="m-nav__link">
                    <span class="m-nav__link-text">Listing</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Select Position</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.blogAds.listing.index')}}"
           class="btn m-btn--square m-btn m-btn--custom btn-outline-info">Back</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#position" href="#/position">Select
                    Position</a></li>
            <li class="tab-item"><a class="tab-link" href="javascript:void(0);">Listing Detail</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="position_area">
        <div class="m-portlet__body" x-data="{table:false}">
            <div class="text-right">
                <span class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2 tooltip_3"
                      x-bind:class="table? 'active': ''" x-on:click="table=true" title="Table View"><i
                            class="fa fa-th"></i></span>
                <span class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2 tooltip_3"
                      x-bind:class="table? '': 'active'" x-on:click="table=false" title="List View"><i
                            class="fa fa-list-ul"></i></span>
            </div>
            <div class="table_view" x-show="table">
                <table class="table table-hover ajaxTable datatable">
                    <thead>
                    <tr>
                        <th class="text-center">Position Name</th>
                        <th class="text-center">Ad Type</th>
                        <th class="text-center">Position Price</th>
                        <th class="text-center">Select</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($positions as $position)
                        <tr>
                            <td class="text-center">{{$position->name}}</td>
                            <td class="text-center">
                                {{$position->type->name}} <br>
                                (Width:{{$position->type->width}}px, Height:{{$position->type->height}}px)
                            </td>
                            <td class="text-center">
                                @if($position->standardPrice->slashed_price)
                                    <span class="slashed_price_text">${{$position->standardPrice->slashed_price}}</span>
                                @endif
                                <span>${{$position->standardPrice->price}} / {{$position->standardPrice->getUnit()}}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{route('admin.newsletterAds.listing.create', $position->slug)}}"
                                   class="btn btn-outline-info">Select</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="list_view" x-show="!table">
                <div class="row justify-content-center">
                    @foreach($positions as $position)
                        <div class="col-lg-3 col-md-4 mb-3">
                            <div class="spot-grid h-100">
                                <a href="{{route('admin.newsletterAds.listing.create', $position->slug)}}"
                                   class="hover-none text-dark">
                                    <div class="position-relative">
                                        <figure data-href="{{$position->getFirstMediaUrl('thumbnail')}}"
                                                class="w-100 progressive replace img-bg-effect-container height-300 mb-0">
                                            <img src="{{$position->getFirstMediaUrl('thumbnail')}}"
                                                 alt="{{$position->name}}"
                                                 class="preview img-bg img-bg-effect z-index-99" />
                                        </figure>
                                    </div>
                                    <div class="spot-desc p-2 text-center">
                                        <p class="mt-2 mb-1 font-size20">{{$position->name}}</p>
                                        <div class="mt-3">
                                            {{$position->type->name}} <br>
                                            (Width:{{$position->type->width}}px, Height:{{$position->type->height}}px)
                                        </div>
                                        <br><br>
                                        @if($position->standardPrice->slashed_price)
                                            <span class="slashed_price_text">${{$position->standardPrice->slashed_price}}</span>
                                        @endif
                                        <span>${{$position->standardPrice->price}} / {{$position->standardPrice->getUnit()}}</span>
                                        <br>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
