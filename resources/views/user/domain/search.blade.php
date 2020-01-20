@extends('layouts.master')

@section('title', 'Domain Search')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/checkout.css')}}">
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
                    <span class="m-nav__link-text">Domain</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Search</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="domain_search_area">
        <div class="m-portlet m-portlet--mobile tab_area area-active" id="search_area">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            New Domain
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">

                </div>
            </div>
            <div class="m-portlet__body">
                <form class="domain-search" id="search-form">
                    @csrf
                    <div class="container">
                        <div class="text-center mb-3">
                            <a href="javascript:void(0);" class="btn m-btn--square btn-info m-btn m-btn--custom">Register
                                Domain</a>
                            <a href="{{ route('user.domain.connect') }}"
                                class="btn m-btn--square  btn-outline-info m-btn m-btn--custom">Connect My Domain</a>
                            {{--                        <a href="{{route('user.domain.transfer')}}" class="btn m-btn--square  btn-outline-info m-btn m-btn--custom">Transfer</a> --}}
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="domain" id="domain_input" class="form-control domain_search_box"
                                autocomplete="off">
                            <button type="submit" class="btn btn-primary smtBtn"><i class="fa fa-search fa-2x"></i></button>
                        </div>
                        <div class="recom_domain_area text-center">
                            @foreach ($recommends as $tld)
                                <div class="tld_recom_div">.{{ $tld->Name }}
                                    <span>(${{ $tld->prices->where('status', 1)->first()->totalPrice ?? null }})</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="result_area">

                        </div>
                        <div class="text-center">
                            <a href="javascript:void(0);" class="btn btn-primary mt-2 hover-box loadMoreBtn d-none">Load
                                More...</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area" id="duration_area">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Domain Name: <b><span class="duration_domain">{{ Session::get('pickDomain') ?? '' }}</span></b>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="#/search" class="tab-link btn btn-info">Back</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="duration_result"></div>
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area" id="contacts_area">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Domain Name: <b><span
                                    class="duration_domain">{{ Session::get('pickDomain') ?? '' }}</span></b>&nbsp;&nbsp;
                            Duration: <b><span class="duration_num">{{ Session::get('duration') ?? '0' }} Years</span></b>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="#/duration" class="tab-link btn btn-info">Back</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="contacts_result">
                    <form action="{{ route('user.domain.setContact') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Set Domain Contact Detail</h3>
                                </div>
                                <div class="col-md-6">
                                    <select name="contact" id="contact" class="contact selectpicker" data-width="100%">
                                        <option selected>Choose from my saved addresses</option>
                                        @foreach (user()->domainContacts()->select('contactName', 'firstName', 'lastName', 'email', 'address1', 'address2', 'city', 'state', 'postalCode', 'country', 'phoneCode', 'phoneNumber')->get() as $contact)
                                            <option value="{{ $contact->id }}" data-contact="{{ $contact }}">
                                                {{ $contact->contactName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            @include('components.domain.contact')
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group m-form__group">
                                        <label class="m-checkbox m-checkbox--state-success mt-2">
                                            <input type="checkbox" name="saveThis" id="saveThis"> Remember this address for
                                            later.
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group m-form__group d-none contact_name_area">
                                        <input type="text" class="form-control m-input m-input--square"
                                            name="contactName" placeholder="Name">
                                        <div class="form-control-feedback error-contactName"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group m-form__group text-right">
                                        <button type="submit" class="btn btn-success tw-bg-green-600 formSmtBtn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile tab_area" id="confirm_area">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Confirm
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="#/contacts" class="tab-link btn btn-info">Back</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="confirm_result">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ s3_asset('vendors/typed/typed.js') }}"></script>
    <script src="{{ asset('assets/js/user/domain/search.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
@endsection
