@extends('layouts.master')

@section('title', 'Newsletter Advertisement Position Edit')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Newsletter Advertisement', 'Position', 'Edit']"
                             :menuLinks="['', route('admin.newsletterAds.position.index'), '']" />
    </div>

    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.newsletterAds.position.index')}}" type="info" label="Back" />
    </div>
@endsection

@section('content')

    <x-layout.tabs-wrapper>
        <x-layoutItems.normal-tab title="Position Detail" link="all" active="1" />
        <x-layoutItems.normal-tab title="Price" link="price" active="0" />
        <x-layoutItems.normal-tab title="Default Listing" link="default_listing" active="0" />
        <x-layoutItems.normal-tab active="0" link="status" title="Status" />
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        <x-form.form action="{{route('admin.newsletterAds.position.update', $position->id)}}">
            <input type="hidden" name="edit_id" value="{{$position->id}}">
            <div class="row">
                <div class="col-md-6">
                    <x-form.input name="name" value="{{$position->name}}" label="Name" />

                    <x-form.textarea label="Description" name="description">
                        {{$position->description}}
                    </x-form.textarea>

                    <x-form.select label="Ads Type" name="type" class="non_search_select2">
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.status label="Approve?" name="status" checked="{{$position->status? 'checked': ''}}" />
                </div>
                <div class="col-md-6">
                    <div class="form-group slimdiv">
                        <label for="thumbnail" class="form-control-label">Upload Screenshot</label>
                        <input type="file" name="image" id="thumbnail" />
                    </div>
                </div>
            </div>
            <div class="text-right mt-4">
                <x-form.smtBtn type="success" label="Next" />
            </div>

        </x-form.form>
    </x-layout.portletBody>

    <x-layout.portletBody id="price_area" active="0">
        <x-layout.container>
            <div class="text-right">
                <x-form.a label="+ Add Price Plan" class="addPriceBtn" />
            </div>
            <div class="table-responsive">
                <table class="table table-bordered ajaxTable datatable">
                    <thead>
                    <tr>
                        <th>Price</th>
                        <th>Slashed Price</th>
                        <th>Type</th>
                        <th>Period/Impression</th>
                        <th>Standard</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody class="price_area">
                    <tr>
                        <td colspan="7">
                            <x-layoutItems.loading />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-right mt-4">
                <a class="btn btn-outline-info m-btn m-btn--custom m-btn--square tab-link m-1" data-area="#all"
                   href="#/all">Previous</a>
                <a class="btn m-btn--square m-btn m-btn--custom btn-outline-success tab-link m-1"
                   data-area="#default_listing" href="#/default_listing">Next</a>
            </div>

        </x-layout.container>
    </x-layout.portletBody>

    <x-layout.portletBody id="default_listing_area" active="0">
        <x-layout.container>
            <x-form.form action="{{route('admin.newsletterAds.position.updateListing', $position->id)}}"
                         id="listing_form">
                <div class="default_listing_select">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-5">
                                <label for="ads_image">Ads Image ({{$position->type->width}}x{{$position->type->height}}
                                    px)</label>
                                <input type="file" name="ads_image" id="ads_image" />
                            </div>

                            <x-form.input name="ads_url" value="{{$position->default_url}}" label="Ads Url:" />

                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <a class="btn btn-outline-info m-btn m-btn--custom m-btn--square tab-link" data-area="#price"
                       href="#/price">Previous</a>
                    <button type="submit" class="btn m-btn--square m-btn--custom m-btn btn-outline-success smtBtn">
                        Next
                    </button>
                </div>
            </x-form.form>
        </x-layout.container>
    </x-layout.portletBody>

    @include("components.admin.common.statusArea", ['action'=>route('admin.newsletterAds.position.switch'), 'status'=>$position->status])

    <div class="modal fade" id="price_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Price</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="price_modal_form"
                      action="{{route('admin.newsletterAds.position.createPrice', $position->id)}}"
                      method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="edit_price" name="edit_price">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_type" class="form-control-label">Payment Type:</label>
                                    <select name="payment_type" id="payment_type" class="payment_type selectpicker"
                                            data-width="100%">
                                        <option value="period">Per Period</option>
                                    </select>
                                    <div class="form-control-feedback error-payment_type"></div>
                                </div>
                            </div>
                            <div class="col-md-6 period_select payment_type_select">
                                <label for="period" class="form-control-label">Choose Period Unit:</label>
                                <select class="form-control m-bootstrap-select selectpicker" name="period" id="period">
                                    <option value="1">1 day</option>
                                    <option value="7">1 week</option>
                                    <option value="14">2 weeks</option>
                                    <option value="30" selected>1 month</option>
                                    <option value="90">3 months</option>
                                    <option value="180">6 months</option>
                                    <option value="365">1 year</option>
                                </select>
                                <div class="form-control-feedback error-period"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-control-label">Price:</label>
                                    <input type="text" class="form-control price" name="price" id="price">
                                    <div class="form-control-feedback error-price"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slashed_price" class="form-control-label">Slashed Price: <i
                                                class="fa fa-info-circle tooltip_1" title="Slashed Price: Nullable"></i></label>
                                    <input type="text" class="form-control price" name="slashed_price"
                                           id="slashed_price">
                                    <div class="form-control-feedback error-slashed_price"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="standard" class="form-control-label">Set As Standard? <i
                                            class="fa fa-info-circle tooltip_1"
                                            title="Set as standard price"></i></label>
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
                                <label for="status" class="form-control-label">Status <i
                                            class="fa fa-info-circle tooltip_1"
                                            title="Set as standard price"></i></label>
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
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                                data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
      var item_id = '{{$position->id}}'
      var type = @json($position->type);
      @if($position->getFirstMediaUrl("thumbnail"))
        window.thumbnailUrl = '{{$position->getFirstMediaUrl("thumbnail")}}'
      @endif
      @if($position->getFirstMediaUrl("image"))
      var imageUrl = "{{$position->getFirstMediaUrl("image")}}"
      @endif
      var imageOption = {
        'size': "{{$position->type->width}},{{$position->type->height}}",
        'ratio': "{{$position->type->width}}:{{$position->type->height}}",
        download: true,
        buttonRemoveTitle: 'upload',
        instantEdit: false,
        maxFileSize: 50,
        label: 'Drop or choose image'
      }
    </script>
    <script src="{{asset('assets/js/admin/newsletterAds/positionEdit.js')}}"></script>
@endsection
