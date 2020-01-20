@extends('layouts.master')

@section('title', 'Edit Team')
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
                    <span class="m-nav__link-text">Team Manage</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Edit Team</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn m-btn--square  btn-outline-info m-btn m-btn--custom" href="{{$slug==null? route('admin.teamManage.team.index') : route('admin.teamManage.team.subteam', $slug)}}">Back</a>
    </div>

@endsection
@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#detail" href="#/detail">Team Detail</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#permission" href="#/permission">Assign Permission</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="detail_area">
        <div class="m-portlet__body">
            <div class="container-fluid">
                <form action="{{route('admin.teamManage.team.update', $team->id)}}" id="submitForm">
                    @csrf
                    <input type="hidden" name="slug" value="{{$slug}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group position-relative text-center pt-5">
                                <label for="thumbnail_image" class="btn btn-outline-info m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air choose_btn_container">
                                    <i class="la la-edit"></i>
                                </label>
                                <input type="file" accept="image/*" class="form-control m-input--square d-none" id="thumbnail_image" >
                                <img id="thumbnail" class="image_upload_output w-300px" src="{{$team->getImage()}}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group pt-3 pb-3">
                                <label for="name">
                                    Name
                                    <i class="fa fa-info-circle tooltip_1" title="Name"></i>
                                </label>
                                <input type="text" class="form-control m-input m-input--square" name="name" id="name" value="{{$team->name}}">
                                <div class="form-control-feedback error-name"></div>
                            </div>
                            <div class="form-group pt-3 pb-3">
                                <label for="description">
                                    Description
                                    <i class="fa fa-info-circle tooltip_1" title="Description"></i>
                                </label>
                                <textarea class="form-control m-input m-input--square minh-200" name="description" id="description">{{$team->description}}</textarea>
                                <div class="form-control-feedback error-description"></div>
                            </div>
                            <div class="form-group pt-3 pb-3">
                                <label>
                                    Choose Properties
                                    <i class="fa fa-info-circle tooltip_1" title="Properties"></i>
                                </label>
                                @php
                                    $ids = $team->properties->pluck('id')->toArray();
                                    $teamusers = $team->users;
                                @endphp
                                <div class="m-checkbox-list mt-3">
                                    @foreach($properties as $property)
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="properties[]" value="{{$property->id}}" @if(in_array($property->id, $ids)) checked @endif>
                                            {{$property->name}} <i class="fa fa-info-circle tooltip_1" title="{{$property->description}}"></i>
                                            <span></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client" class="form-control-label">Choose Clients:</label> <br>
                                <select name="clients[]" id="client" class="select2 m-select2" multiple>
                                    @foreach($teamusers->where('pivot.role', 'client') as $client)
                                        <option value="{{$client->id}}" selected>{{$client->name}} ({{$client->email}})</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-clients"></div>
                            </div>
                            <div class="form-group">
                                <label for="employee" class="form-control-label">Choose Employees:</label> <br>
                                <select name="employees[]" id="employee" class="select2 m-select2" multiple>
                                    @foreach($teamusers->where('pivot.role', 'employee') as $employee)
                                        <option value="{{$employee->id}}" selected>{{$employee->name}} ({{$employee->email}})</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-employees"></div>
                            </div>
                            <div class="form-group">
                                <label for="user" class="form-control-label">Choose Users:</label> <br>
                                <select name="users[]" id="user" class="select2 m-select2" multiple>
                                    @foreach($teamusers->where('pivot.role', 'user') as $user)
                                        <option value="{{$user->id}}" selected>{{$user->name}} ({{$user->email}})</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-users"></div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-control-label">Active?</label>
                                <div>
                                <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                    <label>
                                        <input type="checkbox" @if($team->status) checked @endif id="status" name="status">
                                        <span></span>
                                    </label>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button class="btn m-btn--square  btn-outline-info m-btn m-btn--custom smtBtn" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="permission_area">
        <div class="m-portlet__body">
            Permission
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>var slug = "{{$slug}}"</script>
    <script src="{{asset('assets/js/admin/teamManage/editTeam.js')}}"></script>
@endsection
