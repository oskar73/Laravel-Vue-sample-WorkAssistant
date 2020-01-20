@extends('layouts.app')

@section('title', 'Bizinabox Directory Listing Tag - ' . $tag->name)

@section('seo')
    <link rel="canonical" href="{{ config('app.url') }}/directory/tag/{{ $tag->slug }}">
@endsection

@section('style')
@endsection
@section('content')

    <x-front.hero>Directory</x-front.hero>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <div class="directory-ads-position-1114 text-right"></div>
                <div class="directory-ads-position-1115 text-right"></div>
            </div>
            <div class="col-lg-8">

                <x-front.directory-nav></x-front.directory-nav>

                <div class="search_append_area py-4">
                    <div class="directory-ads-position-1111 text-center"></div>
                    <div class="directory_category_area">
                        <a href="{{route('directory.index')}}"><i class="fa fa-home"></i></a> &rarr; <span class="text-center w-100">{{$tag->name}}</span>
                    </div>
                    <div class="directory-ads-position-1112 text-center"></div>
                    <div class="directory_item_area">
                        <div class="row featured_listing_area">
                            <div class="text-center w-100">
                                <i class="fa fa-spin fa-spinner fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="directory-ads-position-1113 text-center"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="directory-ads-position-1116 text-left"></div>
                <div class="directory-ads-position-1117 text-left"></div>
            </div>
        </div>
    </div>
    <input type="hidden" id="tag_id" value="{{$tag->id}}">
    <input type="hidden" id="category_id" value="0">
    <input type="hidden" id="disable_listings" value="0">

    <input type="hidden" id="adtype" value="tag">
    <input type="hidden" id="adid" value="{{$tag->id}}">
@endsection
@section('script')
    <script>var directory_url = "{{route('directory.index')}}"</script>
    <script src="{{asset('assets/js/front/directory/index.js')}}"></script>
@endsection
