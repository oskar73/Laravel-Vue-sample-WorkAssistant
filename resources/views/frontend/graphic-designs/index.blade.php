@extends('layouts.app')

@section('title', $seo['title']??'Bizinabox LogoType Category - ' . $graphic->title)

@section('seo')
    @include('components.front.seo')
    <link rel="canonical" href="{{ config('app.url') }}/graphics/category/{{ $graphic->slug }}">
@endsection

@section('content')
    <x-front.hero :image="$frontSetting['header_image'] ?? ''" :thumbImage="$frontSetting['box_image_thumbnail'] ?? ''" ></x-front.hero>

    <div class="tw-w-full tw-max-w-7xl tw-mx-auto tw-my-10">
        <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-6 tw-gap-1 place-items-center">
            @foreach(array_slice($designs, 0, 8) as $box)
                @include('components.front.graphic-design-item')
            @endforeach
            <div class="tw-col-span-2 md:tw-grid-rows-2">
                <a href="{{route('graphics.design-categories', $graphic->slug)}}" class="d-block box-item-container">
                    <div class="row no-gutters thumbnail">
                        <div class="col-12 position-relative">
                            <div class="preview-wrapper">
                                <div class="thumbnail_overlay">
                                    <div class="img_area">
                                        <figure data-href="{{ $frontSetting['box_image'] ?? '' }}" class="w-100 progressive replace m-0">
                                            <img src="{{ $frontSetting['box_image_thumbnail'] ?? '' }}" alt="box image thumbnail" class="preview img-full" />
                                        </figure>
                                    </div>
                                    <div class="center-title">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @foreach(array_slice($designs, 8, 15) as $box)
                @include('components.front.graphic-design-item')
            @endforeach
        </div>
    </div>
@endsection
