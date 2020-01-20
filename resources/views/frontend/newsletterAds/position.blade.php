@extends('layouts.app')

@section('title', 'Newsletter Advertisement Position - ' . $position->name)

@section('seo')
    <link rel="canonical" href="{{ config('app.url') }}/newsletterAds/position/{{ $position->slug }}">
@endsection

@section('style')
    <link rel="stylesheet" href="{{s3_asset('vendors/lightgallery/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/calendar/fullcalendar.css')}}">
@endsection
@section('content')
    <x-front.hero>Newsletter Advertisement</x-front.hero>
    <form action="{{route('newsletterAds.addtocart', $position->id)}}" id="submitForm">
        @csrf
        <div class="container mt-3">
            <x-front.blog-nav></x-front.blog-nav>

            <div class="items_result blog_search_remove_section">
                <div class="my-5">
                    <h1 class="text-center font-size24">{{$position->name}}</h1>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="lightgallery">
                                <a href="{{$position->getFirstMediaUrl("thumbnail")}}"
                                   class="progressive replace h-cursor box-shadow-dark"
                                >
                                    <img src="{{$position->getFirstMediaUrl("thumbnail")}}" alt=""
                                         class="preview w-100">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            {{$position->description}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Position Name</label>
                                <input type="text" class="form-control border-radius-none bg-transparent" id="name"
                                       value="{{$position->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="area">Ad Area</label>
                                <input type="text" class="form-control border-radius-none bg-transparent" id="area"
                                       value="{{$position->type->name}} ({{$position->type->width}}x{{$position->type->height}}px)"
                                       readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Price Option</label>
                            <div class="m-radio-list">
                                @foreach($position->prices as $price)
                                    <label class="m-radio m-radio--state-primary">
                                        <input type="radio" name="price" value="{{$price->id}}"
                                               @if($price->standard) checked @endif data-price="{{$price}}">
                                        @if($price->slashed_price)
                                            <p class="slashed_price_text d-inline-block mb-0 ml-0">
                                                ${{$price->slashed_price}}</p>
                                        @endif
                                        ${{$price->price}} / {{$price->getUnit()}}
                                        <span></span>
                                    </label> <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{route('newsletterAds.index')}}" class="btn border-radius-none btn-outline-primary">Back</a>
                        <button type="submit" class="btn border-radius-none btn-outline-success smtBtn">Add to cart
                        </button>
                        <a href="{{route('cart.index')}}" class="btn border-radius-none btn-outline-success d-none">
                            Go to cart <i class="fas fa-arrow-right margin-5px-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="calendar_area blog_search_remove_section mt-3 p-lg-5">
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <div id="calendar"></div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-primary border-radius-none" role="alert">
                        Select Period
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table ajaxTable datatable">
                            <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Price ($)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="dynamic_date_field">
                            </tbody>
                        </table>
                        <div class="form-group text-right">
                            <div class="font-size20">Total Price : $<span class="total_price">0.00</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="blog_append_section"></div>
@endsection
@section('script')
    <script src="{{s3_asset('vendors/lightgallery/js/lightgallery-all.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/calendar/moment.js') }}"></script>
    <script src="{{ asset('assets/vendors/calendar/fullcalendar.js') }}"></script>
    <script>
      g_price = {!! $position->standardPrice !!};
      g_type = {!! $position->type !!};
      slug = '{{$position->slug}}'
    </script>
    <script src="{{asset('assets/js/front/newsletterAds/position.js')}}"></script>
    <script src="{{asset('assets/js/dev2/calendar-hover.js')}}"></script>
@endsection
