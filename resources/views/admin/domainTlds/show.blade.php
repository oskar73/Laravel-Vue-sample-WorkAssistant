@extends('layouts.master')

@section('title', 'Domain')
@section('style')
    <style>
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
                    <span class="m-nav__link-text">Detail</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.domainTld.index')}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-danger mb-2">Back</a>
        <a href="{{route('admin.domainTld.edit', $domainTld->id)}}" class="ml-auto btn m-btn--square m-btn--sm btn-outline-info mb-2">Edit</a>
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
                            <td>
{{--                                <span class="c-badge {{$domainTld->status==1? 'c-badge-success': 'c-badge-danger'}}">{{$domainTld->status? 'Active': 'Inactive'}}</span>--}}
                                @if($domainTld->status==1)
                                    <span class="c-badge c-badge-success">Active</span>
                                @else
                                    <span class="c-badge c-badge-danger" >InActive</span>
                                @endif
                            </td>
                            <td>
                                @if($domainTld->recommend==1)
                                    <span class="c-badge c-badge-success">Recommended</span>
                                @else
                                    <span class="c-badge c-badge-danger">Unrecommended</span>
                                @endif
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
                            <tr>
                                <td>{{$rPrice->Duration}}</td>
                                <td>{{$rPrice->Price}}</td>
                                <td>{{$rPrice->AdditionalCost}}</td>
                                <td>{{$rPrice->RegularPrice}}</td>
                                <td>{{$rPrice->RegularAdditionalCost}}</td>
                                <td>{{$rPrice->YourPrice}}</td>
                                <td>{{$rPrice->YourAdditionalCost}}</td>
                                <td>{{$rPrice->PromotionPrice}}</td>
                                <td>{{$rPrice->Currency}}</td>
                                <td>{{formatNumber($rPrice->addPrice)}}</td>
                                <td><span class="c-badge {{$rPrice->status==1? 'c-badge-success': 'c-badge-danger'}}">{{$rPrice->status? 'Active': 'Inactive'}}</span></td>
                                <td>{{$rPrice->totalPrice}}</td>
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
                            <tr>
                                <td>{{$nPrice->Duration}}</td>
                                <td>{{$nPrice->Price}}</td>
                                <td>{{$nPrice->AdditionalCost}}</td>
                                <td>{{$nPrice->RegularPrice}}</td>
                                <td>{{$nPrice->RegularAdditionalCost}}</td>
                                <td>{{$nPrice->YourPrice}}</td>
                                <td>{{$nPrice->YourAdditionalCost}}</td>
                                <td>{{$nPrice->PromotionPrice}}</td>
                                <td>{{$nPrice->Currency}}</td>
                                <td>{{formatNumber($nPrice->addPrice)}}</td>
                                <td><span class="c-badge {{$nPrice->status==1? 'c-badge-success': 'c-badge-danger'}}">{{$nPrice->status? 'Active': 'Inactive'}}</span></td>
                                <td>{{$nPrice->totalPrice}}</td>
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
                            <tr>
                                <td>{{$aPrice->Duration}}</td>
                                <td>{{$aPrice->Price}}</td>
                                <td>{{$aPrice->AdditionalCost}}</td>
                                <td>{{$aPrice->RegularPrice}}</td>
                                <td>{{$aPrice->RegularAdditionalCost}}</td>
                                <td>{{$aPrice->YourPrice}}</td>
                                <td>{{$aPrice->YourAdditionalCost}}</td>
                                <td>{{$aPrice->PromotionPrice}}</td>
                                <td>{{$aPrice->Currency}}</td>
                                <td>{{formatNumber($aPrice->addPrice)}}</td>
                                <td><span class="c-badge {{$aPrice->status==1? 'c-badge-success': 'c-badge-danger'}}">{{$aPrice->status? 'Active': 'Inactive'}}</span></td>
                                <td>{{$aPrice->totalPrice}}</td>
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
                            <tr>
                                <td>{{$tPrice->Duration}}</td>
                                <td>{{$tPrice->Price}}</td>
                                <td>{{$tPrice->AdditionalCost}}</td>
                                <td>{{$tPrice->RegularPrice}}</td>
                                <td>{{$tPrice->RegularAdditionalCost}}</td>
                                <td>{{$tPrice->YourPrice}}</td>
                                <td>{{$tPrice->YourAdditionalCost}}</td>
                                <td>{{$tPrice->PromotionPrice}}</td>
                                <td>{{$tPrice->Currency}}</td>
                                <td>{{formatNumber($tPrice->addPrice)}}</td>
                                <td><span class="c-badge {{$tPrice->status==1? 'c-badge-success': 'c-badge-danger'}}">{{$tPrice->status? 'Active': 'Inactive'}}</span></td>
                                <td>{{$tPrice->totalPrice}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
