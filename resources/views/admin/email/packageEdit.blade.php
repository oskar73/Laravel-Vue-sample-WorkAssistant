@extends('layouts.master')

@section('title', 'Email Campaign Package')
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
                    <span class="m-nav__link-text">Email Campaign</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Package</span>
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
        <a href="{{route('admin.email.package.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
    </div>
@endsection

@section('content')
    <div class="tabs-wrapper">
        <ul class="tab-nav">
            <li class="tab-item"><a class="tab-link tab-active" data-area="#all" href="#/all">Package Detail</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#price" href="#/price">Set Price</a></li>
            <li class="tab-item"><a class="tab-link" data-area="#meeting" href="#/meeting">Meeting and Attach Form</a></li>
        </ul>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50" id="all_area">
        <div class="m-portlet__body">
            <form action="{{route('admin.email.package.update', $item->id)}}" id="submit_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-control-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$item->name}}">
                            <div class="form-control-feedback error-name"></div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-control-label">Description:</label>
                            <textarea class="form-control m-input--square minh-100 white-space-pre-line" name="description" id="description">{{$item->description}}</textarea>
                            <div class="form-control-feedback error-description"></div>
                        </div>
                        <div class="row" x-data="{unlimit:'{{$item->campaign_number==-1?1:0}}'}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="campaign_number" class="form-control-label">Campaign Listing limit number:</label>
                                    <input type="number" class="form-control" name="campaign_number" id="campaign_number" x-bind:disabled="unlimit==1" value="{{$item->campaign_number!=-1?$item->campaign_number:''}}">
                                    <div class="form-control-feedback error-campaign_number"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <label class="m-checkbox m-checkbox--state-success">
                                    <input type="checkbox" name="unlimit" x-on:click="unlimit=unlimit==1?0:1" x-bind:checked="unlimit==1"> Set Unlimit
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="image_area">
                                @foreach($item->getMedia('image') as $key=>$image)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                        </td>
                                        <td><img class='width-150' src="{{$image->getUrl()}}"/></td>
                                        <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addImage">+ Add Image</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="link_area">
                                @foreach($item->getLinks() as $key1=>$link)
                                    <tr>
                                        <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}"></td>
                                        <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addLink">+ Add External Video Link</a>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table table-bordered table-item-center">
                                <tbody id="video_area">
                                @foreach($item->getMedia('video') as $key2=>$video)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control m-input--square" value="{{$video->getUrl()}}" readonly>
                                            <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                                        </td>
                                        <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1" id="addVideo">+ Upload Video</a>
                            </table>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="example_input_full_name">Choose Gallery Order:</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="1" @if($item->order==1) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                    Image Gallery

                                                    <hr/>

                                                    Video Gallery
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="m-option">
                                        <span class="m-option__control">
                                            <span class="m-radio m-radio--brand m-radio--check-bold">
                                                <input type="radio" name="order" value="0" @if($item->order==0) checked @endif>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="m-option__label">
                                            <span class="m-option__head">
                                                <span class="m-option__title">
                                                     Video Gallery

                                                    <hr/>

                                                     Image Gallery

                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="thumbnail" class="form-control-label">Thumbnail</label>
                            <input type="file" accept="image/*" class="form-control m-input--square" id="thumbnail" >
                            <div class="form-control-feedback error-thumbnail"></div>
                            <img id="thumbnail_image" class="maxw-100" src="{{$item->getFirstMediaUrl("thumbnail")}}"/>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="featured" class="form-control-label">Featured?</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="featured" id="featured" @if($item->featured) checked @endif>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="new" class="form-control-label">New?</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="new" id="new" @if($item->new) checked @endif>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="status" class="form-control-label">Approve?</label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="status" id="status" @if($item->status)checked @endif>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="meeting_area">
        <div class="m-portlet__body" x-data="{meeting:{{$item->meeting}},form:{{$item->form}}}">
            <form action="{{route('admin.email.package.updateMeeting', $item->id)}}" id="meeting_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <label for="meeting" class="form-control-label">Allow Meeting?</label>
                            <div>
                                <span class="m-switch m-switch--icon m-switch--info">
                                    <label>
                                        <input type="checkbox" name="meeting" id="meeting" @if($item->meeting) checked @endif x-on:click="meeting=meeting===1?0:1">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div x-show="meeting===1">
                                Meeting Area
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="form" class="form-control-label">Attach Purchase Follow-up Form?</label>
                            <div>
                                <span class="m-switch m-switch--icon m-switch--info">
                                    <label>
                                        <input type="checkbox" name="form" id="form" @if($item->form) checked @endif  x-on:click="form=form===1?0:1">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div  x-show="form===1">
                                <div class="form-group">
                                    <label for="followupEmail" class="form-control-label">Choose Email:</label>
                                    <select name="followupEmail" id="followupEmail" class="followupEmail selectpicker" data-live-search="true" data-width="100%">
                                        <option value="" disabled selected>Choose Email</option>
                                        @foreach($followEmails as $email)
                                            <option value="{{$email->id}}" @if($email->id===$item->email_id) selected @endif>{{$email->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-followupEmail"></div>
                                </div>
                                <div class="form-group">
                                    <label for="followupForm" class="form-control-label">Choose Form:</label>
                                    <select name="followupForm" id="followupForm" class="followupForm selectpicker" data-live-search="true" data-width="100%">
                                        <option value="" disabled selected>Choose Form</option>
                                        @foreach($followForms as $form)
                                            <option value="{{$form->id}}" @if($form->id===$item->form_id) selected @endif>{{$form->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-followupForm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile tab_area md-pt-50" id="price_area">
        <div class="m-portlet__body">
            <div class="container">
                <div class="text-right">
                    <a href="javascript:void(0);" class="btn m-btn--square m-btn btn-outline-success btn-sm addPriceBtn">+ Add Price Plan</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered ajaxTable datatable">
                        <thead>
                        <tr>
                            <th>Price</th>
                            <th>Slashed Price</th>
                            <th>Payment</th>
                            <th>Standard</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="price_area">
                        <tr><td colspan="6"><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Price</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="create_modal_form" action="{{route('admin.email.package.createPrice', $item->id)}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="edit_price" name="edit_price">
                        <div class="row" x-data="{recurrent:true}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_type" class="form-control-label">Payment Type:</label>
                                    <select name="payment_type" id="payment_type" class="payment_type selectpicker disable_item" data-width="100%" x-on:change="recurrent=!recurrent">
                                        <option value="1" @if($item->recurrent===1) selected @endif>Recurrent</option>
                                        <option value="0" @if($item->recurrent===0) selected @endif>Onetime</option>
                                    </select>
                                    <div class="form-control-feedback error-payment_type"></div>
                                </div>
                            </div>
                            <div class="col-md-6" x-show="recurrent">
                                <label for="period" class="form-control-label">Recurring Billing Cycle:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-right m-input--square disable_item" value="1" name="period" id="period">
                                    <div class="input-group-append" style="width:150px;">
                                        <select class="form-control m-bootstrap-select selectpicker disable_item" name="period_unit" id="period_unit">
                                            <option value="day">Day</option>
                                            <option value="week">Week</option>
                                            <option value="month" selected>Month</option>
                                            <option value="year">Year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-control-feedback error-period"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Price:</label>
                                    <input type="text" class="form-control price disable_item" name="price" id="price">
                                    <div class="form-control-feedback error-price"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slashed_price" class="form-control-label">Slashed Price: <i class="fa fa-info-circle tooltip_1" title="Slashed Price: Nullable"></i></label>
                                    <input type="text" class="form-control price" name="slashed_price" id="slashed_price">
                                    <div class="form-control-feedback error-slashed_price"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="standard" class="form-control-label">Set As Standard? <i class="fa fa-info-circle tooltip_1" title="Set as standard price"></i></label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="standard" id="price_standard">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-control-label">Status <i class="fa fa-info-circle tooltip_1" title="Set as standard price"></i></label>
                                <div>
                                    <span class="m-switch m-switch--icon m-switch--info">
                                        <label>
                                            <input type="checkbox" name="status" checked id="price_status">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script> var item_id = "{{$item->id}}"; </script>
    <script src="{{asset('assets/js/admin/email/packageEdit.js')}}"></script>
@endsection
