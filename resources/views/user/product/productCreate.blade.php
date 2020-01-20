@extends('layouts.master')

@section('title', 'Product Create')
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
                <a href="{{ route('user.product.index') }}" class="m-nav__link">
                    <span class="m-nav__link-text">Product</span>
                </a>
            </li>
            <li class="m-nav__separator">/</li>
            <li class="m-nav__item">
                <a href="" class="m-nav__link">
                    <span class="m-nav__link-text">Create</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile tab_area area-active md-pt-50">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Product Create
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form action="{{route('user.product.store')}}" id="submit_form">
                @csrf
                <div class="container">
                    <div class="row mb-5">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Name</label>
                                <input type="text" class="form-control m-input--square minh-50" name="name" id="name" placeholder="Name" value="{{old('name')}}">
                                <div class="form-control-feedback error-name"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="" for="price">Price</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" class="form-control" id="price" name="price" min="1" max="100000" placeholder="Price" value="{{old('price')}}">
                                        <div class="form-control-feedback error-price"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="100000" placeholder="Quantity" value="{{old('quantity')}}">
                                    <div class="form-control-feedback error-quantity"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="unit_id">Unit</label>
                                    <select id="unit_id" name="unit_id" class="form-control">
                                        <option value="" selected>Choose...</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-unit_id"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="form-control-label">Category</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    <option value="" selected>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-category_id"></div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-control-label">Description</label>
                                <textarea class="form-control m-input--square minh-100" name="description" id="description">{{old('description')}}</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="sizeId">Size</label>
                                    <select id="sizeId" class="form-control">
                                        <option value="" selected>Choose...</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-sizeId"></div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label class="" for="sizePrice">Price</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" class="form-control" id="sizePrice" min="1" max="100000" placeholder="Size Price">
                                        <div class="form-control-feedback error-price"></div>
                                    </div>
                                    <div class="form-control-feedback error-sizePrice"></div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="addSize">&nbsp;</label>
                                    <a href="javascript:void(0);" id="addSize" class="btn m-btn--square m-btn--sm btn-outline-success mb-2"
                                        style="display:block; width:31px; height:31px;">
                                            + </a>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label for="colorId">Color</label>
                                    <select id="colorId" class="form-control">
                                        <option value="" selected>Choose...</option>
                                        @foreach ($colors as $color)
                                            <option style="color:{{ $color->color_code }}" value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback error-colorId"></div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label class="" for="colorPrice">Price</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" class="form-control" id="colorPrice" min="1" max="100000" placeholder="Color Price">
                                        <div class="form-control-feedback error-price"></div>
                                    </div>
                                    <div class="form-control-feedback error-colorPrice"></div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="addSize">&nbsp;</label>
                                    <a href="javascript:void(0);" id="addColor" class="btn m-btn--square m-btn--sm btn-outline-success mb-2"
                                        style="display:block; width:31px; height:31px;">
                                            + </a>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="list_view mt-4">
                                    <div class="table-responsive">
                                        <table class="table table-hover ajaxTable datatable block-datatable">
                                            <thead>
                                                <tr>
                                                    <th> Content </th>
                                                    <th> Type </th>
                                                    <th> Price </th>
                                                    <th> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody class="table_body" id="additionalTableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label>Status</label> <br>
                                    <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                        <label>
                                            <input type="checkbox" checked="checked" name="status">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <div class="mt-3 text-right">
                                        <a href="{{route('user.product.index')}}" class="m-1 btn m-btn-square btn-outline-info m-btn m-btn--custom">Back</a>
                                        <button type="submit" class="m-1 btn m-btn-square btn-outline-success m-btn m-btn--custom smtBtn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="thumbnail" class="form-control-label">Upload Image</label>
                                <div class="slimdiv">
                                    <input type="file" name="image" id="slimInput"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/slim/slim.kickstart.min.js')}}"></script>
    <script src="{{asset('assets/js/user/product/productCreate.js')}}"></script>
@endsection
