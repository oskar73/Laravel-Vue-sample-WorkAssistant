@extends('layouts.app')

@section('title', 'Bizinabox Directory Category - ' . $category->name)

@section('seo')
    <link rel="canonical" href="{{ config('app.url') }}/directory/category/{{ $category->slug }}">
@endsection

@section('content')

    <x-front.hero
        image="{{uploadUrl(option('directory.front.header.image'),'header.png')}}"
        thumbImage="{{uploadUrl(option('directory.front.header.image.thumb'),'header-thumb.png')}}"
    ></x-front.hero>

    <div class="divider-60"></div>

    <x-front.directory-category :selected="$category->id??''"/>

    <div class="divider"></div>

    <div class="container mx-w-1350px">
        <div id="search_result_append" class="boxes">
            <div class="row mt-4">
                @forelse($items as $key=>$item)
                    <div class="col-lg-3 col-md-4 col-6 mb-3">
                        <x-front.directory-item :item="$item"/>
                    </div>
                @empty
                    <div class="w-100 text-center">
                        <h3>No items..</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/js/front/directory/category.js')}}"></script>
    @if($category)
    <script>
        vector_category_id = "{{$category->id??''}}";
        let scroll_container = $('.cat_scroll_container');
        let distance = $(`#category_item_${vector_category_id}`).offset().left - scroll_container.offset().left;
        scroll_container.animate({scrollLeft: distance}, 500);
    </script>
    @endif
@endsection
