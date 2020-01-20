@extends('layouts.master')

@section('title', 'Setting - Third Party')
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
                    <span class="m-nav__link-text">Third Party</span>
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
                        Third Party Setting
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <form action="{{route('admin.setting.third_party.store')}}" id="submit_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">Tom Website Server</p>
                                <div class="form-group">
                                    <label for="ssh_ip">
                                        IP Address (Load Balancer IP for A record point)
                                        <i class="fa fa-info-circle tooltip_3" title="ip address of load balance server and ip which custom domains' A record should be point to."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input type="text"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="ssh_ip"
                                               id="ssh_ip"
                                               value="{{$ssh['ip']}}">

                                    </div>
                                    <div class="form-control-feedback error-ssh_ip"></div>
                                </div>
                                <div class="form-group">
                                    <label for="ssh_root_domain">
                                        Root Domain
                                        <i class="fa fa-info-circle tooltip_3" title="root domain of tom websites."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input type="text"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="ssh_root_domain"
                                               id="ssh_root_domain"
                                               value="{{$ssh['root_domain']}}">
                                    </div>
                                    <div class="form-control-feedback error-ssh_root_domain"></div>
                                </div>
                                <div class="form-group">
                                    <label for="ssh_domain">
                                        Sub Domain for CNAME point
                                        <i class="fa fa-info-circle tooltip_3" title="domain for cname point"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input type="text"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="ssh_domain"
                                               id="ssh_domain"
                                               value="{{$ssh['domain']}}">

                                    </div>
                                    <div class="form-control-feedback error-ssh_domain"></div>
                                </div>
                            </div>

                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">Socket Server</p>

                                <div class="form-group" x-data={show:false}>
                                    <label for="socket_server">
                                        Server Endpoint
                                        <i class="fa fa-info-circle tooltip_3" title="This is socket server endpoint"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="socket_server"
                                               id="socket_server"
                                               value="{{option("socket_server", "")}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-socket_server"></div>
                                </div>
                            </div>
                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">MailCow Server</p>

                                <div class="form-group" x-data={show:false}>
                                    <label for="mailcow_server">
                                        Server Domain
                                        <i class="fa fa-info-circle tooltip_3" title="This is api key of mailcow server."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="mailcow_server"
                                               id="mailcow_server"
                                               value="{{option("mailcow_server", "")}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-mailcow_server"></div>
                                </div>

                                <div class="form-group" x-data={show:false}>
                                    <label for="mailcow_apikey">
                                        API Key
                                        <i class="fa fa-info-circle tooltip_3" title="This is api key of mailcow server."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="mailcow_apikey"
                                               id="mailcow_apikey"
                                               value="{{option("mailcow_apikey", "")}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-mailcow_apikey"></div>
                                </div>
                            </div>
                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">Unsplash</p>

                                <div class="form-group" x-data={show:false}>
                                    <label for="unsplash_api_key">
                                        API Key
                                        <i class="fa fa-info-circle tooltip_3" title="This is api key of mailcow server."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                                class="form-control m-input m-input--square handleToggle"
                                                name="unsplash_api_key"
                                                id="unsplash_api_key"
                                                value="{{option("unsplash_api_key", "")}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-unsplash_api_key"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">Namecheap API</p>

                                <div class="form-group">
                                    <label for="namecheap_sandbox">
                                        Sandbox Mode?
                                        <i class="fa fa-info-circle tooltip_3" title="Sandbox mode?"></i>
                                    </label>
                                    <select name="namecheap_sandbox" id="namecheap_sandbox" class="form-control selectpicker">
                                        <option value="1" {{$namecheap['sandbox']==1? 'selected':''}}>Sandbox Mode</option>
                                        <option value="0" {{$namecheap['sandbox']==0? 'selected':''}}>Live Mode</option>
                                    </select>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="namecheap_user">
                                        API User
                                        <i class="fa fa-info-circle tooltip_3" title="API User"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="namecheap_user"
                                               id="namecheap_user"
                                               value="{{$namecheap['user']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-namecheap_user"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="namecheap_key">
                                        API Key
                                        <i class="fa fa-info-circle tooltip_3" title="API Key"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="namecheap_key"
                                               id="namecheap_key"
                                               value="{{$namecheap['key']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-namecheap_key"></div>
                                </div>
                                <div class="form-group" x-data={show:false}>
                                    <label for="namecheap_ip">
                                        Client IP Address
                                        <i class="fa fa-info-circle tooltip_3" title="This is your server IP address"></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="namecheap_ip"
                                               id="namecheap_ip"
                                               value="{{$namecheap['ip']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-namecheap_ip"></div>
                                </div>
                            </div>

                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">Google NoCaptCha Keys</p>

                                <div class="form-group" x-data={show:false}>
                                    <label for="nocaptcha_secret">
                                        Secret
                                        <i class="fa fa-info-circle tooltip_3" title="This is api key of mailcow server."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="nocaptcha_secret"
                                               id="nocaptcha_secret"
                                               value="{{$nocaptcha['secret']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-nocaptcha_secret"></div>
                                </div>

                                <div class="form-group" x-data={show:false}>
                                    <label for="nocaptcha_site_key">
                                        Site Key
                                        <i class="fa fa-info-circle tooltip_3" title="This is api key of mailcow server."></i>
                                    </label>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="nocaptcha_site_key"
                                               id="nocaptcha_site_key"
                                               value="{{$nocaptcha['sitekey']}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-nocaptcha_site_key"></div>
                                </div>
                            </div>

                            <div class="input_group_bp">
                                <p class="input_group_bp_heading">Google Map Key</p>

                                <div class="form-group" x-data={show:false}>
                                    <div class="position-relative">
                                        <input x-bind:type="show===true?'text':'password'"
                                               class="form-control m-input m-input--square handleToggle"
                                               name="google_map_key"
                                               id="google_map_key"
                                               value="{{option('google_map_key', '')}}">

                                        <i class="fa fa-eye psw_eye" x-show="show===true" x-on:click="show=!show"></i>
                                        <i class="fa fa-eye-slash psw_eye" x-show="show===false" x-on:click="show=!show"></i>
                                    </div>
                                    <div class="form-control-feedback error-google_map_key"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-right mt-3">
                        <button class="ml-auto btn m-btn--square m-btn--sm btn-outline-success mb-2 smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/setting/third-party.js')}}"></script>
@endsection
