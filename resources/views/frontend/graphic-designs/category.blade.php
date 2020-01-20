@extends('layouts.app')

@section('title', 'Bizinabox Graphics Design Categories - ' . $graphic->title . ' - ' . $category->name)

@section('seo')
    <link rel="canonical"
          href="{{ config('app.url') }}/graphics/category/{{ $graphic->slug }}/design-categories{{ isset($categorySlug) ? '?category_slug=' . $categorySlug : '' }}">
@endsection

@section('content')

    <x-front.hero
            :image="$frontSetting['header_image'] ?? ''"
            :thumbImage="$frontSetting['box_image_thumbnail'] ?? ''"
    ></x-front.hero>

    <div class="divider-60"></div>

    <div class="container mx-w-1350px overflow-x-auto cat_scroll_container categories pt-3">
        <div class="row flex-row flex-nowrap">
            @foreach($categories as $cat)
                <div class="col-md-2 col-sm-3 col-6">
                    <a href="{{route('graphics.design-categories',['slug' => $graphic->slug, 'category_slug' => $cat->slug])}}"
                       class="d-block item-preview_container {{$category->id == $cat->id? 'active':''}}"
                       id="category_item_{{$cat->id}}"
                       data-id="{{$cat->id}}"
                    >
                        <div class="preview">
                            <div class="row no-gutters thumbnail">
                                <div class="col-12 position-relative">
                                    <div class="thumbnail_overlay">
                                        <div class="img_area">
                                            <figure data-href="{{ $cat->getFirstMediaUrl("thumbnail")}}"
                                                    class="w-100 progressive replace img-bg-effect-container mb-0 z-0">
                                                <img src="{{ $cat->getFirstMediaUrl("thumbnail", "thumb")}}"
                                                     alt="{{$cat->name}}" class="preview" />
                                            </figure>
                                        </div>
                                        <div class="edit-template-container overflow-hidden h-cursor">
                                            <div class="fs-12px hide-white-loading text-black p-3 line-height-14px cat_desc_text">
                                                {{$cat->description}}
                                            </div>
                                        </div>
                                        <div class="cat_desc_area">
                                            <span class="fs-12px">{{$cat->name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>


    <div class="divider"></div>

    <div class="container mx-w-1350px">
        <div class="tw-grid tw-grid-cols-4 tw-gap-4">
            @forelse($category->designs as $key=>$design)
                <div class="tw-w-full tw-flex tw-justify-center tw-items-center">
                    <div class="tw-relative tw-w-full " style="padding-top: 100%">
                        <div class="tw-absolute tw-w-full tw-h-full top-0 tw-flex tw-justify-center tw-items-center tw-border tw-group tw-cursor-pointer">
                            <figure data-href="{{$design->preview}}" class="w-100 progressive replace m-0">
                                <img src="{{$design->preview}}" class="preview img-fill" alt="Design Preview Image" />
                            </figure>
                            <a class="tw-absolute tw-shadow-lg tw-text-gray-700 tw-bg-white tw-py-2 tw-px-10 focus:tw-bg-white tw-hidden group-hover:tw-block"
                               href="{{route('graphics.choose', $design->hash)}}">EDIT TEMPLATE</a>
                        </div>
                        @if($design->premium)
                            <span class="tw-absolute tw-right-2 tw-bottom-2 tw-border tw-border-pink-500 tw-text-pink-500 tw-p-1 tw-text-xs">PREMIUM</span>
                        @else
                            <span class="tw-absolute tw-right-2 tw-bottom-2 tw-border tw-border-green-500 tw-text-green-500 tw-p-1 tw-text-xs">FREE</span>
                        @endif
                    </div>
                </div>

            @empty
                <div class="w-100 text-center">
                    <h3>No items...</h3>
                </div>
            @endforelse
        </div>
    </div>
@endsection
@section('script')
    @if($category)
        <script>
          vector_category_id = "{{$category->id??''}}"
          let scroll_container = $('.cat_scroll_container')
          let distance = $(`#category_item_${vector_category_id}`).offset().left - scroll_container.offset().left
          scroll_container.animate({ scrollLeft: distance }, 500)
        </script>
    @endif
    <script src="{{asset('assets/js/front/directory/category.js')}}"></script>
@endsection
