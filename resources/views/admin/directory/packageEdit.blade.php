@extends('layouts.master')

@section('title', 'Directory Listing Package')

@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['Directory Listing', 'Package', 'Edit']" :menuLinks="[]" />
    </div>
    <div class="col-md-6 text-right">
        <a href="{{route('admin.directory.package.index')}}" class="btn btn-outline-info m-btn m-btn--custom m-btn--square">Back</a>
    </div>
@endsection

@section('content')

    <x-layout.tabs-wrapper>
        <x-layoutItems.normal-tab active="1" link="all" title="Package Detail"/>
        <x-layoutItems.normal-tab active="0" link="price" title="Set Price"/>
        <x-layoutItems.normal-tab active="0" link="meeting" title="Meeting and Attach Form"/>
        <x-layoutItems.normal-tab active="0" link="status" title="Status"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody active="1" id="all_area">
        <x-form.form action="{{route('admin.directory.package.update', $item->id)}}">
            <div class="row">
                <div class="col-md-7">

                    <x-form.input name="name" label="Name" value="{{$item->name}}"/>
                    <x-form.textarea name="description" label="Description" >
                        {{$item->description}}
                    </x-form.textarea>

                    <h5>Features</h5>
                    <hr>
                    <div class="row" x-data="{unlimit:'{{$item->listing_limit==-1?1:0}}'}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="listing_number" class="form-control-label">
                                    Listing limit number:

                                    <i class="la la-info-circle tipso2"
                                       data-tipso-title="What is this?"
                                       data-tipso="This is directory listing limit number that users can put using this package."
                                    ></i>
                                </label>
                                <input type="number" class="form-control" name="listing_limit" id="listing_limit" x-bind:disabled="unlimit==1" value="{{$item->listing_limit!=-1?$item->listing_limit:''}}">
                                <div class="form-control-feedback error-listing_number"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <br>
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="unlimit" x-on:click="unlimit=unlimit==1?0:1" x-bind:checked="unlimit==1">
                                Set Unlimit Listing Number

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="If you check this field, it will give unlimited listing privilege to users"
                                ></i>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_thumbnail" {{optional($item->property)['thumbnail']==1? 'checked':''}}>
                                Allow to put thumbnail

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to put their image"
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_social" {{optional($item->property)['social']==1? 'checked':''}}>
                                Allow to put social media links

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to put their social media link"
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_featured" {{optional($item->property)['featured']==1? 'checked':''}}>
                                Allow featured listings

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to set featured listing. Featured listings will be searched first."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_image" {{optional($item->property)['image']==1? 'checked':''}}>
                                Allow to put image gallery

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to put image gallery in listing detail."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_links" {{optional($item->property)['links']==1? 'checked':''}}>
                                Allow to put external video links

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to put external video links."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_videos" {{optional($item->property)['videos']==1? 'checked':''}}>
                                Allow to upload custom videos

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to upload custom videos."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="m-checkbox m-checkbox--state-success">
                                <input type="checkbox" name="allow_tracking" {{optional($item->property)['tracking']==1? 'checked':''}}>
                                Allow to track impression

                                <i class="la la-info-circle tipso2"
                                   data-tipso-title="What is this?"
                                   data-tipso="This will allow for users to see impression tracking data."
                                ></i>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <hr>

                    <x-form.addImage >
                        @foreach($item->getMedia('image') as $key=>$image)
                            <tr>
                                <td>
                                    <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                                    <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                </td>
                                <td><img class='w-150px' src="{{$image->getUrl()}}"/></td>
                                <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                            </tr>
                        @endforeach
                    </x-form.addImage>

                    <x-form.addLink >
                        @foreach($item->getLinks() as $key1=>$link)
                            <tr>
                                <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}"></td>
                                <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                            </tr>
                        @endforeach
                    </x-form.addLink>

                    <x-form.uploadVideo >
                        @foreach($item->getMedia('video') as $key2=>$video)
                            <tr>
                                <td>
                                    <input type="text" class="form-control m-input--square" value="{{$video->getUrl()}}" readonly>
                                    <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                                </td>
                                <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                            </tr>
                        @endforeach
                    </x-form.uploadVideo>

                    <x-form.galleryOrder order="{{$item->order}}"/>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="thumbnail" class="form-control-label">Upload Image</label>
                        @php
                            $ratio_width = config("custom.variable.package_image_ratio_width");
                            $ratio_height=config("custom.variable.package_image_ratio_height");
                        @endphp
                        <div class="slim slimdiv"
                             data-download="true"
                             data-label="Drop or choose image"
                             data-max-file-size="50"
                             data-instant-edit="true"
                             data-button-remove-title="Upload"
                             data-ratio="{{$ratio_width}}:{{$ratio_height}}">
                            @if($item->getFirstMediaUrl("thumbnail"))<img src="{{$item->getFirstMediaUrl("thumbnail")}}" alt=""/>@endif
                            <input type="file" name="image" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <x-form.status label="Featured?" name="featured" checked="{{$item->featured? 'checked': ''}}"/>
                        </div>
                        <div class="col-4">
                            <x-form.status label="New?" name="new" checked="{{$item->new? 'checked': ''}}"/>
                        </div>
                        <div class="col-4">
                            <x-form.status label="Approve?" name="status" checked="{{$item->status? 'checked': ''}}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-4">
                <x-form.a link="{{route('admin.directory.package.index')}}" label="Back"/>
                <x-form.smtBtn type="success" label="Next" />
            </div>
        </x-form.form>
    </x-layout.portletBody>

    <x-layout.portletBody id="price_area" active="0">
        <x-layout.container>
            <h2>Set Prices</h2>
        </x-layout.container>
        @include("components.admin.common.priceArea")
    </x-layout.portletBody>

    @include("components.admin.common.meetingForm", ['action'=>route('admin.directory.package.updateMeetingForm', $item->id)])

    @include("components.admin.common.addPriceModal", ['action'=>route('admin.directory.package.createPrice', $item->id)])

    @include("components.admin.common.statusArea", ['action'=>route('admin.directory.package.switch'), 'status'=>$item->status])

@endsection
@section('script')
    <script>
        var item_id = "{{$item->id}}",
            getPriceUrl="{{route('admin.directory.package.edit', $item->id)}}",
            delPriceUrl="{{route('admin.directory.package.deletePrice', $item->id)}}";
    </script>
    <script src="{{asset('assets/js/admin/directory/packageEdit.js')}}"></script>
    <script src="{{asset('assets/js/admin/common/meetingForm.js')}}"></script>
    <script src="{{asset('assets/js/admin/common/price.js')}}"></script>
@endsection
