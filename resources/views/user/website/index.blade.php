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
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Websites</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('user.website.getting.started')}}" class="ml-auto btn m-btn--square m-btn--sm m-btn--custom btn-outline-info mb-2">New Website</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all"> Running Websites (<span>{{$activeWebsites->count()}}</span>)</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#expired" href="#/expired"> Expired Websites (<span>{{$pendingWebsites->count()}}</span>)</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body tab_area area-active md-pt-50" id="all_area">
            <div class="table-responsive">
                <table class="table table-hover ajaxTable datatable datatable-all">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Package
                        </th>
                        <th>
                            Domain
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Storage
                        </th>
                        <th>
                            Created At
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($activeWebsites as $website)
                            <tr>
                                <td>{{$website->name}}</td>
                                <td>
                                    @if($website->package->getName()!=null)
                                        <a href="{{route('user.purchase.package.detail', $website->package->id)}}">{{$website->package->getName()}}</a>
                                    @endif
                                </td>
                                <td><a href='//{{$website->domain}}' target='_blank'>{{$website->domain}}<i class='la la-external-link'></i></a></td>
                                <td>
                                    <span class="c-badge {{$website->status_by_owner=='active'?'c-badge-success':'c-badge-info'}}">{{$website->status_by_owner}}</span>
                                </td>
                                <td>{!! $website->storageUsage() !!}</td>
                                <td>{{$website->created_at}}</td>
                                <td>
                                    <a href="{{route('user.website.editContent', $website->id)}}" target="_blank" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon">
                                        <span>
                                            <i class="la la-edit"></i>
                                            <span>Design</span>
                                        </span>
                                    </a>

                                    <a href="{{route('user.website.edit', $website->id)}}" class="btn btn-outline-info btn-sm m-1 p-2 m-btn m-btn--icon">
                                        <span>
                                            <i class="la la-cog"></i>
                                            <span>Setting</span>
                                        </span>
                                    </a>

                                    <button class="btn btn-outline-danger btn-sm m-1 p-2 m-btn m-btn--icon del-btn" data-id="{{$website->id}}" data-domain="{{$website->domain}}">
                                        <span>
                                            <i class="la la-trash"></i>
                                            <span>Delete</span>
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="m-portlet__body tab_area md-pt-50" id="expired_area">
            <div class="table-responsive">
                <table class="table table-hover ajaxTable datatable datatable-all">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Package
                        </th>
                        <th>
                            Domain
                        </th>
                        <th>
                            Storage
                        </th>
                        <th>
                            Created At
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pendingWebsites as $website)
                        <tr>
                            <td>{{$website->name}}</td>
                            <td>
                                @if($website->package->getName()!=null)
                                    <a href="{{route('user.purchase.package.detail', $website->package->id)}}">{{$website->package->getName()}}</a>
                                @endif
                            </td>
                            <td><a href='//{{$website->domain}}' target='_blank'>{{$website->domain}}<i class='la la-external-link'></i></a></td>
                            <td>{!! $website->storageUsage() !!}</td>
                            <td>{{$website->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{route('user.website.delete')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" />
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Are you absolutely sure?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>This action <strong>cannot</strong> be undone. This will permanently delete the <strong class="website_domain"></strong> site and its all relative fields.</p>
                            <p>Please type <strong class="website_domain"></strong> to confirm</p>
                            <input type="text" class="form-control m-input" id="domain_input" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button id="_confirm" type="submit" class="btn btn-danger" disabled>Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/user/website/index.js')}}"></script>
    <script>
        var domain = ''
        $(document).ready(function() {
            $(document).on('click', '.del-btn', function(e){
                var id = $(this).data('id')
                domain = $(this).data('domain')
                if (id && domain) {
                    $('#id').val(id)
                    $('.website_domain').html(domain)
                    $('#confirm-modal').modal('toggle');
                }
            })
            $(document).on('keyup', '#domain_input', function() {
                if ($('#domain_input').val() == domain) $('#_confirm').attr('disabled', false)
                else $('#_confirm').attr('disabled', true)
            })
        })
    </script>
@endsection
