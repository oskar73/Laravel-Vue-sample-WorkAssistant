@extends('layouts.master')

@section('title', 'Ready Made BIZ')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6">
        <x-layout.breadcrumb :menus="['Ready Made BIZ', 'Edit Item']" :menuLinks="[]" />
    </div>
    <div class="col-md-6 text-right">
        <x-form.a link="{{route('admin.readymade.item.index')}}" label="Back" type="info"/>
    </div>
@endsection

@section('content')

    <x-layout.tabs-wrapper>
        <x-layoutItems.normal-tab title="Module Detail" link="all" active="1"/>
        <x-layoutItems.normal-tab title="Set Items" link="item" active="0"/>
        <x-layoutItems.normal-tab title="price" link="price" active="0"/>
        <x-layoutItems.normal-tab title="meeting and attach form" link="meeting" active="0"/>
    </x-layout.tabs-wrapper>

    <x-layout.portletBody id="all_area" active="1">
        <x-form.form action="{{route('admin.readymade.item.update', $item->id)}}">
            <div class="row">
                <div class="col-md-6">

                    <x-form.selectpicker label="Choose Category"  name="category" class="category">
                        <option value="" disabled selected>Choose Category</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @foreach($category->approvedSubCategories as $subcat)
                                <option value="{{$subcat->id}}">{{$category->name}} --> {{$subcat->name}}</option>
                            @endforeach
                        @endforeach
                    </x-form.selectpicker>

                    <x-form.input name="name" value="{{$item->name}}" label="Name"/>

                    <x-form.textarea label="Description" name="description">
                        {{$item->description}}
                    </x-form.textarea>

                    <x-form.addImage>
                        @foreach($item->getMedia('image') as $key=>$image)
                            <tr>
                                <td>
                                    <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                                    <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                                </td>
                                <td class="text-center">
                                    <figure data-href="{{$image->getUrl()}}" class="width-150 progressive replace m-auto">
                                        <img class='width-150 preview' src="{{$image->getUrl('thumb')}}"/>
                                    </figure>
                                </td>
                                <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                            </tr>
                        @endforeach
                    </x-form.addImage>
                    <x-form.addLink>
                        @foreach($item->getLinks() as $key1=>$link)
                            <tr>
                                <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}"></td>
                                <td><button class='btn btn-danger btn-sm delRowBtn'>X</button></td>
                            </tr>
                        @endforeach
                    </x-form.addLink>
                    <x-form.uploadVideo>
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

                    <x-form.galleryOrder name="order" order="{{$item->order}}"/>
                </div>
                <div class="col-md-6">
                    {{-- <x-form.thumbnail>
                        {{$item->getFirstMediaUrl("thumbnail")}}
                    </x-form.thumbnail> --}}
                    <div class="form-group slimdiv mb-5" style="300px; width: 300px; margin: auto">
                        <label for="thumbnail" class="form-control-label">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" />
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <x-form.status name="featured" label="Featured?" checked="{{$item->featured==1? 'checked':''}}"/>
                        </div>
                        <div class="col-4">
                            <x-form.status name="new" label="New?" checked="{{$item->new==1? 'checked':''}}"/>
                        </div>
                        <div class="col-4">
                            <x-form.status name="status" label="Approve?" checked="{{$item->status==1? 'checked':''}}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right mt-4">
                <x-form.smtBtn type="success" label="Submit" />
            </div>
        </x-form.form>
    </x-layout.portletBody>

    <x-layout.portletBody id="item_area" active="0">
        <x-layout.container>
            <h2>Set Items</h2>
            <form id="setItemForm" action="{{route('admin.readymade.item.updateModule', $item->id)}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="service" class="form-control-label">Choose Service:</label> <br>
                                    <select name="services[]" id="service" class="select2_item m-select2" multiple>
                                        @foreach($services as $service)
                                            <option value="{{$service->id}}"
                                                    @if(in_array($service->id, $item->services()->pluck("services.id")->toArray())) selected @endif
                                            >
                                                {{$service->name}} (${{formatNumber($service->price)}})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-services"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="lacarte" class="form-control-label">Choose A La Carte:</label> <br>
                                    <select name="lacartes[]" id="lacarte" class="select2_item m-select2" multiple>
                                        @foreach($lacartes as $lacarte)
                                            <option value="{{$lacarte->id}}"
                                                    @if(in_array($lacarte->id, $item->lacartes()->pluck("lacartes.id")->toArray())) selected @endif
                                            >
                                                {{$lacarte->name}} (${{formatNumber($lacarte->price)}})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-lacartes"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="plugin" class="form-control-label">Choose Plugin:</label> <br>
                                    <select name="plugins[]" id="plugin" class="select2_item m-select2" multiple>
                                        @foreach($plugins as $plugin)
                                            <option value="{{$plugin->id}}"
                                                    @if(in_array($plugin->id, $item->plugins()->pluck("plugins.id")->toArray())) selected @endif
                                            >
                                                {{$plugin->name}} (${{formatNumber($plugin->price)}})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-plugins"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="module" class="form-control-label">Choose Recommend Modules:</label> <br>
                                    <select name="modules[]" class="select2_item m-select2" multiple>
                                        @foreach($modules as $module)
                                            <option value="{{$module->id}}"
                                                    @if(in_array($module->id, $item->modules()->pluck("modules.id")->toArray())) selected @endif
                                            >
                                                {{$module->name}} {{$module->featured? '(Featured)':''}}, {{$module->standardPrice->getPriceText()}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-modules"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="free_domain_price" class="form-control-label">Domain Discount Price ($):</label> <br>
                            <input type="text" class="form-control m-input--square" name="free_domain_price" value="{{$item->domain}}">
                            <div class="form-control-feedback error-free_domain_price"></div>
                        </div>
                    </div>
                    <div class="col-md-6" x-data="{module:'{{$item->module==-1? 1:0}}',featured_module:'{{$item->featured_module==-1? 1:0}}',page:'{{$item->page==-1? 1:0}}',storage:'{{$item->storage==-1? 1:0}}',website:'{{$item->website==-1? 1:0}}'}">
                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="module_count" class="form-control-label">Number of available modules</label>
                                    <input type="text" class="form-control m-input--square" name="module_count" x-bind:disabled="module==1" value="{{$item->module==-1? '':$item->module}}">
                                    <div class="form-control-feedback error-module_count"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="module_unlimit">Set Unlimited</label>
                                    <div class="m-checkbox-list pt-2">
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="module_unlimit" id="module_unlimit" x-on:click="module=module==1?0:1" x-bind:checked="module==1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="featured_module_count" class="form-control-label">Number of available featured modules</label>
                                    <input type="text" class="form-control m-input--square" name="featured_module_count" x-bind:disabled="featured_module==1" value="{{$item->featured_module==-1? '':$item->featured_module}}">
                                    <div class="form-control-feedback error-featured_module_count"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="featured_unlimit">Set Unlimited</label>
                                    <div class="m-checkbox-list pt-2">
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="featured_module_unlimit" id="featured_unlimit" x-on:click="featured_module=featured_module==1?0:1" x-bind:checked="featured_module==1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="page_count" class="form-control-label">Number of available pages</label>
                                    <input type="text" class="form-control m-input--square" name="page_count" x-bind:disabled="page==1" value="{{$item->page==-1? '':$item->page}}">
                                    <div class="form-control-feedback error-page_count"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="page_unlimit">Set Unlimited</label>
                                    <div class="m-checkbox-list pt-2">
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="page_unlimit" id="page_unlimit" x-on:click="page=page==1?0:1" x-bind:checked="page==1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="storage" class="form-control-label">Storage Limit (GB)</label>
                                    <input type="text" class="form-control m-input--square" name="storage" x-bind:disabled="storage==1" value="{{$item->storage==-1? '':$item->storage}}">
                                    <div class="form-control-feedback error-storage"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="storage_unlimit">Set Unlimited</label>
                                    <div class="m-checkbox-list pt-2">
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="storage_unlimit" id="storage_unlimit" x-on:click="storage=storage==1?0:1" x-bind:checked="storage==1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="website_count" class="form-control-label">Number of available websites</label>
                                    <input type="text" class="form-control m-input--square" name="website_count" x-bind:disabled="website==1" value="{{$item->website==-1? '':$item->website}}">
                                    <div class="form-control-feedback error-website_count"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="website_unlimit">Set Unlimited</label>
                                    <div class="m-checkbox-list pt-2">
                                        <label class="m-checkbox m-checkbox--state-success">
                                            <input type="checkbox" name="website_unlimit" id="website_unlimit" x-on:click="website=website==1?0:1" x-bind:checked="website==1">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success setItemSmtBtn">Save</button>
                </div>
            </form>
        </x-layout.container>
    </x-layout.portletBody>

    <x-layout.portletBody id="price_area" active="0">
        <x-layout.container>
            <h2>Set Prices</h2>
        </x-layout.container>
        @include("components.admin.common.priceArea")
    </x-layout.portletBody>

    @include("components.admin.common.meetingForm", ['action'=>route('admin.readymade.item.updateMeetingForm', $item->id)])

    @include("components.admin.common.addPriceModal", ['action'=>route('admin.readymade.item.createPrice', $item->id)])

@endsection
@section('script')
    <script type="text/javascript" src="{{s3_asset('vendors/cropper/cropper.js')}}"></script>
    <script>
        var category = "{{$item->category_id}}",
            item_id = "{{$item->id}}";
            getPriceUrl="{{route('admin.readymade.item.edit', $item->id)}}",
            delPriceUrl="{{route('admin.readymade.item.deletePrice', $item->id)}}",
            ratio_width = "{{config("custom.variable.package_image_ratio_width")}}",
            ratio_height="{{config("custom.variable.package_image_ratio_height")}}",
            maxImageSize = "{{config('custom.variable.max_image_size')}}";

        @if($item->getFirstMediaUrl("thumbnail"))
            window.thumbNailUrl = '{{$item->getFirstMediaUrl("thumbnail")}}';
        @endif
    </script>
    <script src="{{asset('assets/js/account/image_crop.js')}}"></script>
    <script src="{{asset('assets/js/admin/readymade/itemEdit.js')}}"></script>
    <script src="{{asset('assets/js/admin/common/meetingForm.js')}}"></script>
    <script src="{{asset('assets/js/admin/common/price.js')}}"></script>
@endsection
