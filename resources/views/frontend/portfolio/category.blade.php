@extends('layouts.app')

@section('title', 'Bizinabox Portfolio Category - ' . $category->title)

@section('seo')
    @if(isset($categories))
        <link rel="canonical" href="{{ config('app.url') }}/portfolio/categories">
    @else
        <link rel="canonical" href="{{ config('app.url') }}/portfolio/category/{{ $category->slug }}">
    @endif
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/page/front/portfolio.css')}}" />
@endsection

@section('content')

    <x-front.hero image="/uploads/portfolio.front.header.image.png" />

    <div class="container mx-w-1350px">

        <div class="divider-60"></div>

        <x-front.portfolio-category :selected="$category->id" />

        <div class="divider"></div>

        <div id="search_result_append">
            <div class="row mt-4">
                @if(count($items))
                    @foreach($items as $key=>$item)
                        <div class="w-lg-1-5 col-md-4 col-6 mb-3">
                            <x-front.portfolio-item :item="$item" />
                        </div>
                    @endforeach
                @else
                    <div class="w-100 text-center">
                        <h4 class="text-gray">No items..</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/front/portfolio/index.js')}}"></script>
    <script>
      vector_category_id = "{{$category->id}}"
      let scroll_container = $('.cat_scroll_container')
      let distance = $(`#category_item_${vector_category_id}`).offset().left - scroll_container.offset().left
      scroll_container.animate({ scrollLeft: distance }, 500)
    </script>
@endsection
