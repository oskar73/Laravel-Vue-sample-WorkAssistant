@extends('layouts.master')

@section('title', 'Domain')
@section('style')
    <style>
        .changable_area {
            background-color:#d7f5ea;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
            <li class="m-nav__item m-nav__item--home">
                <a href="" class="m-nav__link m-nav__link--icon">
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
                <a href="{{route('admin.domainTld.index')}}" class="m-nav__link">
                    <span class="m-nav__link-text">TLDs</span>
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
    <div class="col-md-6 text-right">
        <a href="{{route('admin.domainTld.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger mb-2">Back</a>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        TLD: (.{{$domainTld->Name}})
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>NonRealTime?</th>
                            <th>MinRegisterYears</th>
                            <th>MaxRegisterYears</th>
                            <th>MinRenewYears</th>
                            <th>MaxRenewYears</th>
                            <th>RenewalMinDays</th>
                            <th>RenewalMaxDays</th>
                            <th>ReactivateMaxDays</th>
                            <th>MinTransferYears</th>
                            <th>MaxTransferYears</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="c-badge c-badge-success">.{{$domainTld->Name}}</span></td>
                            <td><span class="c-badge {{$domainTld->NonRealTime=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->NonRealTime}}</span></td>
                            <td>{{$domainTld->MinRegisterYears}}</td>
                            <td>{{$domainTld->MaxRegisterYears}}</td>
                            <td>{{$domainTld->MinRenewYears}}</td>
                            <td>{{$domainTld->MaxRenewYears}}</td>
                            <td>{{$domainTld->RenewalMinDays}}</td>
                            <td>{{$domainTld->RenewalMaxDays}}</td>
                            <td>{{$domainTld->ReactivateMaxDays}}</td>
                            <td>{{$domainTld->MinTransferYears}}</td>
                            <td>{{$domainTld->MaxTransferYears}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>IsApiRegisterable?</th>
                            <th>IsApiRenewable?</th>
                            <th>IsApiTransferable?</th>
                            <th>IsEppRequired?</th>
                            <th>IsDisableModContact?</th>
                            <th>IsIncludeInExtendedSearchOnly?</th>
                            <th>SequenceNumber</th>
                            <th>Type</th>
                            <th>SubType</th>
                            <th>IsSupportsIDN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="c-badge {{$domainTld->IsApiRegisterable=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->IsApiRegisterable}}</span></td>
                            <td><span class="c-badge {{$domainTld->IsApiRenewable=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->IsApiRenewable}}</span></td>
                            <td><span class="c-badge {{$domainTld->IsApiTransferable=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->IsApiTransferable}}</span></td>
                            <td><span class="c-badge {{$domainTld->IsEppRequired=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->IsEppRequired}}</span></td>
                            <td><span class="c-badge {{$domainTld->IsDisableModContact=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->IsDisableModContact}}</span></td>
                            <td><span class="c-badge {{$domainTld->IsIncludeInExtendedSearchOnly=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->IsIncludeInExtendedSearchOnly}}</span></td>
                            <td>{{$domainTld->SequenceNumber}}</td>
                            <td>{{$domainTld->Type}}</td>
                            <td>{{$domainTld->SubType}}</td>
                            <td>{{$domainTld->IsSupportsIDN}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>SupportsRegistrarLock</th>
                            <th>AddGracePeriodDays</th>
                            <th>WhoisVerification</th>
                            <th>ProviderApiDelete</th>
                            <th>TldState</th>
                            <th>SearchGroup</th>
                            <th>Registry</th>
                            <th>Status</th>
                            <th>Recommend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$domainTld->Category}}</td>
                            <td><span class="c-badge {{$domainTld->SupportsRegistrarLock=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->SupportsRegistrarLock}}</span></td>
                            <td>{{$domainTld->AddGracePeriodDays}}</td>
                            <td><span class="c-badge {{$domainTld->WhoisVerification=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->WhoisVerification}}</span></td>
                            <td><span class="c-badge {{$domainTld->ProviderApiDelete=='true'? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->ProviderApiDelete}}</span></td>
                            <td>{{$domainTld->TldState}}</td>
                            <td>{{$domainTld->SearchGroup}}</td>
                            <td>{{$domainTld->Registry}}</td>
                            <td class="changable_area">
                                <select class="form-control switchStatus" data-obj="tld" data-id="{{$domainTld->id}}" data-action="status">
                                    <option value="1" @if($domainTld->status==1) selected @endif>Active</option>
                                    <option value="0" @if($domainTld->status==0) selected @endif>Inactive</option>
                                </select>
                            </td>
                            <td class="changable_area">
                                <select class="form-control switchStatus" data-obj="tld" data-id="{{$domainTld->id}}" data-action="recommend">
                                    <option value="1" @if($domainTld->recommend==1) selected @endif>Recommended</option>
                                    <option value="0" @if($domainTld->recommend==0) selected @endif>Unrecommended</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <h4>Register:</h4>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Duration (Year)</th>
                            <th>Price</th>
                            <th>AdditionalCost</th>
                            <th>RegularPrice</th>
                            <th>RegularAdditionalCost</th>
                            <th>YourPrice</th>
                            <th>YourAdditionalCost</th>
                            <th>PromotionPrice</th>
                            <th>Currency</th>
                            <th>BizinaboxAddPrice</th>
                            <th>Status</th>
                            <th>TotalPrice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($domainTld->prices()->where('Action', 'register')->get() as $rPrice)
                            <tr data-id="{{$rPrice->id}}" data-action="register">
                                <td>{{$rPrice->Duration}}</td>
                                <td>{{$rPrice->Price}}</td>
                                <td>{{$rPrice->AdditionalCost}}</td>
                                <td>{{$rPrice->RegularPrice}}</td>
                                <td>{{$rPrice->RegularAdditionalCost}}</td>
                                <td class="yourPrice">{{$rPrice->YourPrice}}</td>
                                <td>{{$rPrice->YourAdditionalCost}}</td>
                                <td>{{$rPrice->PromotionPrice}}</td>
                                <td>{{$rPrice->Currency}}</td>
                                <td class="changable_area addPrice" contenteditable="true">{{formatNumber($rPrice->addPrice)}}</td>
                                <td class="changable_area">
                                    <select class="form-control switchStatus" data-obj="price" data-id="{{$rPrice->id}}" data-action="register">
                                        <option value="1" @if($rPrice->status==1) selected @endif>Active</option>
                                        <option value="0" @if($rPrice->status==0) selected @endif>Inactive</option>
                                    </select>
                                </td>
                                <td class="totalPrice">{{$rPrice->totalPrice}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h4>Renew:</h4>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Duration (Year)</th>
                            <th>Price</th>
                            <th>AdditionalCost</th>
                            <th>RegularPrice</th>
                            <th>RegularAdditionalCost</th>
                            <th>YourPrice</th>
                            <th>YourAdditionalCost</th>
                            <th>PromotionPrice</th>
                            <th>Currency</th>
                            <th>BizinaboxAddPrice</th>
                            <th>Status</th>
                            <th>TotalPrice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($domainTld->prices()->where('Action', 'renew')->get() as $nPrice)
                            <tr data-id="{{$nPrice->id}}" data-action="renew">
                                <td>{{$nPrice->Duration}}</td>
                                <td>{{$nPrice->Price}}</td>
                                <td>{{$nPrice->AdditionalCost}}</td>
                                <td>{{$nPrice->RegularPrice}}</td>
                                <td>{{$nPrice->RegularAdditionalCost}}</td>
                                <td class="yourPrice">{{$nPrice->YourPrice}}</td>
                                <td>{{$nPrice->YourAdditionalCost}}</td>
                                <td>{{$nPrice->PromotionPrice}}</td>
                                <td>{{$nPrice->Currency}}</td>
                                <td class="changable_area addPrice" contenteditable="true">{{formatNumber($nPrice->addPrice)}}</td>
                                <td class="changable_area">
                                    <select class="form-control switchStatus" data-obj="price" data-id="{{$nPrice->id}}" data-action="renew">
                                        <option value="1" @if($nPrice->status==1) selected @endif>Active</option>
                                        <option value="0" @if($nPrice->status==0) selected @endif>Inactive</option>
                                    </select>
                                </td>
                                <td class="totalPrice">{{$nPrice->totalPrice}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h4>Reactive:</h4>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Duration (Year)</th>
                            <th>Price</th>
                            <th>AdditionalCost</th>
                            <th>RegularPrice</th>
                            <th>RegularAdditionalCost</th>
                            <th>YourPrice</th>
                            <th>YourAdditionalCost</th>
                            <th>PromotionPrice</th>
                            <th>Currency</th>
                            <th>BizinaboxAddPrice</th>
                            <th>Status</th>
                            <th>TotalPrice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($domainTld->prices()->where('Action', 'reactivate')->get() as $aPrice)
                            <tr data-id="{{$aPrice->id}}" data-action="reactivate">
                                <td>{{$aPrice->Duration}}</td>
                                <td>{{$aPrice->Price}}</td>
                                <td>{{$aPrice->AdditionalCost}}</td>
                                <td>{{$aPrice->RegularPrice}}</td>
                                <td>{{$aPrice->RegularAdditionalCost}}</td>
                                <td class="yourPrice">{{$aPrice->YourPrice}}</td>
                                <td>{{$aPrice->YourAdditionalCost}}</td>
                                <td>{{$aPrice->PromotionPrice}}</td>
                                <td>{{$aPrice->Currency}}</td>
                                <td class="changable_area addPrice" contenteditable="true">{{formatNumber($aPrice->addPrice)}}</td>
                                <td class="changable_area">
                                    <select class="form-control switchStatus" data-obj="price" data-id="{{$aPrice->id}}" data-action="reactivate">
                                        <option value="1" @if($aPrice->status==1) selected @endif>Active</option>
                                        <option value="0" @if($aPrice->status==0) selected @endif>Inactive</option>
                                    </select>
                                </td>
                                <td class="totalPrice">{{$aPrice->totalPrice}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h4>Transfer:</h4>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                        <tr>
                            <th>Duration (Year)</th>
                            <th>Price</th>
                            <th>AdditionalCost</th>
                            <th>RegularPrice</th>
                            <th>RegularAdditionalCost</th>
                            <th>YourPrice</th>
                            <th>YourAdditionalCost</th>
                            <th>PromotionPrice</th>
                            <th>Currency</th>
                            <th>BizinaboxAddPrice</th>
                            <th>Status</th>
                            <th>TotalPrice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($domainTld->prices()->where('Action', 'transfer')->get() as $tPrice)
                            <tr data-id="{{$tPrice->id}}" data-action="transfer">
                                <td>{{$tPrice->Duration}}</td>
                                <td>{{$tPrice->Price}}</td>
                                <td>{{$tPrice->AdditionalCost}}</td>
                                <td>{{$tPrice->RegularPrice}}</td>
                                <td>{{$tPrice->RegularAdditionalCost}}</td>
                                <td class="yourPrice">{{$tPrice->YourPrice}}</td>
                                <td>{{$tPrice->YourAdditionalCost}}</td>
                                <td>{{$tPrice->PromotionPrice}}</td>
                                <td>{{$tPrice->Currency}}</td>
                                <td class="changable_area addPrice" contenteditable="true">{{formatNumber($tPrice->addPrice)}}</td>
                                <td class="changable_area">
                                    <select class="form-control switchStatus" data-obj="price" data-id="{{$tPrice->id}}" data-action="transfer">
                                        <option value="1" @if($tPrice->status==1) selected @endif>Active</option>
                                        <option value="0" @if($tPrice->status==0) selected @endif>Inactive</option>
                                    </select>
                                </td>
                                <td class="totalPrice">{{$tPrice->totalPrice}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/js/admin/domainTlds/edit.js')}}"></script>
@endsection
