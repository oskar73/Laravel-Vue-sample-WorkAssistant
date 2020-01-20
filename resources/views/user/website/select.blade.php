@extends('layouts.master')

@section('title', 'Websites')
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
                <a href="{{ route('user.website.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Websites</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Select Package</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.website.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Back</a>
        <a href="{{route('package.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2">Purchase package</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 text-center mb-3"><h3>Choose the available package</h3></div>
        @forelse($packages as $package)
        <div class="col-lg-4  mb-3">
            <div class="package_item bg-white p-4 pl-3 box-shadow h-100">
                <div class="d-flex justify-content-between">
                    <h4>{{$package->getName()}}</h4>
                    <div>
                        <span class="c-badge c-badge-success">{{ucfirst($package->status)}}</span>
                    </div>
                </div>
                <hr>
                <p class="fs-16"><b>Websites </b>: {{$package->website==-1? 'Unlimited': $package->website}}/{{$package->websites->count()}}</p>
                <p class="fs-16"><b>Website Pages</b>:{{$package->page==-1? 'Unlimited': $package->page}}</p>
                <p class="fs-16"><b>Website Storage</b>:{{$package->storage==-1? 'Unlimited': $package->storage}} GB</p>
                <p class="fs-16"><b>Website Modules</b>:{{$package->module==-1? 'Unlimited': $package->module}}</p>
                <hr>
                <div class="text-right">
                    @if(($package->status=='active')&&($package->website==-1||$package->website-$package->websites->count()>0))
                        <a href="{{route('user.website.select', $package->id)}}"
                           class="btn m-btn--square m-btn--sm btn-outline-success"
                        >Select</a>
                    @else
                        <button class="btn m-btn--square m-btn--sm btn-outline-danger hover-prevent" disabled="disabled">Select</button>
                    @endif
                </div>
            </div>
        </div>
        @empty

        @endforelse
    </div>
@endsection
@section('script')
@endsection
