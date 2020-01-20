@extends('layouts.master')

@section('title', 'Purchase Management - Products')
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
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Purchase Management</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Products</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.purchase.'.$type.'.index')}}" class="btn m-btn--square  btn-outline-info m-btn m-btn--custom">
            Back
        </a>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile md-pt-50">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{$detail->name}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h3> {{$detail->name}}</h3>
                    </div>
                    <div class="col-md-4">
                        <p class="fs-14 mb-0"><b>Payment</b>: {{$item->orderItem->recurrent==1?'Recurrent':'Onetime'}}</p>
                        <p class="fs-14 mb-0">
                           <b>Price</b>:
                           @if($item->orderItem->recurrent==1)
                               ${{$item->orderItem->getRecurrentPrice()}}
                           @else
                            ${{formatNumber($item->orderItem->price)}}
                           @endif
                        </p>
                        @if($item->orderItem->recurrent==1)
                            <br>
                            <b>Subscription</b> <a href="{{route('user.purchase.subscription.detail', $item->orderItem->id)}}" class="underline">View Detail</a>
                            <p class="fs-14 mb-0">
                                <b>Start Date</b>: {{$item->created_at}}
                            </p>
                            <p class="fs-14 mb-0">
                                <b>Due Date</b>: {{$item->orderItem->due_date}}
                            </p>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="fs-14">
                    <b>Status</b>: <span class="c-badge {{$item->status=='active'?'c-badge-success':'c-badge-info'}}">{{ucfirst($item->status)}}</span> <br>
                    <b>Description</b>: {{$detail->description}} <br>
                    <b>Details</b>: <br>
                    <div class="pl-3">
                       @if($item->domain!=0) <b>Free Domain Price</b>:${{formatNumber($item->domain)}} <br> @endif
                        <b>Websites</b>:{{$item->website}} <br>
                        <b>Pages per website</b>:{{$item->page}} <br>
                        <b>Modules per website</b>:{{$item->module}} <br>
                        <b>Featured Modules per website</b>:{{$item->featured_module}} <br>
                    </div>
                </div>
                <hr>
                <div class="fs-14">
                    Website:
                    <table class="table table-bordered table-item-center datatable">
                        <thead>
                            <tr>
                                <th class="text-center">Website Name</th>
                                <th class="text-center">Website Domain</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->websites as $website)
                                <tr>
                                    <td><a href="{{route('user.website.edit', $website->id)}}">{{$website->name}} <i class="fa fa-eye"></i></a></td>
                                    <td><a href="//{{$website->domain}}" target="_blank">{{$website->domain}} <i class="fa fa-external-link-alt"></i></a></td>
                                    <td>
                                        <span class="c-badge {{$website->status=='active'?'c-badge-success':'c-badge-info'}}">{{ucfirst($website->status)}}</span>
                                    </td>
                                    <td>
                                        <a href="{{route('user.website.edit', $website->id)}}" class="btn m-btn--square btn-outline-info m-btn m-btn--custom p-2">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    Domain:
                    <table class="table table-bordered table-item-center datatable">
                        <thead>
                            <tr>
                                <th class="text-center">Domain Name</th>
                                <th class="text-center">Expire Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($item->domains as $domain)
                            <tr>
                                <td><a href="//{{$domain->name}}" target="_blank">{{$domain->name}} <i class="fa fa-external-link-alt"></i></a></td>
                                <td>{{$domain->expired_at}}</td>
                                <td>
                                    <span class="c-badge {{$domain->status=='active'?'c-badge-success':'c-badge-info'}}">{{ucfirst($domain->status)}}</span>
                                </td>
                                <td>
                                    <a href="{{route('user.domainList.show', $domain->id)}}" class="btn m-btn--square btn-outline-info m-btn m-btn--custom p-2">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    Items:
                    <table class="table table-bordered table-item-center datatable">
                        <thead>
                        <tr>
                            <th class="text-center">Type</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item->plugins as $plugin)
                            <tr>
                                <td>Plugin</td>
                                <td>{{$plugin->getName()}}</td>
                                <td>
                                    <span class="c-badge {{$plugin->status=='active'?'c-badge-success':'c-badge-info'}}">{{ucfirst($plugin->status)}}</span>
                                </td>
                                <td>
                                    <a href="{{route('user.purchase.plugin.detail', $plugin->id)}}" class="btn m-btn--square btn-outline-info m-btn m-btn--custom p-2">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($item->services as $service)
                            <tr>
                                <td>Service</td>
                                <td>{{$service->getName()}}</td>
                                <td>
                                    <span class="c-badge {{$service->status=='active'?'c-badge-success':'c-badge-info'}}">{{ucfirst($service->status)}}</span>
                                </td>
                                <td>
                                    <a href="{{route('user.purchase.service.detail', $service->id)}}" class="btn m-btn--square btn-outline-info m-btn m-btn--custom p-2">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($item->lacartes as $lacarte)
                            <tr>
                                <td>A La Carte</td>
                                <td>{{$lacarte->getName()}}</td>
                                <td>
                                    <span class="c-badge {{$lacarte->status=='active'?'c-badge-success':'c-badge-info'}}">{{ucfirst($lacarte->status)}}</span>
                                </td>
                                <td>
                                    <a href="{{route('user.purchase.lacarte.detail', $lacarte->id)}}" class="btn m-btn--square btn-outline-info m-btn m-btn--custom p-2">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    Purchase Followup Form:
                    <table class="table table-bordered table-item-center datatable">
                        <thead>
                        <tr>
                            <th class="text-center">Title</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item->forms as $form)
                            <tr>
                                <td>{{$form->title}}</td>
                                <td>
                                    <span class="c-badge {{$form->status=='filled'?'c-badge-success':'c-badge-info'}}">{{ucfirst($form->status)}}</span>
                                </td>
                                <td>
                                    <a href="{{route('user.purchase.form.detail', $form->id)}}" class="btn m-btn--square btn-outline-info m-btn m-btn--custom p-2">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    Meeting Permission
                    <table class="table table-bordered table-item-center datatable">
                        <thead>
                        <tr>
                            <th class="text-center">Total Meeting Number</th>
                            <th class="text-center">Current Number</th>
                            <th class="text-center">Meeting Period</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item->meetings as $meeting)
                            <tr>
                                <td>{{$meeting->meeting_number}}</td>
                                <td>{{$meeting->current_number}}</td>
                                <td>{{$meeting->meeting_period}} minutes</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
