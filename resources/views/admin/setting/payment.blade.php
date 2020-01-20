@extends('layouts.master')

@section('title', 'Setting - Payment')
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
                    <span class="m-nav__link-text">Setting</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Payment</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Payment Setting
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <form action="{{route('admin.setting.payment.store')}}" id="submit_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label class="m-checkbox">
                                    <input type="checkbox" name="paypal" class="checkbox_item" data-area=".paypal_area" @if(in_array("paypal", $gateway)) checked @endif>
                                    Paypal Payment Gateway
                                    <span></span>
                                </label>
                                <i class="fa fa-info-circle tooltip_3"
                                   title="You can configure paypal payment gateway. If you uncheck, then customers won't pay via paypal.">
                                </i>
                            </div>
                            <div class="paypal_area @if(!in_array("paypal", $gateway)) d-none @endif">
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_api_username">
                                        Paypal API Username
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_api_username"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_api_username"
                                               id="paypal_api_username"
                                               value="{{optional($paypal)['username']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_api_username"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_api_password">
                                        Paypal API Password
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_api_password"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_api_password"
                                               id="paypal_api_password"
                                               value="{{optional($paypal)['password']}}">
                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_api_password"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_api_secret">
                                        Paypal API Secret
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_api_secret"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_api_secret"
                                               id="paypal_api_secret"
                                               value="{{optional($paypal)['secret']}}">
                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_api_secret"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_client_id">
                                        Paypal Client ID
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_client_id"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_client_id"
                                               id="paypal_client_id"
                                               value="{{optional($paypal)['client_id']}}">
                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_client_id"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_client_secret">
                                        Paypal Client Secret
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_client_secret"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_client_secret"
                                               id="paypal_client_secret"
                                               value="{{optional($paypal)['client_secret']}}">
                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_client_secret"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_partner_id">
                                        Paypal Partner ID
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_partner_id"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_partner_id"
                                               id="paypal_partner_id"
                                               value="{{optional($paypal)['partner_id']}}">
                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_partner_id"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="paypal_webhook_id">
                                        Paypal Webhook ID
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_webhook_id"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="paypal_webhook_id"
                                               id="paypal_webhook_id"
                                               value="{{optional($paypal)['webhook_id']}}">
                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-paypal_webhook_id"></div>
                                </div>
                                <div class="form-group">
                                    <label for="paypal_api_sandbox">
                                        Sandbox Mode?
                                        <i class="fa fa-info-circle tooltip_3" title="paypal_api_sandbox"></i>
                                    </label>
                                    <select name="paypal_api_sandbox" id="paypal_api_sandbox" class="form-control selectpicker">
                                        <option value="sandbox" {{optional($paypal)['sandbox']=='sandbox'? 'selected':''}}>Sandbox Mode</option>
                                        <option value="live" {{optional($paypal)['sandbox']=='live'? 'selected':''}}>Live Mode</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group m-form__group">
                                <label class="m-checkbox">
                                    <input type="checkbox" name="stripe" class="checkbox_item" data-area=".stripe_area" @if(in_array("stripe", $gateway)) checked @endif>
                                    Stripe Payment Gateway
                                    <span></span>
                                </label>
                                <i class="fa fa-info-circle tooltip_3"
                                   title="You can configure stripe payment gateway. If you uncheck, then customers won't pay via paypal.">
                                </i>
                            </div>
                            <div class="stripe_area @if(!in_array("stripe", $gateway)) d-none @endif">

                                <div class="form-group" x-data={show:false}>
                                    <label for="stripe_public_key">
                                        Stripe Public Key
                                        <i class="fa fa-info-circle tooltip_3" title="stripe_public_key"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="stripe_public_key"
                                               id="stripe_public_key"
                                               value="{{optional($stripe)['public']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-stripe_public_key"></div>
                                </div>

                                <div class="form-group" x-data={show:false}>
                                    <label for="stripe_secret_key">
                                        Stripe Secret Key
                                        <i class="fa fa-info-circle tooltip_3" title="stripe_secret_key"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="stripe_secret_key"
                                               id="stripe_secret_key"
                                               value="{{optional($stripe)['secret']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-stripe_secret_key"></div>
                                </div>

                                <div class="form-group" x-data={show:false}>
                                    <label for="stripe_webhook_secret">
                                        Stripe Account Webhook Secret
                                        <i class="fa fa-info-circle tooltip_3" title="stripe_webhook_secret"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="stripe_webhook_secret"
                                               id="stripe_webhook_secret"
                                               value="{{optional($stripe)['webhook']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-stripe_webhook_secret"></div>
                                </div>

                                <div class="form-group" x-data={show:false}>
                                    <label for="stripe_webhook_secret_connect">
                                        Stripe Connect Webhook Secret
                                        <i class="fa fa-info-circle tooltip_3" title="stripe_webhook_secret_connect"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="stripe_webhook_secret_connect"
                                               id="stripe_webhook_secret_connect"
                                               value="{{optional($stripe)['webhook_connect']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-stripe_webhook_secret_connect"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2 smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/setting/payment.js')}}"></script>
@endsection
